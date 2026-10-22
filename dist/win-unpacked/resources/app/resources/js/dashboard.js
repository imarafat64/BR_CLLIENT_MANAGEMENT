import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // ---- Global State ----
    let currentPage = 1;
    let perPage = 10;
    let sortBy = 'id';
    let sortOrder = 'asc';
    let searchQuery = '';
    let matchMode = 'any';
    let statusFilter = '';

    // Advanced filters state
    let filterClientId = '';
    let filterFullname = '';
    let filterTin = '';
    let filterEntryStart = '';
    let filterEntryEnd = '';
    let filterGradStart = '';
    let filterGradEnd = '';

    // ---- DOM Elements ----
    const themeToggle = document.querySelector('.theme-toggle');
    const themeIconUse = themeToggle.querySelector('use');
    const modal = document.getElementById('alumni-modal');
    const openModalBtn = document.getElementById('add-alumni-btn');
    const closeModalX = document.getElementById('close-modal-x');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const alumniForm = document.getElementById('add-alumni-form');
    const tableBody = document.getElementById('alumni-table-body');
    const toastContainer = document.getElementById('toast-container');

    // Modal fields
    const modalTitle = document.getElementById('modal-title');
    const editClientId = document.getElementById('edit-client-id');
    const fieldFullname = document.getElementById('fullname');
    const fieldTinNumber = document.getElementById('tin-number');
    const fieldPassword = document.getElementById('password');
    const fieldPasswordHint = document.getElementById('password-hint');
    const fieldStatus = document.getElementById('status');
    const saveClientBtn = document.getElementById('save-client-btn');

    // View Modal
    const viewModal = document.getElementById('view-modal');
    const closeViewX = document.getElementById('close-view-x');
    const closeViewBtn = document.getElementById('close-view-btn');
    const viewAvatar = document.getElementById('view-avatar-initials');
    const viewFullname = document.getElementById('view-fullname');
    const viewStatusText = document.getElementById('view-status-text');
    const viewStatusBadge = document.getElementById('view-status-badge');
    const viewStudentId = document.getElementById('view-student-id');
    const viewTinNumberText = document.getElementById('view-tin-number-text');
    const viewDateEntry = document.getElementById('view-date-entry');
    const viewDateGraduation = document.getElementById('view-date-graduation');
    const viewDuration = document.getElementById('view-duration');

    // Search and filters
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const advancedToggleBtn = document.getElementById('advanced-toggle-btn');
    const advancedPanel = document.getElementById('advanced-panel');
    const applyFiltersBtn = document.getElementById('apply-filters-btn');
    const clearFiltersBtn = document.getElementById('clear-filters-btn');
    const filterStatusSelect = document.getElementById('filter-status');
    const activeFiltersBar = document.getElementById('active-filters-bar');
    const activeFiltersList = document.getElementById('active-filters-list');
    const clearAllLink = document.getElementById('clear-all-link');
    const noResultsRow = document.getElementById('no-results-row');
    const resetNoResultsBtn = document.getElementById('reset-no-results-btn');

    // Advanced search inputs
    const fClientId = document.getElementById('filter-student-id');
    const fFullname = document.getElementById('filter-fullname');
    const fTin = document.getElementById('filter-tin-number');
    const fEntryStart = document.getElementById('filter-entry-start');
    const fEntryEnd = document.getElementById('filter-entry-end');
    const fGradStart = document.getElementById('filter-grad-start');
    const fGradEnd = document.getElementById('filter-grad-end');
    const fMatchMode = document.getElementById('filter-match-mode');

    // Pagination
    const paginationInfo = document.getElementById('pagination-info');
    const rowsPerPageSelect = document.getElementById('rows-per-page-select');
    const paginationPages = document.getElementById('pagination-pages');

    // Headers sorting
    const tableHeaders = document.querySelectorAll('th[data-sort]');

    // Statistics widgets
    const statTotalClients = document.getElementById('stat-total-clients');
    const statSavedClients = document.getElementById('stat-saved-clients');
    const statPendingClients = document.getElementById('stat-pending-clients');

    // ---- Theme Management (Dark Mode) ----
    function applyTheme(dark) {
        if (dark) {
            document.body.classList.add('dark');
            themeIconUse.setAttribute('href', '#icon-sun');
        } else {
            document.body.classList.remove('dark');
            themeIconUse.setAttribute('href', '#icon-moon');
        }
    }

    applyTheme(localStorage.getItem('theme') === 'dark');

    themeToggle.addEventListener('click', () => {
        const isDark = document.body.classList.contains('dark');
        const nextDark = !isDark;
        applyTheme(nextDark);
        localStorage.setItem('theme', nextDark ? 'dark' : 'light');
        showToast(nextDark ? 'Switched to Dark Mode 🌙' : 'Switched to Light Mode ☀️');
    });

    // ---- Helper functions ----
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = 'toast';
        if (type === 'error') {
            toast.style.borderLeftColor = 'var(--danger-red)';
        }
        toast.textContent = message;
        toastContainer.appendChild(toast);

        // Animate in
        setTimeout(() => toast.classList.add('show'), 10);

        // Remove Toast
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return '-';
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
    }

    function calculateTenure(createdStr, updatedStr) {
        if (!createdStr) return '-';
        const created = new Date(createdStr);
        const updated = updatedStr ? new Date(updatedStr) : new Date();
        const diffMs = updated - created;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMins / 60);
        const diffDays = Math.floor(diffHours / 24);
        const diffMonths = Math.floor(diffDays / 30);
        const diffYears = Math.floor(diffMonths / 12);

        if (diffYears > 0) {
            const remainingMonths = diffMonths % 12;
            return `${diffYears} yr${diffYears > 1 ? 's' : ''} ${remainingMonths} mo${remainingMonths !== 1 ? 's' : ''}`;
        }
        if (diffMonths > 0) {
            return `${diffMonths} month${diffMonths > 1 ? 's' : ''}`;
        }
        if (diffDays > 0) {
            return `${diffDays} day${diffDays > 1 ? 's' : ''}`;
        }
        if (diffHours > 0) {
            return `${diffHours} hour${diffHours > 1 ? 's' : ''}`;
        }
        return `${diffMins || 0} min${diffMins !== 1 ? 's' : ''}`;
    }

    function computeInitials(name) {
        if (!name) return '??';
        const parts = name.trim().split(/\s+/);
        const initials = parts.map(p => p[0]).join('').slice(0, 2).toUpperCase();
        return initials || '??';
    }

    // ---- API Actions ----
    function fetchClients() {
        const params = {
            page: currentPage,
            per_page: perPage,
            sort_by: sortBy,
            sort_order: sortOrder,
            search: searchQuery,
            match_mode: matchMode,
            status: statusFilter,
            filter_client_id: filterClientId,
            filter_fullname: filterFullname,
            filter_tin: filterTin,
            filter_created_start: filterEntryStart,
            filter_created_end: filterEntryEnd,
            filter_updated_start: filterGradStart,
            filter_updated_end: filterGradEnd
        };

        axios.get('/clients/list', { params })
            .then(response => {
                const res = response.data;
                renderTable(res.data);
                updatePaginationControls(res);
                updateStatsWidgets(res.stats);
                renderActiveFilterTags();
            })
            .catch(error => {
                console.error(error);
                showToast('Failed to fetch clients from server', 'error');
            });
    }

    function renderTable(clients) {
        // Clear previous rows except no-results-row
        const rows = tableBody.querySelectorAll('tr:not(#no-results-row)');
        rows.forEach(r => r.remove());

        if (clients.length === 0) {
            noResultsRow.style.display = '';
            return;
        }

        noResultsRow.style.display = 'none';

        clients.forEach(client => {
            const tr = document.createElement('tr');
            tr.setAttribute('data-id', client.id);

            tr.innerHTML = `
                <td><input type="checkbox" class="client-checkbox"></td>
                <td>${client.id}</td>
                <td class="row-name">${client.full_name}</td>
                <td>${client.tin_number}</td>
                <td>${client.password || ''}</td>
                <td>${formatDate(client.created_at)}</td>
                <td>${formatDate(client.updated_at)}</td>
                <td>
                    <span class="badge badge-${client.status.toLowerCase()}">
                        <svg class="icon icon-xs badge-icon"><use href="#icon-check"></use></svg>${client.status}
                    </span>
                </td>
                <td class="actions-cell">
                    <button class="action-btn act-view" title="View"><svg class="icon"><use href="#icon-eye"></use></svg></button>
                    <button class="action-btn act-edit" title="Edit"><svg class="icon"><use href="#icon-pencil"></use></svg></button>
                    <button class="action-btn act-delete" title="Delete"><svg class="icon"><use href="#icon-trash"></use></svg></button>
                </td>
            `;

            tableBody.appendChild(tr);
        });
    }

    function updateStatsWidgets(stats) {
        if (!stats) return;
        statTotalClients.textContent = stats.total_clients;
        statSavedClients.textContent = stats.saved_clients;
        statPendingClients.textContent = stats.pending_clients;
    }

    function updatePaginationControls(res) {
        const from = res.from || 0;
        const to = res.to || 0;
        const total = res.total || 0;
        paginationInfo.textContent = `${from} - ${to} of ${total}`;

        paginationPages.innerHTML = '';

        // Previous button
        const prevNode = document.createElement('a');
        prevNode.className = `page-node ${res.current_page === 1 ? 'disabled' : ''}`;
        prevNode.setAttribute('aria-label', 'Previous Page');
        prevNode.innerHTML = `<svg class="icon"><use href="#icon-chevron-left"></use></svg>`;
        if (res.current_page > 1) {
            prevNode.addEventListener('click', () => {
                currentPage--;
                fetchClients();
            });
        }
        paginationPages.appendChild(prevNode);

        // Generate pages array with ellipsis
        const current = res.current_page;
        const last = res.last_page;
        const range = [];
        const delta = 2;

        for (let i = 1; i <= last; i++) {
            if (i === 1 || i === last || (i >= current - delta && i <= current + delta)) {
                range.push(i);
            }
        }

        let l;
        for (const i of range) {
            if (l) {
                if (i - l === 2) {
                    paginationPages.appendChild(createPageNode(l + 1, current));
                } else if (i - l > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'pagination-ellipsis';
                    ellipsis.textContent = '...';
                    paginationPages.appendChild(ellipsis);
                }
            }
            paginationPages.appendChild(createPageNode(i, current));
            l = i;
        }

        // Next button
        const nextNode = document.createElement('a');
        nextNode.className = `page-node ${res.current_page === last ? 'disabled' : ''}`;
        nextNode.setAttribute('aria-label', 'Next Page');
        nextNode.innerHTML = `<svg class="icon"><use href="#icon-chevron-right"></use></svg>`;
        if (res.current_page < last) {
            nextNode.addEventListener('click', () => {
                currentPage++;
                fetchClients();
            });
        }
        paginationPages.appendChild(nextNode);
    }

    function createPageNode(pageNo, activePage) {
        const node = document.createElement('a');
        node.className = `page-node ${pageNo === activePage ? 'active' : ''}`;
        node.textContent = pageNo;
        node.addEventListener('click', () => {
            if (pageNo !== activePage) {
                currentPage = pageNo;
                fetchClients();
            }
        });
        return node;
    }

    // ---- Live Input Validations (TIN 12-digit & Password 8-character) ----
    const tinPreview = document.getElementById('tin-validation-preview');
    const pwdPreview = document.getElementById('password-validation-preview');

    function updateTinValidation() {
        const val = fieldTinNumber.value;
        const len = val.length;
        if (len < 12) {
            fieldTinNumber.classList.remove('dancing-alert');
            tinPreview.classList.remove('dancing-alert', 'valid');
            tinPreview.textContent = val + "-".repeat(12 - len);
        } else if (len === 12) {
            fieldTinNumber.classList.remove('dancing-alert');
            tinPreview.classList.remove('dancing-alert');
            tinPreview.classList.add('valid');
            tinPreview.textContent = val + " ✔";
        } else {
            fieldTinNumber.classList.add('dancing-alert');
            tinPreview.classList.add('dancing-alert');
            tinPreview.classList.remove('valid');
            tinPreview.textContent = val + " (Exactly 12 digits required!)";
        }
    }

    // ---- Live Input Validation (Password 8-character or more) ----
    function updatePasswordValidation() {
        const val = fieldPassword.value;
        const len = val.length;
        const isEdit = editClientId.value !== '';

        // Case 1: Optional edit mode where field is left blank
        if (isEdit && len === 0) {
            fieldPassword.classList.remove('dancing-alert');
            pwdPreview.classList.remove('dancing-alert', 'valid');
            pwdPreview.textContent = "(Leave blank to keep current password)";
            return;
        }

        if (len < 8) {
            // Still typing under 8 characters - Clear validation, show mask
            fieldPassword.classList.remove('dancing-alert');
            pwdPreview.classList.remove('dancing-alert', 'valid');
            pwdPreview.textContent = "•".repeat(len) + "-".repeat(8 - len);
        } else {
            // 8 or more characters entered - Explicitly force remove any alert classes
            fieldPassword.classList.remove('dancing-alert');
            pwdPreview.classList.remove('dancing-alert');

            // Mark as completely valid
            pwdPreview.classList.add('valid');
            pwdPreview.textContent = "•".repeat(len) + " ✔";
        }
    }

    // Bind input event listeners for live validation
    fieldTinNumber.addEventListener('input', updateTinValidation);
    fieldPassword.addEventListener('input', updatePasswordValidation);

    // ---- Modals Logic ----
    function openModal(isEdit = false) {
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        if (isEdit) {
            modalTitle.textContent = 'Edit Client';
            saveClientBtn.textContent = 'Update Client';
            fieldPassword.removeAttribute('required');
            fieldPasswordHint.style.display = 'block';
        } else {
            modalTitle.textContent = 'Add New Client';
            saveClientBtn.textContent = 'Save Client';
            editClientId.value = '';
            alumniForm.reset();
            fieldPassword.setAttribute('required', 'required');
            fieldPasswordHint.style.display = 'none';
        }

        // Refresh validation previews on open
        updateTinValidation();
        updatePasswordValidation();
    }

    function closeModal() {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        alumniForm.reset();
    }

    function closeViewModal() {
        viewModal.classList.remove('show');
        viewModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    // Modal triggers
    openModalBtn.addEventListener('click', () => openModal(false));
    closeModalX.addEventListener('click', closeModal);
    closeModalBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

    closeViewX.addEventListener('click', closeViewModal);
    closeViewBtn.addEventListener('click', closeViewModal);
    viewModal.addEventListener('click', (e) => { if (e.target === viewModal) closeViewModal(); });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (modal.classList.contains('show')) closeModal();
            if (viewModal.classList.contains('show')) closeViewModal();
        }
    });

    // Form Submit (Add / Edit Client)
    alumniForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const fullname = fieldFullname.value.trim();
        const tin = fieldTinNumber.value.trim();
        const password = fieldPassword.value;
        const status = fieldStatus.value;
        const clientID = editClientId.value;

        // Custom validation triggers
        if (!/^[0-9]{12}$/.test(tin)) {
            showToast('TIN Number must be exactly 12 digits', 'error');
            return;
        }

        if (!clientID && (!password || password.length < 8)) {
            showToast('Password is required and must be at least 8 characters', 'error');
            return;
        }

        if (password && password.length < 8) {
            showToast('Password must be at least 8 characters', 'error');
            return;
        }

        const data = {
            full_name: fullname,
            tin_number: tin,
            status: status
        };

        if (password) {
            data.password = password;
        }

        const request = clientID
            ? axios.put(`/clients/${clientID}`, data)
            : axios.post('/clients', data);

        request
            .then(response => {
                showToast(response.data.message || 'Action executed successfully!');
                closeModal();
                fetchClients();
            })
            .catch(error => {
                const errorMsg = error.response?.data?.message || 'Failed to submit form';
                const validationErrors = error.response?.data?.errors;
                if (validationErrors) {
                    const firstError = Object.values(validationErrors)[0][0];
                    showToast(firstError, 'error');
                } else {
                    showToast(errorMsg, 'error');
                }
            });
    });

    // Event delegation for table action buttons
    tableBody.addEventListener('click', (e) => {
        const btn = e.target.closest('.action-btn');
        if (!btn) return;

        const row = btn.closest('tr');
        const id = row.getAttribute('data-id');
        const fullname = row.querySelector('.row-name').textContent;

        if (btn.classList.contains('act-delete')) {
            if (confirm(`Are you sure you want to delete the record for ${fullname}?`)) {
                axios.delete(`/clients/${id}`)
                    .then(response => {
                        showToast(response.data.message || 'Client record deleted!');
                        fetchClients();
                    })
                    .catch(error => {
                        showToast(error.response?.data?.message || 'Failed to delete record', 'error');
                    });
            }
        } else if (btn.classList.contains('act-view')) {
            axios.get(`/clients/${id}`)
                .then(response => {
                    const client = response.data;
                    const initials = computeInitials(client.full_name);
                    const tenure = calculateTenure(client.created_at, client.updated_at);

                    viewAvatar.textContent = initials;
                    viewFullname.textContent = client.full_name;
                    viewStatusText.textContent = client.status;
                    viewStudentId.textContent = client.id;
                    viewTinNumberText.textContent = client.tin_number;
                    document.getElementById('view-password-text').textContent = client.password || '-';
                    viewDateEntry.textContent = formatDate(client.created_at);
                    viewDateGraduation.textContent = formatDate(client.updated_at);
                    viewDuration.textContent = tenure;

                    // Sync status badge appearance
                    viewStatusBadge.className = `badge badge-${client.status.toLowerCase()}`;

                    // Show view modal
                    viewModal.classList.add('show');
                    viewModal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                })
                .catch(error => {
                    showToast(error.response?.data?.message || 'Failed to retrieve details', 'error');
                });
        } else if (btn.classList.contains('act-edit')) {
            axios.get(`/clients/${id}`)
                .then(response => {
                    const client = response.data;
                    editClientId.value = client.id;
                    fieldFullname.value = client.full_name;
                    fieldTinNumber.value = client.tin_number;
                    fieldPassword.value = '';
                    fieldStatus.value = client.status;

                    openModal(true);
                })
                .catch(error => {
                    showToast(error.response?.data?.message || 'Failed to retrieve client for edit', 'error');
                });
        }
    });

    // ---- Sorting ----
    tableHeaders.forEach(th => {
        th.addEventListener('click', () => {
            const field = th.getAttribute('data-sort');
            if (!field) return;

            if (sortBy === field) {
                sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                sortBy = field;
                sortOrder = 'asc';
            }

            // Sync visual icons
            tableHeaders.forEach(header => {
                const iconUse = header.querySelector('use');
                if (iconUse) {
                    iconUse.setAttribute('href', '#icon-chevrons-up-down');
                }
            });

            const currentIconUse = th.querySelector('use');
            if (currentIconUse) {
                currentIconUse.setAttribute('href', sortOrder === 'asc' ? '#icon-chevrons-up-down' : '#icon-chevrons-up-down'); // using default styling arrow
            }

            fetchClients();
        });
    });

    // ---- Rows Per Page pagination ----
    rowsPerPageSelect.addEventListener('change', () => {
        perPage = parseInt(rowsPerPageSelect.value);
        currentPage = 1;
        fetchClients();
    });

    // ---- General Search & Advanced Search Logic ----
    advancedToggleBtn.addEventListener('click', () => {
        const isOpen = advancedPanel.classList.toggle('show');
        advancedToggleBtn.classList.toggle('btn-primary', isOpen);
        advancedToggleBtn.classList.toggle('btn-secondary', !isOpen);
    });

    function applyAllFilters() {
        searchQuery = searchInput.value.trim();
        statusFilter = filterStatusSelect.value;
        matchMode = fMatchMode.value;

        // Advanced filter values
        filterClientId = fClientId.value.trim();
        filterFullname = fFullname.value.trim();
        filterTin = fTin.value.trim();
        filterEntryStart = fEntryStart.value;
        filterEntryEnd = fEntryEnd.value;
        filterGradStart = fGradStart.value;
        filterGradEnd = fGradEnd.value;

        currentPage = 1;
        fetchClients();
    }

    function renderActiveFilterTags() {
        activeFiltersList.innerHTML = '';
        const tags = [];

        if (searchQuery) {
            tags.push({
                label: `Search: "${searchQuery}"`,
                clearFn: () => { searchInput.value = ''; applyAllFilters(); }
            });
        }
        if (statusFilter) {
            tags.push({
                label: `Status: ${statusFilter}`,
                clearFn: () => { filterStatusSelect.value = ''; applyAllFilters(); }
            });
        }
        if (filterClientId) {
            tags.push({
                label: `ID: ${filterClientId}`,
                clearFn: () => { fClientId.value = ''; applyAllFilters(); }
            });
        }
        if (filterFullname) {
            tags.push({
                label: `Name: ${filterFullname}`,
                clearFn: () => { fFullname.value = ''; applyAllFilters(); }
            });
        }
        if (filterTin) {
            tags.push({
                label: `TIN: ${filterTin}`,
                clearFn: () => { fTin.value = ''; applyAllFilters(); }
            });
        }
        if (filterEntryStart || filterEntryEnd) {
            tags.push({
                label: `Created: ${filterEntryStart || 'Start'} to ${filterEntryEnd || 'End'}`,
                clearFn: () => { fEntryStart.value = ''; fEntryEnd.value = ''; applyAllFilters(); }
            });
        }
        if (filterGradStart || filterGradEnd) {
            tags.push({
                label: `Updated: ${filterGradStart || 'Start'} to ${filterGradEnd || 'End'}`,
                clearFn: () => { fGradStart.value = ''; fGradEnd.value = ''; applyAllFilters(); }
            });
        }

        if (tags.length > 0) {
            tags.forEach(tag => {
                const badge = document.createElement('span');
                badge.className = 'filter-badge';
                // Inline styling matching the CSS requirement for filter badge remove triggers
                badge.style.display = 'inline-flex';
                badge.style.alignItems = 'center';
                badge.style.gap = '4px';
                badge.style.backgroundColor = 'var(--bg-control)';
                badge.style.border = '1px solid var(--border-color)';
                badge.style.borderRadius = '4px';
                badge.style.padding = '2px 8px';
                badge.style.fontSize = '0.75rem';
                badge.style.color = 'var(--text-primary)';

                badge.innerHTML = `${tag.label} <button class="filter-badge-remove" title="Remove filter" style="background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: 0.75rem; display: inline-flex; align-items: center; padding: 0 2px;">✕</button>`;
                badge.querySelector('.filter-badge-remove').addEventListener('click', tag.clearFn);
                activeFiltersList.appendChild(badge);
            });
            activeFiltersBar.style.display = 'flex';
        } else {
            activeFiltersBar.style.display = 'none';
        }
    }

    function clearAllFilters() {
        searchInput.value = '';
        filterStatusSelect.value = '';
        fClientId.value = '';
        fFullname.value = '';
        fTin.value = '';
        fEntryStart.value = '';
        fEntryEnd.value = '';
        fGradStart.value = '';
        fGradEnd.value = '';

        applyAllFilters();
    }

    searchBtn.addEventListener('click', applyAllFilters);
    searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') applyAllFilters(); });

    let debounceTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(applyAllFilters, 300);
    });

    filterStatusSelect.addEventListener('change', applyAllFilters);
    applyFiltersBtn.addEventListener('click', applyAllFilters);
    clearFiltersBtn.addEventListener('click', clearAllFilters);
    clearAllLink.addEventListener('click', clearAllFilters);
    resetNoResultsBtn.addEventListener('click', clearAllFilters);

    // Multi-select Checkbox logic
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    selectAllCheckbox.addEventListener('change', () => {
        const checkBoxes = tableBody.querySelectorAll('.client-checkbox');
        checkBoxes.forEach(cb => {
            cb.checked = selectAllCheckbox.checked;
        });
    });

    // ---- Initial Load ----
    fetchClients();
});
