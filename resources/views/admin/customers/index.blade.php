@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Header with Add Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Customer Management</h2>
            <p class="text-sm text-slate-500">Add, edit, or remove customer accounts.</p>
        </div>
        <div>
            <button onclick="openAddModal()"
                class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl text-sm shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 transition-all duration-300 cursor-pointer">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Add Customer
            </button>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
        <form id="searchForm" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" 
                       id="searchInput" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm placeholder:text-slate-400">
            </div>
            <div class="flex gap-2">
                <button type="submit" 
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl text-sm shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 transition-all duration-300">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Search
                </button>
                <a href="{{ route('admin.customers.index') }}" 
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl text-sm transition-all duration-200">
                    Reset
                </a>
            </div>
        </form>
        <!-- Result info -->
        <div id="resultInfo" class="mt-3 text-sm text-slate-500">
            @if(request('search'))
                Showing results for: <span class="font-medium text-slate-700">"{{ request('search') }}"</span>
                <span class="mx-2">|</span>
                <span class="font-medium text-indigo-600">{{ $customers->total() }}</span> customer(s) found
            @else
                Showing all <span class="font-medium text-slate-700">{{ $customers->total() }}</span> customers
            @endif
        </div>
    </div>

    <!-- Customers List Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="overflow-x-auto">
            @if($customers->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-indigo-50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <p class="font-medium text-slate-600">No customers registered yet.</p>
                    <p class="text-sm text-slate-400 mt-1">Click "Add Customer" to register a new user.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            {{-- <th class="p-4 pl-6">ID</th> --}}
                            <th class="p-4">Name</th>
                            <th class="p-4">Email Address</th>
                            <th class="p-4">Date Registered</th>
                            <th class="p-4 pr-6 text-right font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @foreach($customers as $c)
                            <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                                {{-- <td class="p-4 pl-6 text-slate-400 font-mono text-xs">#{{ $c->id }}</td> --}}
                                <td class="p-4 font-semibold text-slate-800">
                                    <div class="flex items-center gap-2">
                                {{ $c->name }}
                                    </div>
                                </td>
                                <td class="p-4 text-indigo-600">{{ $c->email }}</td>
                                <td class="p-4 text-slate-500 text-sm">
                                    {{ $c->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="p-4 pr-6 text-right space-x-2">
                                    <!-- Edit Trigger -->
                                    <button onclick="openEditModal({{ $c->id }}, '{{ addslashes($c->name) }}', '{{ addslashes($c->email) }}')"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 hover:bg-indigo-500 text-indigo-700 hover:text-white border border-indigo-200 hover:border-indigo-500 transition-all duration-200 cursor-pointer">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>

                                    <!-- Delete Trigger -->
                                    <button onclick="openDeleteCustomerModal('{{ route('admin.customers.destroy', $c->id) }}', '{{ addslashes($c->name) }}', '{{ addslashes($c->email) }}')"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 hover:bg-rose-500 text-rose-700 hover:text-white border border-rose-200 hover:border-rose-500 transition-all duration-200 cursor-pointer">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination -->
                <div class="p-4 border-t border-slate-100">
                    {{ $customers->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Overlay for Add Customer -->
<div id="addCustomerModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-slate-100 m-4 relative overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 rounded-xl bg-indigo-50">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Add Customer</h3>
                <p class="text-xs text-slate-500">Register a new customer account.</p>
            </div>
        </div>

        <form action="{{ route('admin.customers.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            
            <div>
                <label for="add_name" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Name</label>
                <input type="text" name="name" id="add_name" required
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm placeholder:text-slate-400"
                    placeholder="Enter full name">
            </div>

            <div>
                <label for="add_email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" id="add_email" required
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm placeholder:text-slate-400"
                    placeholder="customer@domain.com">
            </div>

            <div>
                <label for="add_password" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" id="add_password" required minlength="6"
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm placeholder:text-slate-400"
                    placeholder="Min. 6 characters">
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeAddModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl text-sm transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl text-sm shadow-lg shadow-indigo-600/20 transition-all cursor-pointer">
                    Create Account
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Overlay for Edit Customer -->
<div id="editCustomerModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-slate-100 m-4 relative overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-violet-500 to-indigo-500"></div>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 rounded-xl bg-violet-50">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Edit Customer</h3>
                <p class="text-xs text-slate-500">Update customer details. Leave password empty to keep current password.</p>
            </div>
        </div>

        <form id="editForm" action="" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label for="edit_name" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Name</label>
                <input type="text" name="name" id="edit_name" required
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm placeholder:text-slate-400"
                    placeholder="Enter full name">
            </div>

            <div>
                <label for="edit_email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" id="edit_email" required
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm placeholder:text-slate-400"
                    placeholder="customer@domain.com">
            </div>

            <div>
                <label for="edit_password" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">New Password (Optional)</label>
                <input type="password" name="password" id="edit_password" minlength="6"
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm placeholder:text-slate-400"
                    placeholder="Leave blank to keep unchanged">
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl text-sm transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl text-sm shadow-lg shadow-indigo-600/20 transition-all cursor-pointer">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Overlay for Delete Customer -->
<div id="deleteCustomerModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-slate-100 m-4 relative overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 to-red-500"></div>
        
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 mb-4">
                <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Delete Customer</h3>
            <p class="text-sm text-slate-500 mt-2" id="deleteCustomerMessage">Are you sure you want to delete this customer?</p>
            <p class="text-xs text-rose-600/80 mt-1 font-medium">This action cannot be undone. All video access records will also be removed.</p>
            
            <form id="deleteCustomerForm" action="" method="POST" class="mt-6 flex items-center justify-center space-x-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteCustomerModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl text-sm transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-rose-600 hover:bg-rose-500 text-white font-medium rounded-xl text-sm shadow-lg shadow-rose-600/20 transition-all cursor-pointer">
                    Yes, Delete Customer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add Modal functions
    function openAddModal() {
        const modal = document.getElementById('addCustomerModal');
        const content = modal.querySelector('.bg-white');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeAddModal() {
        const modal = document.getElementById('addCustomerModal');
        const content = modal.querySelector('.bg-white');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Edit Modal functions
    function openEditModal(id, name, email) {
        const modal = document.getElementById('editCustomerModal');
        const content = modal.querySelector('.bg-white');
        const form = document.getElementById('editForm');
        
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_password').value = '';
        
        form.action = `/admin/customers/${id}`;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeEditModal() {
        const modal = document.getElementById('editCustomerModal');
        const content = modal.querySelector('.bg-white');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Delete Modal functions
    function openDeleteCustomerModal(actionUrl, customerName, customerEmail) {
        const modal = document.getElementById('deleteCustomerModal');
        const content = modal.querySelector('.bg-white');
        const form = document.getElementById('deleteCustomerForm');
        const message = document.getElementById('deleteCustomerMessage');
        
        form.action = actionUrl;
        
        if (message) {
            message.textContent = `Are you sure you want to delete "${customerName}" (${customerEmail})?`;
        }
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeDeleteCustomerModal() {
        const modal = document.getElementById('deleteCustomerModal');
        const content = modal.querySelector('.bg-white');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Search with Enter key
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('searchForm').submit();
        }
    });

    // Auto search with debounce (300ms delay)
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('searchForm').submit();
        }, 500);
    });

    // Highlight search term in results
    document.addEventListener('DOMContentLoaded', function() {
        const searchTerm = "{{ request('search') }}";
        if (searchTerm) {
            const rows = document.querySelectorAll('#customersTableBody tr');
            rows.forEach(row => {
                const text = row.textContent;
                if (text.toLowerCase().includes(searchTerm.toLowerCase())) {
                    // Optional: Add highlight class
                    row.style.backgroundColor = 'rgba(99, 102, 241, 0.05)';
                }
            });
        }
    });
</script>
@endsection