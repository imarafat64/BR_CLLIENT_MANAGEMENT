<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Client Management Dashboard</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/dashboard.css', 'resources/js/dashboard.js']); ?>
</head>

<body>

    <!-- SVG Sprite Definitions -->
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="icon-chevrons-left" viewBox="0 0 24 24">
            <path d="m11 17-5-5 5-5" />
            <path d="m18 17-5-5 5-5" />
        </symbol>
        <symbol id="icon-search" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.3-4.3" />
        </symbol>
        <symbol id="icon-moon" viewBox="0 0 24 24">
            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
        </symbol>
        <symbol id="icon-sun" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="4" />
            <path
                d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41" />
        </symbol>
        <symbol id="icon-dollar-sign" viewBox="0 0 24 24">
            <line x1="12" x2="12" y1="2" y2="22" />
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
        </symbol>
        <symbol id="icon-check-circle" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" />
            <path d="m9 12 2 2 4-4" />
        </symbol>
        <symbol id="icon-clock" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" />
            <polyline points="12 6 12 12 16 14" />
        </symbol>
        <symbol id="icon-alert-circle" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" x2="12" y1="8" y2="12" />
            <line x1="12" x2="12.01" y1="16" y2="16" />
        </symbol>
        <symbol id="icon-file-text" viewBox="0 0 24 24">
            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
            <path d="M10 9H8" />
            <path d="M16 13H8" />
            <path d="M16 17H8" />
        </symbol>
        <symbol id="icon-plus" viewBox="0 0 24 24">
            <path d="M5 12h14" />
            <path d="M12 5v14" />
        </symbol>
        <symbol id="icon-download" viewBox="0 0 24 24">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
            <polyline points="7 10 12 15 17 10" />
            <line x1="12" x2="12" y1="15" y2="3" />
        </symbol>
        <symbol id="icon-settings" viewBox="0 0 24 24">
            <path
                d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
            <circle cx="12" cy="12" r="3" />
        </symbol>
        <symbol id="icon-chevrons-up-down" viewBox="0 0 24 24">
            <path d="m7 15 5 5 5-5" />
            <path d="m7 9 5-5 5 5" />
        </symbol>
        <symbol id="icon-eye" viewBox="0 0 24 24">
            <path
                d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z" />
            <circle cx="12" cy="12" r="3" />
        </symbol>
        <symbol id="icon-pencil" viewBox="0 0 24 24">
            <path d="M12 20h9" />
            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
        </symbol>
        <symbol id="icon-trash" viewBox="0 0 24 24">
            <path d="M3 6h18" />
            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
            <line x1="10" x2="10" y1="11" y2="17" />
            <line x1="14" x2="14" y1="11" y2="17" />
        </symbol>
        <symbol id="icon-check" viewBox="0 0 24 24">
            <path d="M20 6 9 17l-5-5" />
        </symbol>
        <symbol id="icon-chevron-left" viewBox="0 0 24 24">
            <path d="m15 18-6-6 6-6" />
        </symbol>
        <symbol id="icon-chevron-right" viewBox="0 0 24 24">
            <path d="m9 18 6-6-6-6" />
        </symbol>
    </svg>

    <header class="navbar">
        <div class="nav-left">
            <!-- Left Side Navbar -->
        </div>
        <div class="nav-right">
            <button class="theme-toggle" aria-label="Toggle Theme"><svg class="icon icon-lg">
                    <use href="#icon-moon"></use>
                </svg></button>
            <div class="user-profile">
                <div class="user-info">
                    <span class="user-name"><?php echo e(Auth::user()->name); ?></span>
                    <span class="user-role">Administrator</span>
                </div>
                <div class="avatar"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 2))); ?></div>
                <div class="dropdown" style="position:relative; display:inline-block;">
                    <button type="button" class="dropdown-toggle" style="transition: transform 0.2s ease;"
                        onclick="var menu=document.getElementById('user-dropdown-menu'); var isOpen=menu.style.opacity==='1'; menu.style.opacity=isOpen?'0':'1'; menu.style.visibility=isOpen?'hidden':'visible'; this.style.transform=isOpen?'rotate(0deg)':'rotate(180deg)';">▼</button>
                    <div class="dropdown-menu" id="user-dropdown-menu"
                        style="opacity:0; visibility:hidden; transition: opacity 0.2s ease, transform 0.2s ease; position:absolute; right:0; background:#fff; min-width:120px; border:1px solid #ccc; box-shadow:0 2px 8px rgba(0,0,0,0.15); z-index:1000;">
                        <a href="<?php echo e(route('logout')); ?>" class="nav-link" style="display:block; padding:8px 12px;"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </div>
                </div>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display:none;">
                    <?php echo csrf_field(); ?>
                </form>
            </div>
        </div>
    </header>

    <main class="main-container">

        <section class="financial-grid">
            <div class="fin-card">
                <div class="card-icon-box icon-total">
                    <svg class="icon icon-xl">
                        <use href="#icon-file-text"></use>
                    </svg>
                </div>
                <div class="card-details">
                    <span class="card-label">Total Clients</span>
                    <span class="card-value" id="stat-total-clients">0</span>
                </div>
            </div>
            <div class="fin-card">
                <div class="card-icon-box icon-collected">
                    <svg class="icon icon-xl">
                        <use href="#icon-check-circle"></use>
                    </svg>
                </div>
                <div class="card-details">
                    <span class="card-label">Saved</span>
                    <span class="card-value" id="stat-saved-clients">0</span>
                    <span class="card-subtext sub-success">Completed records</span>
                </div>
            </div>
            <div class="fin-card">
                <div class="card-icon-box icon-pending">
                    <svg class="icon icon-xl">
                        <use href="#icon-clock"></use>
                    </svg>
                </div>
                <div class="card-details">
                    <span class="card-label">Pending</span>
                    <span class="card-value" id="stat-pending-clients">0</span>
                    <span class="card-subtext sub-warning">Awaiting review</span>
                </div>
            </div>
        </section>

        <section class="section-header">
            <div class="title-group">
                <span class="label">Portal</span>
                <h1>Client Directory</h1>
            </div>
            <div class="action-buttons">
                <button class="btn btn-primary" id="add-alumni-btn"><svg class="icon">
                        <use href="#icon-plus"></use>
                    </svg> Add Client</button>
            </div>
        </section>

        <div class="controls-bar">
            <div class="search-box-group">
                <div class="table-search">
                    <svg class="icon table-search-icon">
                        <use href="#icon-search"></use>
                    </svg>
                    <input type="text" id="search-input" placeholder="Search by name, ID, or TIN...">
                </div>
                <button class="btn btn-primary" id="search-btn">
                    <svg class="icon">
                        <use href="#icon-search"></use>
                    </svg>
                    <span>Search</span>
                </button>
                <button class="btn btn-secondary" id="advanced-toggle-btn" title="Advanced Search Options">
                    <svg class="icon">
                        <use href="#icon-settings"></use>
                    </svg>
                    <span>Filters</span>
                </button>
            </div>
            <div class="filter-group">
                <select id="filter-status">
                    <option value="">Filter by Status</option>
                    <option value="Saved">Saved</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
        </div>

        <div class="advanced-filters-panel" id="advanced-panel">
            <div class="advanced-panel-grid">
                <div class="form-group">
                    <label for="filter-student-id">Client ID</label>
                    <input type="text" id="filter-student-id" placeholder="e.g. 1">
                </div>
                <div class="form-group">
                    <label for="filter-fullname">Full Name</label>
                    <input type="text" id="filter-fullname" placeholder="e.g. Leslie Alexander">
                </div>
                <div class="form-group">
                    <label for="filter-tin-number">TIN Number</label>
                    <input type="text" id="filter-tin-number" placeholder="e.g. TIN123456">
                </div>
                <div class="form-group">
                    <label>Created Date Range</label>
                    <div class="range-inputs">
                        <input type="date" id="filter-entry-start">
                        <span class="range-separator">to</span>
                        <input type="date" id="filter-entry-end">
                    </div>
                </div>
                <div class="form-group">
                    <label>Updated Date Range</label>
                    <div class="range-inputs">
                        <input type="date" id="filter-grad-start">
                        <span class="range-separator">to</span>
                        <input type="date" id="filter-grad-end">
                    </div>
                </div>
                <div class="form-group">
                    <label for="filter-match-mode">Search Mode</label>
                    <select id="filter-match-mode">
                        <option value="all">Contains All Words (AND)</option>
                        <option value="any" selected>Contains Any Word/Num (OR)</option>
                        <option value="exact">Exact Phrase Match</option>
                    </select>
                </div>
            </div>
            <div class="advanced-panel-footer">
                <button class="btn btn-secondary btn-sm" id="clear-filters-btn">Clear All</button>
                <button class="btn btn-primary btn-sm" id="apply-filters-btn">Apply Filters</button>
            </div>
        </div>

        <div class="active-filters-bar" id="active-filters-bar" style="display: none;">
            <span class="active-filters-label">Active Filters:</span>
            <div class="active-filters-list" id="active-filters-list"></div>
            <button class="clear-all-link" id="clear-all-link">Clear All</button>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;"><input type="checkbox" id="select-all-checkbox"></th>
                        <th data-sort="id">Client Id <span class="sort-icon"><svg class="icon icon-xs">
                                    <use href="#icon-chevrons-up-down"></use>
                                </svg></span></th>
                        <th data-sort="full_name">Full Name <span class="sort-icon"><svg class="icon icon-xs">
                                    <use href="#icon-chevrons-up-down"></use>
                                </svg></span></th>
                        <th data-sort="tin_number">TIN Number <span class="sort-icon"><svg class="icon icon-xs">
                                    <use href="#icon-chevrons-up-down"></use>
                                </svg></span></th>
                        <th data-sort="password">Password <span class="sort-icon"><svg class="icon icon-xs">
                                    <use href="#icon-chevrons-up-down"></use>
                                </svg></span></th>
                        <th data-sort="created_at">Created Date <span class="sort-icon"><svg class="icon icon-xs">
                                    <use href="#icon-chevrons-up-down"></use>
                                </svg></span></th>
                        <th data-sort="updated_at">Updated Date <span class="sort-icon"><svg class="icon icon-xs">
                                    <use href="#icon-chevrons-up-down"></use>
                                </svg></span></th>
                        <th data-sort="status">Status <span class="sort-icon"><svg class="icon icon-xs">
                                    <use href="#icon-chevrons-up-down"></use>
                                </svg></span></th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="alumni-table-body">
                    <!-- Dynamic client records will load here -->
                    <tr id="no-results-row" style="display: none;">
                        <td colspan="9" class="no-results-cell">
                            <div class="no-results-content">
                                <svg class="icon icon-xl no-results-icon">
                                    <use href="#icon-search"></use>
                                </svg>
                                <h3>No records found</h3>
                                <p>We couldn't find any records matching your search or filters.</p>
                                <button class="btn btn-secondary btn-sm" id="reset-no-results-btn">Reset All
                                    Filters</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <footer class="pagination-bar">
            <div id="pagination-info">0 - 0 of 0</div>
            <div class="pagination-right">
                <div class="rows-per-page">
                    <span>Rows per page:</span>
                    <select class="select-pagination" id="rows-per-page-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="pages-links" id="pagination-pages">
                    <!-- Dynamic page nodes load here -->
                </div>
            </div>
        </footer>

    </main>

    <!-- Add/Edit Client Modal -->
    <div class="modal-overlay" id="alumni-modal" aria-hidden="true">
        <div class="modal-card">
            <div class="modal-header">
                <h2 id="modal-title">Add New Client</h2>
                <button class="modal-close-btn" id="close-modal-x" aria-label="Close modal">
                    <svg class="icon">
                        <use href="#icon-plus" style="transform: rotate(45deg); transform-origin: center;"></use>
                    </svg>
                </button>
            </div>
            <form id="add-alumni-form">
                <input type="hidden" id="edit-client-id" value="">
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="fullname">Full Name</label>
                            <input type="text" id="fullname" placeholder="Name" required>
                        </div>
                        <div class="form-group">
                            <label for="tin-number">TIN Number</label>
                            <input type="text" id="tin-number" placeholder="TIN" required>
                            <div class="validation-preview" id="tin-validation-preview">------------</div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" placeholder="Password" name="password"
                                autocomplete="new-password">
                            <small id="password-hint" class="form-hint" style="display:none; color: var(--text-muted); font-size: 0.75rem;">(Leave blank to keep current password)</small>

                            <div class="validation-preview" id="password-validation-preview">--------</div>
                        </div>
                        <div class="form-group full-width">
                            <label for="status">Status</label>
                            <select id="status" required>
                                <option value="Saved">Saved</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close-modal-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="save-client-btn">Save Client</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Client Details Modal -->
    <div class="modal-overlay" id="view-modal" aria-hidden="true">
        <div class="view-card">
            <button class="modal-close-btn view-close-x" id="close-view-x" aria-label="Close modal">
                <svg class="icon">
                    <use href="#icon-plus" style="transform: rotate(45deg); transform-origin: center;"></use>
                </svg>
            </button>
            <div class="view-profile-sidebar">
                <div class="view-avatar" id="view-avatar-initials">JS</div>
                <h3 id="view-fullname">Jane Doe</h3>
                <span class="badge" id="view-status-badge"><svg class="icon icon-xs badge-icon">
                        <use href="#icon-check"></use>
                    </svg><span id="view-status-text">Saved</span></span>
            </div>
            <div class="view-details-content">
                <h2 class="view-title">Client Profile</h2>
                <div class="view-grid">
                    <div class="view-group">
                        <span class="view-label">Client ID</span>
                        <span class="view-value" id="view-student-id">-</span>
                    </div>
                    <div class="view-group">
                        <span class="view-label">TIN Number</span>
                        <span class="view-value" id="view-tin-number-text">-</span>
                    </div>
                    <div class="view-group">
                        <span class="view-label">Password</span>
                        <span class="view-value" id="view-password-text">-</span>
                    </div>
                    <div class="view-group">
                        <span class="view-label">Created Date</span>
                        <span class="view-value" id="view-date-entry">-</span>
                    </div>
                    <div class="view-group">
                        <span class="view-label">Last Updated Date</span>
                        <span class="view-value" id="view-date-graduation">-</span>
                    </div>
                    <div class="view-group full-width">
                        <span class="view-label">System Tenure</span>
                        <span class="view-value" id="view-duration">-</span>
                    </div>
                </div>
                <div class="view-footer">
                    <button class="btn btn-secondary" id="close-view-btn">Close Details</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div class="toast-container" id="toast-container"></div>

</body>

</html><?php /**PATH E:\BR_CLLIENT_MANAGEMENT\resources\views/clients/index.blade.php ENDPATH**/ ?>