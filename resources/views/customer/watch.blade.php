@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Breadcrumbs / Back navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('customer.dashboard') }}" 
           class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors duration-200">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Catalog
        </a>
        
        <!-- Live Countdown Widget -->
        @if($access)
            <div class="flex items-center px-4 py-2 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-semibold shadow-sm">
                <span class="text-slate-500 text-xs font-medium mr-2">Remaining Watch Time:</span>
                <span id="watchdog_timer" class="flex items-center font-mono" data-expiry="{{ $access->valid_until->timestamp }}">
                    <svg class="w-4 h-4 mr-1 text-emerald-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    00:00:00
                </span>
            </div>
        @else
            <!-- Admin View bypass badge -->
            <div class="flex items-center px-4 py-2 bg-indigo-50 border border-indigo-200 rounded-xl text-indigo-700 text-sm font-semibold shadow-sm">
                <span class="text-slate-500 text-xs font-medium mr-2">Mode:</span>
                <span>Administrator Bypass</span>
            </div>
        @endif
    </div>

    <!-- Theater Screen Container -->
    <div class="relative rounded-2xl overflow-hidden bg-black aspect-video shadow-xl border border-slate-200" id="videoContainer">
        <!-- Secure HTML5 Video Player -->
        <video id="cinemaPlayer" controls autoplay controlsList="nodownload" oncontextmenu="return false;"
               class="w-full h-full object-contain">
            <source src="{{ route('video.stream', $video->id) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <!-- Expired Access Overlay -->
        <div id="expiredOverlay" class="absolute inset-0 bg-white/95 backdrop-blur-md flex flex-col items-center justify-center space-y-4 z-40 hidden transition-all duration-500 opacity-0">
            <div class="p-4 rounded-full bg-rose-50 text-rose-500 border border-rose-200 shadow-xl shadow-rose-500/10 animate-bounce">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0-6V7m0 8h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z" /></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight text-center">Viewing Time Has Expired!</h2>
            <p class="text-sm text-slate-500 text-center max-w-sm">Your temporary access permission has ended. Redirecting you to the catalog to request access again...</p>
            <div class="w-48 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                <div class="h-full bg-rose-500 animate-[loading-bar_3s_linear] w-full"></div>
            </div>
            <p class="text-xs text-slate-400 mt-2">Redirecting in 3 seconds...</p>
        </div>
    </div>

    <!-- Video Metadata Details -->
    <div class="bg-white rounded-2xl p-6 md:p-8 space-y-4 shadow-sm border border-slate-100">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-800">{{ $video->title }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed mt-2">{{ $video->description ?: 'No description provided.' }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <svg class="w-3 h-3 mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Watching
                </span>
            </div>
        </div>
        
        <!-- Video Info Tags -->
        <div class="flex flex-wrap gap-3 pt-2 border-t border-slate-100">
            <div class="flex items-center text-xs text-slate-500">
                <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Uploaded: {{ $video->created_at->format('d M Y') }}</span>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes loading-bar {
        from { width: 0%; }
        to { width: 100%; }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const timerEl = document.getElementById('watchdog_timer');
        const videoPlayer = document.getElementById('cinemaPlayer');
        const overlay = document.getElementById('expiredOverlay');
        
        if (!timerEl) return; // Administrator mode, no countdown needed

        const expiry = parseInt(timerEl.getAttribute('data-expiry'));

        function checkWatchdog() {
            const now = Math.floor(Date.now() / 1000);
            const diff = expiry - now;

            if (diff <= 0) {
                // Access expired
                timerEl.innerHTML = '<span class="text-rose-500 font-semibold">00:00:00</span>';
                
                // 1. Pause video
                videoPlayer.pause();
                
                // 2. Sever stream source completely to prevent hacking
                videoPlayer.src = "";
                videoPlayer.load();

                // 3. Show expired overlay
                overlay.classList.remove('hidden');
                setTimeout(() => {
                    overlay.classList.remove('opacity-0');
                }, 10);

                // 4. Redirect to catalog after 3 seconds
                setTimeout(() => {
                    window.location.href = "{{ route('customer.dashboard') }}";
                }, 3000);
                
                return;
            }

            const h = Math.floor(diff / 3600);
            const m = Math.floor((diff % 3600) / 60);
            const s = diff % 60;

            const hStr = h.toString().padStart(2, '0');
            const mStr = m.toString().padStart(2, '0');
            const sStr = s.toString().padStart(2, '0');

            timerEl.innerHTML = `
                <svg class="w-4 h-4 mr-1 text-emerald-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="text-emerald-700">${hStr}:${mStr}:${sStr}</span>
            `;
        }

        checkWatchdog();
        const intervalId = setInterval(checkWatchdog, 1000);
    });
</script>
@endsection