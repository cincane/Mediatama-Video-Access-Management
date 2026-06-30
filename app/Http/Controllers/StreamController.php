<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StreamController extends Controller
{
    public function stream(Request $request, Video $video)
    {
        $user = $request->user();

        // 1. Authorize access
        if (!$user->isAdmin()) {
            $access = VideoAccess::where('user_id', $user->id)
                ->where('video_id', $video->id)
                ->first();

            if (!$access || !$access->isActive()) {
                abort(403, 'You do not have permission to watch this video or your access has expired.');
            }
        }

        // 2. Fetch the file path
        $filePath = $video->file_path;

        if (!Storage::disk('local')->exists($filePath)) {
            abort(404, 'Video file not found on disk.');
        }

        // 3. Stream the file securely
        // Laravel's response()->file() wraps Symfony BinaryFileResponse, supporting range requests
        $absolutePath = Storage::disk('local')->path($filePath);
        
        $mimeType = Storage::disk('local')->mimeType($filePath) ?: 'video/mp4';

        $headers = [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . rawurlencode(basename($video->title)) . '"',
        ];

        return response()->file($absolutePath, $headers);
    }
}
