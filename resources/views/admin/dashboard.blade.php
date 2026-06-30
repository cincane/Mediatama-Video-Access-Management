@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Admin Dashboard</h2>
        <p class="text-sm text-slate-500 mt-1">System overview and pending action items.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Customers -->
        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-slate-100 hover:border-blue-200 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Customers</p>
                    <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['total_customers'] }}</p>
                </div>
            </div>
        </div>

        <!-- Videos -->
        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-slate-100 hover:border-indigo-200 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Videos</p>
                    <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['total_videos'] }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-slate-100 hover:border-amber-200 hover:-translate-y-1 relative overflow-hidden">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-amber-50 to-amber-100 text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Pending Requests</p>
                    <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['pending_requests'] }}</p>
                </div>
            </div>
            @if($stats['pending_requests'] > 0)
                <div class="absolute top-0 right-0 w-2 h-2 bg-amber-500 rounded-bl-lg animate-pulse"></div>
                <div class="absolute -top-8 -right-8 w-16 h-16 bg-amber-500/5 rounded-full"></div>
            @endif
        </div>

        <!-- Active Accesses -->
        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-slate-100 hover:border-emerald-200 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100 text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Active Permissions</p>
                    <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['active_accesses'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests List -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Pending Access Requests
            </h3>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                <span class="inline-block w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5 animate-pulse"></span>
                Needs Approval
            </span>
        </div>
        
        <div class="overflow-x-auto">
            @if($pendingRequests->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-emerald-50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="font-medium text-slate-600">No pending access requests</p>
                    <p class="text-sm text-slate-400 mt-1">All requests have been processed. Good job! </p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <th class="p-4 pl-6">Customer</th>
                            <th class="p-4">Video</th>
                            <th class="p-4">Requested At</th>
                            <th class="p-4 pr-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @foreach($pendingRequests as $req)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 pl-6">
                                    <div class="font-medium text-slate-800">{{ $req->user->name }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $req->user->email }}</div>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 font-medium text-slate-700">
                                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        {{ $req->video->title }}
                                    </span>
                                </td>
                                <td class="p-4 text-slate-500">
                                    {{ $req->created_at->format('d M Y H:i') }}
                                    <span class="text-xs text-slate-400 ml-1">({{ $req->created_at->diffForHumans() }})</span>
                                </td>
                                <td class="p-4 pr-6 text-right space-x-2">
                                    <!-- Approve Button -->
                                    <button onclick="openApproveModal({{ $req->id }}, '{{ addslashes($req->user->name) }}', '{{ addslashes($req->video->title) }}')"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 hover:bg-emerald-500 text-emerald-700 hover:text-white border border-emerald-200 hover:border-emerald-500 transition-all duration-200 cursor-pointer">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approve
                                    </button>

                                    <!-- Reject Button -->
                                    <button onclick="openRejectModal('{{ route('admin.requests.reject', $req->id) }}', '{{ addslashes($req->user->name) }}', '{{ addslashes($req->video->title) }}')"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 hover:bg-rose-500 text-rose-700 hover:text-white border border-rose-200 hover:border-rose-500 transition-all duration-200 cursor-pointer">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Reject
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<!-- Modal Overlay for Approval Duration -->
<div id="approveModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-slate-100 m-4 relative overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-indigo-500"></div>
        
        <div class="flex items-center gap-3 mb-2">
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

<!-- Modal Overlay for Reject Request -->
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
</script>
@endsection

