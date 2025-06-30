<x-app-layout>
    <x-slot name="header">
        {{ __('Update Blog') }}
    </x-slot>

    <div class="text-left">
        <a href="{{ route('blog.list') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow">
            back
        </a>
    </div>
    <form action="{{ route('blog.update', $blog->id) }}" method="POST"
        class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg space-y-6">
        @csrf
        @if (Session::has('success'))
            <div class="text-green-500 text-[20px] mt-1  ml-[200px] p-[10px]" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif
        <h2 class="text-2xl font-bold text-gray-800">Edit Blog Post</h2>

        <!-- Blog Title -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" value="{{ $blog->title }}"
                class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('title')
                <div class="text-red-500 text-sm mt-1 ml-3">{{ $message }}</div>
            @enderror
        </div>

        <!-- Featured Image Picker -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
            <!-- Clickable upload box (stays unchanged) -->
            <div onclick="openMediaModal()"
                class="w-full h-10 bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50">
                <span class="text-gray-500">Click to select image</span>
            </div>

            <!-- Hidden input to store selected image URL -->
            <input type="hidden" name="featured_image" id="featured_image">

            <!-- Actual preview shown just below the box -->
            <div id="featured-preview-container" class="mt-4 hidden relative">
                <img id="featured-preview-img" src="" alt="Image Preview"
                    class="w-full max-h-64 object-contain rounded-lg shadow">

                <!-- Remove Button -->
                <button type="button" onclick="removeFeaturedImage()"
                    class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-1 rounded shadow">
                    &times;
                </button>
            </div>
        </div>

        <!-- Blog Description (TinyMCE) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" id="editor" rows="10" class="w-full border border-gray-300 rounded px-4 py-2">{!! $blog->description !!}</textarea>

        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>

            <!-- Add Images Button -->
            <div onclick="openGalleryModal()"
                class="w-full h-10 bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50">
                <span class="text-gray-500">Click to add gallery images</span>
            </div>

            <!-- Hidden input to store gallery URLs (comma-separated or JSON if needed) -->
            <input type="hidden" name="gallery_images" id="gallery_images">

            <!-- Preview area -->
            <div id="gallery-preview" class="grid grid-cols-3 gap-3 mt-4"></div>
        </div>

        <!-- Submit Button -->
        <div class="text-right">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow">
                Update Blog
            </button>
        </div>
    </form>

    <!-- TinyMCE -->
    @section('scripts')
        <script>
            tinymce.init({
                selector: '#editor',
                height: 500,
                plugins: 'image link media code lists fullscreen paste autolink preview print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
                toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link image  | code',
                menubar: true,
                branding: false,
                image_description: true,
                file_picker_types: 'image',
                file_picker_callback: function(callback, value, meta) {
                    if (meta.filetype === 'image') {
                        // Save callback to use after selecting an image in the modal
                        window.tinyMCEImageCallback = callback;
                        window.open('{{ route('media.modal') }}', 'FileManager', 'width=900,height=600');
                    }
                }
            });


            // Store selected gallery images
            let galleryImages = [];

            function openGalleryModal() {
                window.selectingGallery = true;
                window.open("{{ route('media.modal') }}?type=multiple", "GalleryManager", "width=1000,height=700");
            }

            function openMediaModal() {
                window.open("{{ route('media.modal') }}", "FileManager", "width=1000,height=700");
            }

            function selectMedia(data) {
                // This is called by the modal for both TinyMCE and Featured Image
                // 1. For TinyMCE Editor
                if (typeof window.tinyMCEImageCallback === 'function') {
                    window.tinyMCEImageCallback(data.url);
                    window.tinyMCEImageCallback = null;
                    return;
                }

                // 2. Gallery image selection
                if (window.selectingGallery) {
                    galleryImages.push(data);
                    renderGalleryPreviews();
                    document.getElementById('gallery_images').value = JSON.stringify(galleryImages);
                    return;
                }

                document.getElementById('featured_image').value = JSON.stringify(data);

                // Update preview image below the box
                const previewImg = document.getElementById('featured-preview-img');
                const previewContainer = document.getElementById('featured-preview-container');

                previewImg.src = data.url;
                previewImg.alt = data.alt ?? '';
                previewImg.title = data.title ?? '';
                previewContainer.classList.remove('hidden');

            }

            function removeFeaturedImage() {
                document.getElementById('featured_image').value = '';
                document.getElementById('featured-preview-container').classList.add('hidden');
                document.getElementById('featured-preview-img').src = '';
            }


            function renderGalleryPreviews() {
                const previewDiv = document.getElementById('gallery-preview');
                previewDiv.innerHTML = galleryImages.map(img => `
        <div class="relative group">
            <img src="${img.url}" class="rounded shadow object-cover w-full h-32" alt="${img.alt}" title="${img.title}">
            <button type="button"
                    class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white text-xs px-2 py-1 rounded"
                    onclick="removeGalleryImage('${img.url}')">
                &times;
            </button>
        </div>
    `).join('');
            }

            function removeGalleryImage(url) {
                galleryImages = galleryImages.filter(img => img.url !== url);
                renderGalleryPreviews();
                document.getElementById('gallery_images').value = JSON.stringify(galleryImages);

            }

            document.addEventListener("DOMContentLoaded", function() {
                const featured = @json($blog->featured_image);
                if (featured && featured.url) {
                    // Set the hidden input value
                    document.getElementById('featured_image').value = JSON.stringify(featured);

                    // Show the preview
                    const previewImg = document.getElementById('featured-preview-img');
                    previewImg.src = featured.url;
                    previewImg.alt = featured.alt ?? '';
                    previewImg.title = featured.title ?? '';

                    document.getElementById('featured-preview-container').classList.remove('hidden');
                }

                // Show gallery images if available
                let rawImages = @json($blog->gallery_images ?? []);

                // Convert JSON strings to objects if needed
                galleryImages = rawImages.map(img =>
                    typeof img === 'string' ? JSON.parse(img) : img
                );

                renderGalleryPreviews();
                document.getElementById('gallery_images').value = JSON.stringify(galleryImages);
            });
        </script>
    @endsection

</x-app-layout>
