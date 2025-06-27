<x-app-layout>
    <x-slot name="header">
        {{ __('Itinearary') }}
    </x-slot>
    <form action="{{ route('blog.store') }}" method="POST"
        class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg space-y-6">
        @csrf

        <h2 class="text-2xl font-bold text-gray-800">Create New Blog Post</h2>

        <!-- Blog Title -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title"
                class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Featured Image Picker -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
            <div id="featured-preview" onclick="openMediaModal()"
                class="w-full h-60 bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50">
                <span class="text-gray-500">Click to select image</span>
            </div>
            <input type="hidden" name="featured_image" id="featured_image">
        </div>

        <!-- Blog Description (TinyMCE) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" id="editor" rows="10" class="w-full border border-gray-300 rounded px-4 py-2"></textarea>
        </div>

        <!-- Submit Button -->
        <div class="text-right">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow">
                Publish Blog
            </button>
        </div>
    </form>

    <!-- TinyMCE -->
    @section('scripts')
        <script>
            tinymce.init({
                selector: '#editor',
                height: 300,
                menubar: false,
                plugins: 'link image code lists media table',
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image media',
            });
            tinymce.init({
                selector: '#editor',
                height: 400,
                plugins: 'image link code lists media',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media',

                file_picker_types: 'image',
                file_picker_callback: function(callback, value, meta) {
                    if (meta.filetype === 'image') {
                        // Save the callback so the modal can use it
                        window.tinyMCEImageCallback = callback;
                        // Open your media modal
                        window.open('{{ route('media.modal') }}', 'FileManager', 'width=900,height=600');
                    }
                }
            });
        </script>

        <!-- Media Picker Integration -->
        <script>
            function openMediaModal() {
                window.open("{{ route('media.modal') }}", "FileManager", "width=1000,height=700");
            }

            function selectMedia(url) {
                document.getElementById('featured_image').value = url;
                document.getElementById('featured-preview').innerHTML =
                    `<img src="${url}" class="w-full h-full object-cover rounded-lg" alt="Featured Image">`;
            }
        </script>
    @endsection

</x-app-layout>
