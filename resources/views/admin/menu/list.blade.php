<x-app-layout>
    <x-slot name="header">
        {{ __('Itinerary List') }}
    </x-slot>
    @section('styles')
        <style>
            .ui-sortable-placeholder {
                background: #e0f2fe;
                border: 2px dashed #3b82f6;
                visibility: visible !important;
                height: 2.5rem;
                border-radius: 0.375rem;
            }

            .menu-dropzone {
                min-height: 3rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-style: italic;
                user-select: none;
            }
        </style>
    @endsection
    @php
        function renderMenu($menu, $menus)
        {
            $children = $menus->where('parent_id', $menu->id);
            echo '<li class="menu-item border bg-white rounded shadow-sm p-3 mb-2" data-id="' . $menu->id . '">';
            echo '  <div class="flex justify-between items-center">';
            echo '    <div class="flex items-center gap-2 drag-handle cursor-move text-gray-700 font-medium">';
            echo '      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
            echo '        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />';
            echo '      </svg>';
            echo '      <span>' . $menu->name . '</span>';
            echo '    </div>';
            echo '    <div class="space-x-3 text-sm">';
            echo '      <a href="' . route('menu.edit', $menu->id) . '" class="text-blue-500 hover:underline">Edit</a>';
            echo '      <a href="' .
                route('menu.delete', $menu->id) .
                '" class="text-red-500 hover:underline" onclick="return confirm(\'Delete this menu?\')">Delete</a>';
            echo '    </div>';
            echo '  </div>';

            echo '<ul class="menu-list mt-2 pl-6 border-l border-gray-200 space-y-2">';
            foreach ($children as $child) {
                renderMenu($child, $menus);
            }
            echo '<li class="menu-dropzone bg-gray-100 border border-dashed border-gray-400 text-center text-gray-500 py-2 rounded cursor-pointer text-sm">';
            echo 'Drop here to add submenu under <strong>' . $menu->name . '</strong>';
            echo '</li>';
            echo '</ul>';
            echo '</li>';
        }
    @endphp

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="h-auto">
                        @if (Session::has('success'))
                            <div class="text-green-500 text-sm mt-1 p-[20px]" role="alert">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        <div class="pb-[100px]">
                            <a href="{{ route('menu.create') }}"
                                class="inline-block float-right px-4 py-2 mb-[10px] mr-[20px] text-sm font-medium text-white bg-green-600 rounded hover:bg-green-800">
                                Add New
                            </a>
                        </div>


                        <div class="bg-gray-50 border border-gray-200 rounded p-4">
                            <ul id="menu-root" class="menu-list space-y-3">
                                @foreach ($menus as $menu)
                                    @if (is_null($menu->parent_id))
                                        @php renderMenu($menu, $menus); @endphp
                                    @endif
                                @endforeach
                                <li
                                    class="menu-dropzone bg-gray-100 border border-dashed border-gray-400 text-center text-gray-500 py-3 rounded cursor-pointer">
                                    Drop here to add menu at root level
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

        <script>
            $(document).ready(function() {
                function initSortable() {
                    $("ul.menu-list").sortable({
                        connectWith: "ul.menu-list",
                        placeholder: "ui-sortable-placeholder",
                        handle: ".drag-handle",
                        items: "> li.menu-item",
                        update: function() {
                            let menuData = [];

                            function buildData($list, parentId = null) {
                                $list.children('li.menu-item').each(function(index) {
                                    const id = $(this).data('id');
                                    menuData.push({
                                        id: id,
                                        parent_id: parentId,
                                        order: index + 1
                                    });

                                    const $child = $(this).children('ul.menu-list');
                                    if ($child.length) {
                                        buildData($child, id);
                                    }
                                });
                            }

                            buildData($('#menu-root'));

                            $.post("{{ route('menu.updateOrder') }}", {
                                _token: "{{ csrf_token() }}",
                                menuData: menuData
                            });
                        }
                    }).disableSelection();
                }

                initSortable();
            });
        </script>
    @endsection
</x-app-layout>
