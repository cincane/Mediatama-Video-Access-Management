@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Available Videos</h2>
        <p class="text-sm text-slate-500">Request permission to watch or start streaming approved content.</p>
    </div>

    <!-- Video Grid -->
    @if($videos->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-100 shadow-sm">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-indigo-50 flex items-center justify-center">
                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-600">No videos in the library.</h3>
            <p class="text-sm text-slate-400 mt-1">Please ask the admin to upload some videos.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($videos as $v)
                @php
                    $access = $accesses->get($v->id);
                    $hasActiveAccess = $access && $access->isActive();
                @endphp
                
                <!-- Video Card -->
                <div class="bg-white rounded-2xl flex flex-col h-full relative overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 border border-slate-100 hover:border-indigo-200 hover:-translate-y-1 group">
                    <!-- Glow effect for active videos -->
                    @if($hasActiveAccess)
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                    @elseif($access && $access->status === 'pending')
                        <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500"></div>
                    @elseif($access && $access->status === 'rejected')
                        <div class="absolute top-0 left-0 right-0 h-1 bg-rose-500"></div>
                    @endif

                    <!-- Video Details -->
                    <div class="p-6 flex-1 space-y-4">
                        <div class="flex items-start justify-between">
                            <div class="p-2.5 rounded-lg bg-gradient-to-br from-indigo-50 to-indigo-100 text-indigo-600 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            
                            <!-- Access Badge -->
                            @if($hasActiveAccess)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <svg class="w-3 h-3 mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Access Granted
                                </span>
                            @elseif($access && $access->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="inline-block w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5 animate-pulse"></span>
                                    Pending
                                </span>
                            @elseif($access && $access->status === 'rejected')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                    <svg class="w-3 h-3 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Rejected
                                </span>
                            @elseif($access && $access->status === 'expired')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-500 border border-slate-200">
                                    <svg class="w-3 h-3 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Expired
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-400 border border-slate-200">
                                    <svg class="w-3 h-3 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Locked
                                </span>
                            @endif
                        </div>

                        <div>
                            <h4 class="font-bold text-slate-800 text-lg group-hover:text-indigo-600 transition-colors leading-snug">{{ $v->title }}</h4>
                            <p class="text-sm text-slate-500 mt-2 line-clamp-3 leading-relaxed">{{ $v->description ?: 'No description provided.' }}</p>
                        </div>
                    </div>

                    <!-- Action Footer -->
                    <div class="p-6 pt-0 border-t border-slate-100 bg-slate-50/30">
                        @if($hasActiveAccess)
                            <!-- Watch Access with Countdown -->
                            <div class="space-y-3.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium">Remaining Time:</span>
                                    <div class="active-timer font-semibold text-emerald-600 flex items-center" 
                                         data-expiry="{{ $access->valid_until->timestamp }}">
                                        <svg class="w-3.5 h-3.5 mr-1 text-emerald-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Calculating...
                                    </div>
                                </div>
                                <a href="{{ route('customer.watch', $v->id) }}"
                                    class="w-full flex items-center justify-center py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold rounded-xl text-sm shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/30 transition-all duration-300">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /></svg>
                                    Watch Video
                                </a>
                            </div>
                        @elseif($access && $access->status === 'pending')
                            <!-- Disabled Pending Button -->
                            <button disabled
                                class="w-full flex items-center justify-center py-2.5 bg-slate-100 border border-slate-200 text-slate-400 font-semibold rounded-xl text-sm cursor-not-allowed">
                                <svg class="w-4 h-4 mr-2 text-slate-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Waiting for Approval
                            </button>
                        @elseif($access && $access->status === 'rejected')
                            <!-- Rejected Status -->
                            <button disabled
                                class="w-full flex items-center justify-center py-2.5 bg-rose-50 border border-rose-200 text-rose-400 font-semibold rounded-xl text-sm cursor-not-allowed">
                                <svg class="w-4 h-4 mr-2 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                Request Rejected
                            </button>
                        @else
                            <!-- Request Access Form -->
                            <form action="{{ route('customer.requests.request', $v->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center justify-center py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl text-sm shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 transition-all duration-300 cursor-pointer">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                    Request Access
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.active-timer').forEach(el => {
            const expiry = parseInt(el.getAttribute('data-expiry'));
            
            function updateTimer() {
                const now = Math.floor(Date.now() / 1000);
                const diff = expiry - now;
                
                if (diff <= 0) {
                    el.innerHTML = '<span class="text-rose-500 font-semibold">Expired</span>';
                    // Reload to update the card state and button UI
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                    return;
                }
                
                const d = Math.floor(diff / 86400);
                const h = Math.floor((diff % 86400) / 3600);
                const m = Math.floor((diff % 3600) / 60);
                const s = diff % 60;
                
                let timeStr = "";
                if (d > 0) timeStr += `${d}d `;
                if (h > 0 || d > 0) timeStr += `${h}h `;
                timeStr += `${m}m ${s}s`;
                
                el.innerHTML = `
                    <svg class="w-3.5 h-3.5 mr-1 text-emerald-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    ${timeStr}
                `;
            }
            
            updateTimer();
            setInterval(updateTimer, 1000);
        });
    });
</script>
@endsection