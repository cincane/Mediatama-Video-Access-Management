@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Header with Upload Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Video Library Management</h2>
            <p class="text-sm text-slate-500">Upload new video files and manage metadata.</p>
        </div>
        <div>
            <button onclick="openUploadModal()"
                class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl text-sm shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 transition-all duration-300 cursor-pointer">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                Upload Video
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
                <a href="{{ route('admin.videos.index') }}" 
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
                <span class="font-medium text-indigo-600">{{ $videos->total() }}</span> video(s) found
            @else
                Showing all <span class="font-medium text-slate-700">{{ $videos->total() }}</span> video(s)
            @endif
        </div>
    </div>

    <!-- Error Validation Messages -->
    @if($errors->any())
        <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <h4 class="font-bold mb-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Failed to upload/update video:
            </h4>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Videos List Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="overflow-x-auto">
            @if($videos->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-indigo-50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </div>
                    <p class="font-medium text-slate-600">No videos uploaded yet.</p>
                    <p class="text-sm text-slate-400 mt-1">Click "Upload Video" to start adding files to the library.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <th class="p-4 pl-6">Title</th>
                            <th class="p-4">Description</th>
                            {{-- <th class="p-4">File Name</th> --}}
                            <th class="p-4">Date Uploaded</th>
                            <th class="p-4 pr-6 text-right font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @foreach($videos as $v)
                            <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                                <td class="p-4 pl-6">
                                    <div class="flex items-center gap-2">
                                <span class="font-semibold text-slate-800">{{ $v->title }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-slate-500 max-w-xs truncate">
                                    {{ $v->description ?: '-' }}
                                </td>
                                {{-- <td class="p-4 text-slate-400 font-mono text-xs max-w-xs truncate">
                                    {{ basename($v->file_path) }}
                                </td> --}}
                                <td class="p-4 text-slate-500 text-sm">
                                    {{ $v->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="p-4 pr-6 text-right space-x-2">
                                    <!-- Watch Stream -->
                                    <a href="{{ route('video.stream', $v->id) }}" target="_blank"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 hover:bg-emerald-500 text-emerald-700 hover:text-white border border-emerald-200 hover:border-emerald-500 transition-all duration-200">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Watch
                                    </a>

                                    <!-- Edit Trigger -->
                                    <button onclick="openEditVideoModal({{ $v->id }}, '{{ addslashes($v->title) }}', '{{ addslashes($v->description) }}')"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 hover:bg-indigo-500 text-indigo-700 hover:text-white border border-indigo-200 hover:border-indigo-500 transition-all duration-200 cursor-pointer">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>

                                    <!-- Delete Trigger -->
                                    <button onclick="openDeleteModal('{{ route('admin.videos.destroy', $v->id) }}', '{{ addslashes($v->title) }}')"
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
                    {{ $videos->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Overlay for Upload Video -->
<div id="uploadVideoModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-slate-100 m-4 relative overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 to-emerald-500"></div>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 rounded-xl bg-indigo-50">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Upload Video</h3>
                <p class="text-xs text-slate-500">Upload a video file to the secure local storage.</p>
            </div>
        </div>

        <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            
            <div>
                <label for="upload_title" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Video Title</label>
                <input type="text" name="title" id="upload_title" required
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm placeholder:text-slate-400"
                    placeholder="Enter descriptive title">
            </div>

            <div>
                <label for="upload_description" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Description</label>
                <textarea name="description" id="upload_description" rows="3"
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm placeholder:text-slate-400"
                    placeholder="Optional details..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Video File</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-xl bg-slate-50/50 hover:border-indigo-300 hover:bg-slate-50 transition-all cursor-pointer relative">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-slate-600 justify-center">
                            <label for="upload_file" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                <span>Upload a file</span>
                                <input id="upload_file" name="video" type="file" required class="sr-only" accept="video/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-slate-400">MP4, MKV, MOV up to 100MB</p>
                    </div>
                </div>
                <div id="file_selected_indicator" class="mt-2 text-xs text-emerald-600 font-semibold hidden text-center"></div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeUploadModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl text-sm transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl text-sm shadow-lg shadow-indigo-600/20 transition-all cursor-pointer">
                    Upload & Add
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Overlay for Edit Video -->
<div id="editVideoModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-slate-100 m-4 relative overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-violet-500 to-indigo-500"></div>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 rounded-xl bg-violet-50">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Edit Video</h3>
                <p class="text-xs text-slate-500">Update video details. Uploading a new video will replace the existing file.</p>
            </div>
        </div>

        <form id="editVideoForm" action="" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label for="edit_title" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Video Title</label>
                <input type="text" name="title" id="edit_title" required
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm placeholder:text-slate-400"
                    placeholder="Enter descriptive title">
            </div>

            <div>
                <label for="edit_description" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Description</label>
                <textarea name="description" id="edit_description" rows="3"
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm placeholder:text-slate-400"
                    placeholder="Optional details..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Replace Video File (Optional)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-xl bg-slate-50/50 hover:border-indigo-300 hover:bg-slate-50 transition-all cursor-pointer relative">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-slate-600 justify-center">
                            <label for="edit_file" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                <span>Upload replacement file</span>
                                <input id="edit_file" name="video" type="file" class="sr-only" accept="video/*">
                            </label>
                        </div>
                        <p class="text-xs text-slate-400">MP4, MKV, MOV up to 100MB</p>
                    </div>
                </div>
                <div id="edit_file_selected_indicator" class="mt-2 text-xs text-emerald-600 font-semibold hidden text-center"></div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeEditVideoModal()"
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

<!-- Modal Overlay for Delete Video -->
<div id="deleteVideoModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-slate-100 m-4 relative overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 to-red-500"></div>
        
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 mb-4">
                <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Delete Video</h3>
            <p class="text-sm text-slate-500 mt-2" id="deleteMessage">Are you sure you want to delete this video?</p>
            <p class="text-xs text-rose-600/80 mt-1 font-medium">This action cannot be undone. The video file will be permanently removed from storage.</p>
            
            <form id="deleteVideoForm" action="" method="POST" class="mt-6 flex items-center justify-center space-x-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl text-sm transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-rose-600 hover:bg-rose-500 text-white font-medium rounded-xl text-sm shadow-lg shadow-rose-600/20 transition-all cursor-pointer">
                    Yes, Delete Video
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // File upload indicator listener
    document.getElementById('upload_file').addEventListener('change', function(e) {
        const fileIndicator = document.getElementById('file_selected_indicator');
        if(e.target.files.length > 0) {
            fileIndicator.textContent = "Selected: " + e.target.files[0].name + " (" + (e.target.files[0].size/1024/1024).toFixed(2) + " MB)";
            fileIndicator.classList.remove('hidden');
        } else {
            fileIndicator.classList.add('hidden');
        }
    });

    document.getElementById('edit_file').addEventListener('change', function(e) {
        const fileIndicator = document.getElementById('edit_file_selected_indicator');
        if(e.target.files.length > 0) {
            fileIndicator.textContent = "Selected: " + e.target.files[0].name + " (" + (e.target.files[0].size/1024/1024).toFixed(2) + " MB)";
            fileIndicator.classList.remove('hidden');
        } else {
            fileIndicator.classList.add('hidden');
        }
    });

    // Upload Modal functions
    function openUploadModal() {
        const modal = document.getElementById('uploadVideoModal');
        const content = modal.querySelector('.bg-white');
        document.getElementById('file_selected_indicator').classList.add('hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeUploadModal() {
        const modal = document.getElementById('uploadVideoModal');
        const content = modal.querySelector('.bg-white');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Edit Modal functions
    function openEditVideoModal(id, title, description) {
        const modal = document.getElementById('editVideoModal');
        const content = modal.querySelector('.bg-white');
        const form = document.getElementById('editVideoForm');
        
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_file_selected_indicator').classList.add('hidden');
        
        form.action = `/admin/videos/${id}`;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeEditVideoModal() {
        const modal = document.getElementById('editVideoModal');
        const content = modal.querySelector('.bg-white');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // DELETE MODAL FUNCTIONS
    function openDeleteModal(actionUrl, videoTitle) {
        const modal = document.getElementById('deleteVideoModal');
        const content = modal.querySelector('.bg-white');
        const form = document.getElementById('deleteVideoForm');
        const message = document.getElementById('deleteMessage');
        
        form.action = actionUrl;
        
        if (message) {
            message.textContent = `Are you sure you want to delete "${videoTitle}"?`;
        }
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteVideoModal');
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
            const rows = document.querySelectorAll('#videosTableBody tr');
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