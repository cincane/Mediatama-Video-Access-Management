<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoAccess;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    }

    public function adminDashboard()
    {
        $stats = [
            'total_customers' => User::where('role', 'customer')->count(),
            'total_videos' => Video::count(),
            'pending_requests' => VideoAccess::where('status', 'pending')->count(),
            'active_accesses' => VideoAccess::where('status', 'approved')
                ->where('valid_until', '>', now())
                ->count(),
        ];

        $pendingRequests = VideoAccess::with(['user', 'video'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingRequests'));
    }

    public function customerDashboard(Request $request)
    {
        $user = $request->user();
        $videos = Video::all();

        // Get the customer's access state for each video
        $accesses = VideoAccess::where('user_id', $user->id)
            ->get()
            ->keyBy('video_id');

        // Dynamically update expired accesses to ensure clean state
        foreach ($accesses as $access) {
            if ($access->status === 'approved' && $access->valid_until && $access->valid_until->isPast()) {
                $access->status = 'expired';
                $access->save();
            }
        }

        return view('customer.dashboard', compact('videos', 'accesses'));
    }

    public function watch(Request $request, Video $video)
    {
        $user = $request->user();
        $access = null;

        if (!$user->isAdmin()) {
            $access = VideoAccess::where('user_id', $user->id)
                ->where('video_id', $video->id)
                ->first();

            if (!$access || !$access->isActive()) {
                return redirect()->route('customer.dashboard')->with('error', 'You do not have permission to view this video or your access has expired.');
            }
        }

        return view('customer.watch', compact('video', 'access'));
    }
}
