<x-app-layout>
    <x-slot name="header">
        {{ __('Blog List') }}
    </x-slot>

    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <div class="flex flex-row justify-between w-full mb-4">
                <h2 class="text-2xl font-semibold leading-tight">Blogs</h2>
                <a href="{{ route('blog.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Blog
                </a>
            </div>
            @if (Session::has('success'))
                <div class="text-green-500 text-[20px] mt-1  ml-[200px] p-[10px]" role="alert">
                    {{ Session::get('success') }}
                </div>
            @endif

            <!-- Filter Section -->
            <form method="GET" action="{{ route('blog.list') }}" class="mb-4">
                <div class="flex items-center gap-4">
                    <label for="category" class="text-gray-700 font-semibold">Filter by Search:</label>
                    <input type="text" name="keyword" id="search"
                        class="border w-[400px] border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Search here" value="{{ request('keyword') }}">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Apply Filter
                    </button>
                    @if (request('keyword'))
                        <a href="{{ route('blog.list') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Clear
                        </a>
                    @endif
                </div>
            </form>


            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal shadow-md rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-800 text-white text-left text-sm uppercase font-semibold tracking-wider">
                            <th class="px-5 py-3">Id</th>
                            <th class="px-5 py-3">Title</th>
                            <th class="px-5 py-3">Image</th>
                            <th class="px-5 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <!-- Example Row -->
                        @if ($blogs->count() > 0)
                            @foreach ($blogs as $blog)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="px-5 py-5 text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap  text-[20px]">{{ $blog->id }}</p>
                                    </td>
                                    <td class="px-5 py-5 text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap  text-[20px]">
                                            <a href="{{ url('blog/' . $blog->id) }}"
                                                class="hover:underline hover:decoration-green-500">
                                                {{ \Illuminate\Support\Str::limit($blog->title, 20, '...') }}
                                            </a>
                                        </p>
                                    </td>
                                    <td class="px-5 py-5 text-sm">
                                        @php
                                            $featured = $blog->featured_image;
                                        @endphp

                                        @if (!empty($featured['url']))
                                            <img src="{{ asset($featured['url']) }}"
                                                alt="{{ $featured['alt'] ?? 'Blog Image' }}"
                                                title="{{ $featured['title'] ?? $blog->title }}"
                                                class="w-[50px] h-[50px] object-cover rounded">
                                        @else
                                            <img src="{{ asset('default.jpg') }}" alt="Default Image"
                                                title="Default Image" class="w-[50px] h-[50px] object-cover rounded">
                                        @endif

                                    </td>

                                    <td class="px-5 py-5 text-sm text-center">
                                        <a href="{{ route('blog.edit', $blog->id) }}"
                                            class="text-blue-500 hover:text-blue-700 font-bold mr-2">Edit</a>
                                        <a href="{{ route('blog.delete', $blog->id) }}"
                                            class="text-red-500 hover:text-red-700 font-bold"
                                            onclick="alert('Are you sure to delete this Blog.')">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            <!-- Add more rows dynamically -->
                        @else
                            <tr>
                                <td colspan="6"
                                    class="border-b border-gray-200 hover:bg-gray-100 text-center py-[15px]">No blogs
                                    found.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="flex justify-center mt-2">
                    {{ $blogs->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
