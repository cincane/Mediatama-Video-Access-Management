<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhereDate('created_at', 'LIKE', "%{$search}%");
            });
        }
        $videos = $query->latest()->paginate(10);
        
        return view('admin.videos.index', compact('videos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'video' => ['required', 'file', 'mimetypes:video/mp4,video/mpeg,video/quicktime,video/x-msvideo,video/x-matroska', 'max:102400'], // max 100MB
        ]);

        $path = $request->file('video')->store('videos', 'local');

        Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
        ]);

        return redirect()->route('admin.videos.index')->with('success', 'Video successfully uploaded and created!');
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/mpeg,video/quicktime,video/x-msvideo,video/x-matroska', 'max:102400'], // max 100MB
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        if ($request->hasFile('video')) {
            // Delete old file
            if (Storage::disk('local')->exists($video->file_path)) {
                Storage::disk('local')->delete($video->file_path);
            }
            // Store new file
            $path = $request->file('video')->store('videos', 'local');
            $data['file_path'] = $path;
        }

        $video->update($data);

        return redirect()->route('admin.videos.index')->with('success', 'Video successfully updated!');
    }

    public function destroy(Video $video)
    {
        // Delete video file from private storage
        if (Storage::disk('local')->exists($video->file_path)) {
            Storage::disk('local')->delete($video->file_path);
        }

        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', 'Video successfully deleted!');
    }
}
