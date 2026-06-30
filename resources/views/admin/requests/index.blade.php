@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Header -->
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Access Request History</h2>
        <p class="text-sm text-slate-500">Review all historical permissions, approvals, and pending requests.</p>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
        <form id="searchForm" class="flex flex-col md:flex-row gap-3">
            <!-- Search Input -->
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
            
            <!-- Status Filter -->
            <div class="flex gap-2">
                <select name="status" id="statusFilter" 
                    class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            
            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit" 
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl text-sm shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 transition-all duration-300">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Search
                </button>
                <a href="{{ route('admin.requests.index') }}" 
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl text-sm transition-all duration-200">
                    Reset
                </a>
            </div>
        </form>
        
        <!-- Result info -->
        <div id="resultInfo" class="mt-3 text-sm text-slate-500 flex flex-wrap items-center gap-3">
            @if(request('search') || request('status'))
                <span>
                    Showing results for: 
                    @if(request('search'))
                        <span class="font-medium text-slate-700">"{{ request('search') }}"</span>
                    @endif
                    @if(request('status'))
                        <span class="font-medium text-slate-700">(Status: {{ ucfirst(request('status')) }})</span>
                    @endif
                    <span class="mx-2">|</span>
                    <span class="font-medium text-indigo-600">{{ $requests->total() }}</span> request(s) found
                </span>
            @else
                Showing all <span class="font-medium text-slate-700">{{ $requests->total() }}</span> request(s)
            @endif
        </div>
    </div>

    <!-- Requests History Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                All Requests
            </h3>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                <span class="inline-block w-1.5 h-1.5 bg-indigo-500 rounded-full mr-1.5"></span>
                Log History
            </span>
        </div>
        
        <div class="overflow-x-auto">
            @if($requests->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <p class="font-medium text-slate-600">No requests log found.</p>
                    <p class="text-sm text-slate-400 mt-1">All access requests will appear here.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <th class="p-4 pl-6">Customer</th>
                            <th class="p-4">Video</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Valid Until</th>
                            <th class="p-4">Requested At</th>
                            <th class="p-4 pr-6 text-right font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @foreach($requests as $r)
                            <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                                <td class="p-4 pl-6">
                                    <div class="flex items-center gap-2">
                                        <div>
                                            <div class="font-medium text-slate-800">{{ $r->user->name }}</div>
                                            <div class="text-xs text-slate-400 mt-0.5">{{ $r->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 font-medium text-slate-700">
                                    {{ $r->video->title }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if($r->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="inline-block w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5 animate-pulse"></span>
                                            Pending
                                        </span>
                                    @elseif($r->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <svg class="w-3 h-3 mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Approved
                                        </span>
                                    @elseif($r->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            <svg class="w-3 h-3 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-500 border border-slate-200">
                                            <svg class="w-3 h-3 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Expired
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($r->status === 'approved' && $r->valid_until)
                                        <div class="font-medium text-emerald-600 text-sm">{{ $r->valid_until->format('d M Y H:i') }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">Expires {{ $r->valid_until->diffForHumans() }}</div>
                                    @elseif($r->status === 'expired' && $r->valid_until)
                                        <div class="text-slate-400 line-through text-sm">{{ $r->valid_until->format('d M Y H:i') }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">Expired</div>
                                    @else
                                        <span class="text-slate-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="p-4 text-slate-500 text-sm">
                                    {{ $r->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    @if($r->status === 'pending')
                                        <div class="inline-flex space-x-2">
                                            <!-- Approve Button -->
                                            <button onclick="openApproveModal({{ $r->id }}, '{{ addslashes($r->user->name) }}', '{{ addslashes($r->video->title) }}')"
                                                class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 hover:bg-emerald-500 text-emerald-700 hover:text-white border border-emerald-200 hover:border-emerald-500 transition-all duration-200 cursor-pointer">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Approve
                                            </button>
                                            
                                            <!-- Reject Button -->
                                            <button onclick="openRejectModal('{{ route('admin.requests.reject', $r->id) }}', '{{ addslashes($r->user->name) }}', '{{ addslashes($r->video->title) }}')"
                                                class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 hover:bg-rose-500 text-rose-700 hover:text-white border border-rose-200 hover:border-rose-500 transition-all duration-200 cursor-pointer">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Reject
                                            </button>
                                        </div>
                                    @elseif($r->status === 'approved' && $r->valid_until && $r->valid_until->isFuture())
                                        <!-- Remove Access Button -->
                                        <button onclick="openRevokeModal('{{ route('admin.requests.revoke', $r->id) }}', '{{ addslashes($r->user->name) }}', '{{ addslashes($r->video->title) }}')"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-50 hover:bg-amber-500 text-amber-700 hover:text-white border border-amber-200 hover:border-amber-500 transition-all duration-200 cursor-pointer">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Remove Access
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination -->
                <div class="p-4 border-t border-slate-100">
                    {{ $requests->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Overlay for Approval Duration -->
<div id="approveModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-slate-100 m-4 relative overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-indigo-500"></div>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 rounded-xl bg-emerald-50">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Approve Access Request</h3>
                <p class="text-xs text-slate-500">Set the viewing duration limit for this customer access.</p>
            </div>
        </div>

        <!-- Target Info -->
        <div class="mt-4 p-3.5 bg-slate-50 border border-slate-100 rounded-xl space-y-1">
            <p class="text-xs text-slate-500">Customer: <span id="modalCustomerName" class="font-semibold text-slate-800"></span></p>
            <p class="text-xs text-slate-500">Video: <span id="modalVideoTitle" class="font-semibold text-slate-800"></span></p>
        </div>

        <form id="approveForm" action="" method="POST" class="mt-6 space-y-4">
            @csrf
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="duration" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Duration</label>
                    <input type="number" name="duration" id="duration" min="1" value="60" required
                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm">
                </div>
                <div>
                    <label for="duration_unit" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Unit</label>
                    <select name="duration_unit" id="duration_unit" required
                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm">
                        <option value="minutes" selected>Minutes</option>
                        <option value="hours">Hours</option>
                        <option value="days">Days</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeApproveModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl text-sm transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-xl text-sm shadow-lg shadow-emerald-600/20 transition-all cursor-pointer">
                    Grant Access
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Overlay untuk Reject Request -->
<div id="rejectModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-slate-100 m-4 relative overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 to-red-500"></div>
        
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 mb-4">
                <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Reject Access Request</h3>
            <p class="text-sm text-slate-500 mt-2" id="rejectMessage">Are you sure you want to reject this request?</p>
            <p class="text-xs text-rose-600/80 mt-1">The customer will be notified that their request was rejected.</p>
            
            <form id="rejectForm" action="" method="POST" class="mt-6 flex items-center justify-center space-x-3">
                @csrf
                <button type="button" onclick="closeRejectModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl text-sm transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-rose-600 hover:bg-rose-500 text-white font-medium rounded-xl text-sm shadow-lg shadow-rose-600/20 transition-all cursor-pointer">
                    Yes, Reject
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Overlay untuk Remove Access -->
<div id="revokeModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-slate-100 m-4 relative overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-orange-500"></div>
        
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 mb-4">
                <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Remove Access</h3>
            <p class="text-sm text-slate-500 mt-2" id="revokeMessage">Are you sure you want to revoke this customer's access immediately?</p>
            <p class="text-xs text-amber-600/80 mt-1">The customer will lose access to this video right away.</p>
            
            <form id="revokeForm" action="" method="POST" class="mt-6 flex items-center justify-center space-x-3">
                @csrf
                <button type="button" onclick="closeRevokeModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl text-sm transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-amber-600 hover:bg-amber-500 text-white font-medium rounded-xl text-sm shadow-lg shadow-amber-600/20 transition-all cursor-pointer">
                    Yes, Remove
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openApproveModal(id, customerName, videoTitle) {
        const modal = document.getElementById('approveModal');
        const content = modal.querySelector('.bg-white');
        const form = document.getElementById('approveForm');
        
        document.getElementById('modalCustomerName').textContent = customerName;
        document.getElementById('modalVideoTitle').textContent = videoTitle;
        
        form.action = `/admin/access-requests/${id}/approve`;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeApproveModal() {
        const modal = document.getElementById('approveModal');
        const content = modal.querySelector('.bg-white');
        
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // REJECT MODAL
    function openRejectModal(actionUrl, customerName, videoTitle) {
        const modal = document.getElementById('rejectModal');
        const content = modal.querySelector('.bg-white');
        const form = document.getElementById('rejectForm');
        const message = document.getElementById('rejectMessage');

        form.action = actionUrl;
        if (message) {
            message.textContent = `Are you sure you want to reject "${customerName}"'s request for "${videoTitle}"?`;
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        const content = modal.querySelector('.bg-white');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // REMOVE MODAL
    function openRevokeModal(actionUrl, customerName, videoTitle) {
        const modal = document.getElementById('revokeModal');
        const content = modal.querySelector('.bg-white');
        const form = document.getElementById('revokeForm');
        const message = document.getElementById('revokeMessage');

        form.action = actionUrl;
        if (message) {
            message.textContent = `Are you sure you want to revoke "${customerName}"'s access for "${videoTitle}" immediately?`;
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeRevokeModal() {
        const modal = document.getElementById('revokeModal');
        const content = modal.querySelector('.bg-white');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Auto submit on status change
    document.getElementById('statusFilter').addEventListener('change', function() {
        document.getElementById('searchForm').submit();
    });

    // Search with Enter key
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('searchForm').submit();
        }
    });

    // Auto search with debounce (500ms delay)
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
            const rows = document.querySelectorAll('#requestsTableBody tr');
            rows.forEach(row => {
                const text = row.textContent;
                if (text.toLowerCase().includes(searchTerm.toLowerCase())) {
                    row.style.backgroundColor = 'rgba(99, 102, 241, 0.05)';
                }
            });
        }
    });
</script>
@endsection