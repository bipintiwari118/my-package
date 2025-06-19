<x-app-layout>
    <x-slot name="header">
        {{ __('Add Menu') }}
    </x-slot>

    <form action="{{ route('menu.store') }}" class="w-full pb-[100px] " method="post">
        @csrf
        <div class="w-full px-[200px] pb-[20px]">
            <button class=" float-right bg-green-600 px-[10px] py-[6px] text-white rounded-md">Submit</button>
            <a href="{{ route('menu.list') }}" class=" float-left bg-blue-600 px-[15px] py-[6px] text-white rounded-md">List</a>
        </div>
        @if (Session::has('success'))
            <div class="text-green-500 text-sm mt-1 p-[20px]" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif
        <div class="w-full h-auto flex flex-col justify-center items-center gap-y-5 pb-[50px]">
            <div class="w-1/2">
                <label class="text-[20px] font-semibold py-[15px] ">Name</label>
                <input type="text" class="w-full rounded-md " name="name">
            </div>

            <div class="w-1/2">
                <label class="text-[20px] font-semibold py-[15px] ">Parent Menu</label>
                <select name="parent_id" id="parent_menu" class="w-full rounded-md">
                      <option value="">Select Option</option>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                    @endforeach

                </select>
            </div>

            <div class="w-1/2">
                <label class="text-[20px] font-semibold py-[15px] ">Url</label>
                <input type="text" class="w-full rounded-md " name="url">
            </div>
        </div>
    </form>
</x-app-layout>
