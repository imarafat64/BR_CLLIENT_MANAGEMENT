<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class ClientController extends Controller
{
    /**
     * Display the client dashboard index view.
     */
    public function index()
    {
        return view('clients.index');
    }

    /**
     * Retrieve clients with search, filters, pagination, and sorting.
     */
    public function list(Request $request)
    {
        $query = Client::query();

        // 1. General search query (AND/OR/Exact match)
        if ($request->has('search') && !empty($request->search)) {
            $searchText = trim($request->search);
            $mode = $request->get('match_mode', 'any'); // 'any', 'all', 'exact'
            
            if ($mode === 'exact') {
                $query->where(function ($q) use ($searchText) {
                    $q->where('id', 'like', "%{$searchText}%")
                      ->orWhere('full_name', 'like', "%{$searchText}%")
                      ->orWhere('tin_number', 'like', "%{$searchText}%");
                });
            } else {
                $tokens = array_filter(explode(' ', $searchText));
                if ($mode === 'all') {
                    foreach ($tokens as $token) {
                        $query->where(function ($q) use ($token) {
                            $q->where('id', 'like', "%{$token}%")
                              ->orWhere('full_name', 'like', "%{$token}%")
                              ->orWhere('tin_number', 'like', "%{$token}%");
                        });
                    }
                } else { // 'any'
                    $query->where(function ($q) use ($tokens) {
                        foreach ($tokens as $token) {
                            $q->orWhere('id', 'like', "%{$token}%")
                              ->orWhere('full_name', 'like', "%{$token}%")
                              ->orWhere('tin_number', 'like', "%{$token}%");
                        }
                    });
                }
            }
        }

        // 2. Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // 3. Advanced Filter: Client ID
        if ($request->has('filter_client_id') && !empty($request->filter_client_id)) {
            $query->where('id', 'like', "%{$request->filter_client_id}%");
        }

        // 4. Advanced Filter: Full Name
        if ($request->has('filter_fullname') && !empty($request->filter_fullname)) {
            $query->where('full_name', 'like', "%{$request->filter_fullname}%");
        }

        // 5. Advanced Filter: TIN Number
        if ($request->has('filter_tin') && !empty($request->filter_tin)) {
            $query->where('tin_number', 'like', "%{$request->filter_tin}%");
        }

        // 6. Advanced Filter: Created Date Range
        if ($request->has('filter_created_start') && !empty($request->filter_created_start)) {
            $query->whereDate('created_at', '>=', $request->filter_created_start);
        }
        if ($request->has('filter_created_end') && !empty($request->filter_created_end)) {
            $query->whereDate('created_at', '<=', $request->filter_created_end);
        }

        // 7. Advanced Filter: Updated Date Range
        if ($request->has('filter_updated_start') && !empty($request->filter_updated_start)) {
            $query->whereDate('updated_at', '>=', $request->filter_updated_start);
        }
        if ($request->has('filter_updated_end') && !empty($request->filter_updated_end)) {
            $query->whereDate('updated_at', '<=', $request->filter_updated_end);
        }

        // Sorting
        $sortField = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');
        // Validate sort field to prevent SQL injection
        $allowedSorts = ['id', 'full_name', 'tin_number', 'password', 'created_at', 'updated_at', 'status'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->orderBy('id', 'asc');
        }

        // Pagination
        $perPage = intval($request->get('per_page', 10));
        if (!in_array($perPage, [10, 25, 50])) {
            $perPage = 10;
        }

        $clients = $query->paginate($perPage);

        // Compute dashboard statistics (counts)
        $totalClients = Client::count();
        $savedCount = Client::where('status', 'Saved')->count();
        $pendingCount = Client::where('status', 'Pending')->count();

        return response()->json([
            'data' => $clients->items(),
            'current_page' => $clients->currentPage(),
            'last_page' => $clients->lastPage(),
            'per_page' => $clients->perPage(),
            'total' => $clients->total(),
            'from' => $clients->firstItem(),
            'to' => $clients->lastItem(),
            'stats' => [
                'total_clients' => $totalClients,
                'saved_clients' => $savedCount,
                'pending_clients' => $pendingCount,
            ]
        ]);
    }

    /**
     * Store a newly created client in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'tin_number' => 'required|string|digits:12|unique:clients,tin_number',
            'password' => 'required|string|min:8',
            'status' => 'required|string|in:Saved,Pending',
        ]);

        $client = Client::create([
            'full_name' => $request->full_name,
            'tin_number' => $request->tin_number,
            'password' => $request->password, // Password automatically hashed by Eloquent model cast
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Client successfully added!',
            'client' => $client
        ], 201);
    }

    /**
     * Display the specified client's details.
     */
    public function show(Client $client)
    {
        return response()->json($client);
    }

    /**
     * Update the specified client in the database.
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'tin_number' => 'required|string|digits:12|unique:clients,tin_number,' . $client->id,
            'password' => 'nullable|string|min:8',
            'status' => 'required|string|in:Saved,Pending',
        ]);

        $data = [
            'full_name' => $request->full_name,
            'tin_number' => $request->tin_number,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password; // Automatically hashed
        }

        $client->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Client successfully updated!',
            'client' => $client
        ]);
    }

    /**
     * Remove the specified client from the database.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json([
            'success' => true,
            'message' => 'Client deleted successfully!'
        ]);
    }
}
