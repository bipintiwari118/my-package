<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Media Library</title>
    <!-- Tailwind CSS v3.1.0 compiled CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">



    <div class="bg-white w-full max-w-5xl mx-auto p-6 rounded-lg shadow-lg overflow-y-auto mb-[100px] h-[90vh]">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl w-full font-bold text-center">Media Library</h2>
            <button onclick="window.close()" class="text-gray-600 hover:text-black text-xl font-bold">&times;</button>
        </div>

        <!-- Upload Form -->
        <form action="{{ route('media.upload') }}" method="POST" enctype="multipart/form-data"
            class="mb-8 space-y-4 bg-gray-50 p-4 rounded-lg shadow">
            @csrf

            <!-- File Picker -->
            <label
                class="block w-full border-2 border-dashed border-gray-300 p-6 text-center bg-white rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
                <input type="file" name="file" id="fileInput" class="hidden" onchange="previewFile(this)" />
                <div class="text-gray-500">
                    <p class="font-medium">Click or drag & drop to upload (jpg, png, pdf)</p>
                </div>
            </label>

            <!-- File Preview -->
            <div id="file-preview" class="hidden">
                <img id="preview-img" src="" alt="Preview" class="w-32 h-32 object-cover rounded mb-4">
            </div>

            <!-- Meta Fields -->
            <div id="media-meta-fields" class="hidden transition  space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alt Text</label>
                    <input type="text" name="alt" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="2" class="w-full border rounded px-3 py-2"></textarea>
                </div>
                <div class="flex justify-between items-center">
                    <button type="button" onclick="hideMetaFields()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Upload File
                    </button>
                </div>
            </div>
        </form>

        <!-- Media Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse ($media as $file)
                <div
                    class="relative group border rounded bg-white shadow-sm hover:shadow-md transition overflow-hidden">
                    @if (Str::startsWith($file->mime_type, 'image/'))
                        <img src="{{ $file->url }}" alt="Image" class="w-full h-32 object-cover">
                    @elseif($file->extension === 'pdf')
                        <embed src="{{ $file->url }}" type="application/pdf" class="w-full h-32" />
                    @else
                        <div class="w-full h-32 flex items-center justify-center text-gray-500">
                            {{ strtoupper($file->extension) }}
                        </div>
                    @endif

                    <div class="p-2 text-xs truncate text-gray-700">
                        {{ $file->original_name }}
                    </div>

                    <div
                        class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 flex items-center justify-center gap-2 transition-opacity">
                        <button
                            onclick="selectMedia(
                            '{{ $file->url }}',
                            '{{ $file->alt ?? '' }}',
                            '{{ $file->title ?? '' }}',
                            '{{ $file->description ?? '' }}')"
                            class="bg-green-600
                            hover:bg-green-700 text-white px-3 py-1 text-sm rounded">
                            Select
                        </button>
                        <form action="{{ route('media.destroy', $file->id) }}" method="POST"
                            onsubmit="return confirm('Delete this file?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 text-sm rounded">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">No media files uploaded yet.</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $media->links() }}
        </div>
    </div>
    <script>
        function selectMedia(url, alt = '', title = '', description = '') {
            if (window.opener && !window.opener.closed) {
                const imageData = {
                    url: url,
                    alt: alt,
                    title: title,
                    description: description,
                };
                window.opener.selectMedia(imageData);
                window.close();
            }
        }

        function previewFile(input) {
            const file = input.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const imgPreview = document.getElementById('preview-img');
                imgPreview.src = e.target.result;
                document.getElementById('file-preview').classList.remove('hidden');
                document.getElementById('media-meta-fields').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }

        function hideMetaFields() {
            document.getElementById('media-meta-fields').classList.add('hidden');
            document.getElementById('file-preview').classList.add('hidden');
            document.getElementById('fileInput').value = '';
        }
    </script>
</body>

</html>
