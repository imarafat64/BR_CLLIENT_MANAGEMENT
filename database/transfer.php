<?php

require __DIR__ . '/../vendor/autoload.php';

// Boot Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

// Path to SQLite database
$sqlitePath = database_path('database.sqlite');

echo "Target SQLite database file: {$sqlitePath}\n";

// Ensure SQLite file exists and is empty/fresh
if (file_exists($sqlitePath)) {
    unlink($sqlitePath);
}
touch($sqlitePath);
echo "Created fresh SQLite database file.\n";

// Configure sqlite connection on-the-fly to ensure it points to the correct absolute path
config(['database.connections.sqlite.database' => $sqlitePath]);

echo "Running migrations on SQLite connection to build identical schema...\n";
Artisan::call('migrate', [
    '--database' => 'sqlite',
    '--force' => true
]);
echo Artisan::output();
echo "Schema built successfully.\n";

// Get list of tables from MySQL (excluding system views/etc if any)
// In MySQL, we can show tables
$tablesResult = DB::connection('mysql')->select('SHOW TABLES');
$dbName = config('database.connections.mysql.database');
$keyName = "Tables_in_{$dbName}";

$tables = [];
foreach ($tablesResult as $row) {
    $tables[] = $row->$keyName;
}

echo "Found " . count($tables) . " tables in MySQL to transfer.\n";

// Disable foreign key constraints on SQLite before copying
DB::connection('sqlite')->statement('PRAGMA foreign_keys = OFF;');

foreach ($tables as $table) {
    if ($table === 'migrations') {
        echo "Skipping migrations table data (already populated by migrate command).\n";
        continue;
    }

    echo "Transferring table '{$table}'...\n";
    
    // Get row count
    $count = DB::connection('mysql')->table($table)->count();
    echo "  Rows to copy: {$count}\n";
    
    if ($count === 0) {
        continue;
    }

    // Chunk through MySQL records and insert into SQLite
    DB::connection('mysql')->table($table)->orderBy(
        // Fallback or attempt ordering by id if exists, otherwise just chunk/get
        Schema::connection('mysql')->hasColumn($table, 'id') ? 'id' : DB::raw('1')
    )->chunk(500, function ($rows) use ($table) {
        $data = [];
        foreach ($rows as $row) {
            $data[] = (array) $row;
        }
        DB::connection('sqlite')->table($table)->insert($data);
    });

    echo "  Completed table '{$table}'\n";
}

// Re-enable foreign key constraints
DB::connection('sqlite')->statement('PRAGMA foreign_keys = ON;');

echo "Database transfer from MySQL to SQLite completed successfully!\n";
