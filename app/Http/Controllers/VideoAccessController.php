<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoAccess;
use Illuminate\Http\Request;

class VideoAccessController extends Controller
{
    // Admin lists all requests
    public function index(Request $request)
    {
        $query = VideoAccess::with(['user', 'video']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%")
                              ->orWhere('email', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('video', function($videoQuery) use ($search) {
                    $videoQuery->where('title', 'LIKE', "%{$search}%");
                })
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->orWhereDate('created_at', 'LIKE', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Pagination
        $requests = $query->latest()->paginate(10);

        // Check and mark expired requests on load
        foreach ($requests as $req) {
            if ($req->status === 'approved' && $req->valid_until && $req->valid_until->isPast()) {
                $req->status = 'expired';
                $req->save();
            }
        }

        // Get status counts for filter
        $statusCounts = [
            'all' => VideoAccess::count(),
            'pending' => VideoAccess::where('status', 'pending')->count(),
            'approved' => VideoAccess::where('status', 'approved')->count(),
            'rejected' => VideoAccess::where('status', 'rejected')->count(),
            'expired' => VideoAccess::where('status', 'expired')->count(),
        ];

        return view('admin.requests.index', compact('requests', 'statusCounts'));
    }

    // Customer requests access
    public function requestAccess(Request $request, Video $video)
    {
        $user = $request->user();

        // Check if there is an active or pending request
        $existingAccess = VideoAccess::where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->first();

        if ($existingAccess) {
            if ($existingAccess->status === 'pending') {
                return back()->with('error', 'You already have a pending request for this video.');
            }
            if ($existingAccess->isActive()) {
                return back()->with('error', 'You already have active access to this video.');
            }
            // If expired or rejected, we allow requesting again by updating status to pending
            $existingAccess->update([
                'status' => 'pending',
                'valid_until' => null,
            ]);
        } else {
            VideoAccess::create([
                'user_id' => $user->id,
                'video_id' => $video->id,
                'status' => 'pending',
            ]);
        }

        return back()->with('success', 'Access request submitted successfully! Waiting for admin approval.');
    }

    // Admin approves request
    public function approve(Request $request, VideoAccess $access)
    {
        $request->validate([
            'duration' => ['required', 'integer', 'min:1'],
            'duration_unit' => ['required', 'string', 'in:minutes,hours,days'],
        ]);

        $duration = (int) $request->duration;
        $unit = $request->duration_unit;

        $validUntil = now();
        if ($unit === 'minutes') {
            $validUntil = $validUntil->addMinutes($duration);
        } elseif ($unit === 'hours') {
            $validUntil = $validUntil->addHours($duration);
        } else {
            $validUntil = $validUntil->addDays($duration);
        }

        $access->update([
            'status' => 'approved',
            'valid_until' => $validUntil,
        ]);

        return back()->with('success', 'Request approved! Access granted until ' . $validUntil->format('d M Y H:i:s') . '.');
    }

    // Admin rejects request
    public function reject(VideoAccess $access)
    {
        $access->update([
            'status' => 'rejected',
            'valid_until' => null,
        ]);

        return back()->with('success', 'Request has been rejected.');
    }

    // Admin revokes access
    public function revoke(VideoAccess $access)
    {
        $access->update([
            'status' => 'expired',
            'valid_until' => now(),
        ]);

        return back()->with('success', 'Access has been revoked immediately.');
    }
}
