<x-app-layout>
    <x-slot name="header">
        {{ __('Itinearary Edit') }}
    </x-slot>
    <form action="{{ route('itinearary.update', $item->id) }}" class="w-full pb-[100px] " method="post">
        @csrf
        <div class="w-full px-[200px] pb-[20px]">
            <button class=" float-right bg-green-600 px-[10px] py-[6px] text-white rounded-md">Update</button>
            <a href="{{ route('itinearary.list') }}"
                class=" float-left bg-blue-600 px-[15px] py-[6px] text-white rounded-md">List</a>
        </div>
        @if (Session::has('success'))
            <div class="text-green-500 text-sm mt-1 p-[20px]" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif
        <div class="w-full h-auto flex flex-col justify-center items-center gap-y-5 pb-[50px]">
            <div class="w-1/2">
                <label class="text-[20px] font-semibold p-[5px]">Name</label>
                <input type="text" class="w-full " name="name" value="{{ $item->name }}">
            </div>
            <div class="w-1/2">
                <label class="text-[20px] font-semibold p-[5px]">Email</label>
                <input type="email" class="w-full " name="email" value="{{ $item->email }}">
            </div>
        </div>
        @php
            $data = json_decode($item->itinerary, true);
        @endphp

        @foreach ($data as $index => $key)
            <div id="itinerary" class="border-[2px] py-[50px]">
                <div class="itinerary-box w-full h-auto flex flex-col justify-center items-center gap-y-5 ">
                    <div class="w-1/2">
                        <label class="text-[20px] font-semibold p-[5px]">Day</label>
                        <input type="text" class="w-full " name="itinerary[{{ $index }}][day]"
                            value="{{ $key['day'] }}">
                    </div>
                    <div class="w-1/2">
                        <label class="text-[20px] font-semibold p-[5px]">Title</label>
                        <input type="text" class="w-full " name="itinerary[{{ $index }}][title]"
                            value="{{ $key['title'] }}">
                    </div>
                    <div class="w-1/2">
                        <label class="text-[20px] font-semibold p-[5px]">Description</label>
                        <textarea name="itinerary[{{ $index }}][description]" id="description-{{ $index }}" cols="30"
                            rows="10" class="w-full">{!! $key['description'] !!}</textarea>
                    </div>

                </div>
                <div class="w-full px-[280px]" id="add-btn">
                    <button class="remove-btn bg-red-600 px-[10px] py-[6px] text-white rounded-md">
                        Remove
                    </button>
                    <button class="add-btn btn float-right bg-blue-600 px-[15px] py-[6px] text-white rounded-md">Add
                    </button>

                </div>
            </div>
        @endforeach
    </form>

    @section('scripts')
        <script>
            $(document).ready(function() {

                let index = {{ count(json_decode($item->itinerary, true)) }};

                // 2. Initialize TinyMCE for all existing description fields
                for (let i = 0; i < index; i++) {
                    tinymce.init({
                        selector: `#description-${i}`,
                        height: 300,
                        plugins: 'image link media code lists fullscreen paste autolink preview print preview paste importcss searchreplace autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help quickbars emoticons',
                        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link image | code',
                        menubar: true,
                        branding: false
                    });
                }

                // Hide remove button initially if only 1 box
                if ($('.itinerary-box').length <= 1) {
                    $('.remove-btn').hide();
                }

                $(".add-btn").click(function(event) {
                    event.preventDefault();

                    const newGroup = `
                        <div class="itinerary-box w-full h-auto flex flex-col py-[20px] justify-center items-center gap-y-5 ">
                <div class="w-1/2">
                    <label class="text-[20px] font-semibold p-[5px]">Day</label>
                    <input type="text" class="w-full " name="itinerary[${index}][day]">
                </div>
                <div class="w-1/2">
                    <label class="text-[20px] font-semibold p-[5px]">Title</label>
                    <input type="text" class="w-full " name="itinerary[${index}][title]">
                </div>
                <div class="w-1/2">
                    <label class="text-[20px] font-semibold p-[5px]">Description</label>
                    <textarea name="itinerary[${index}][description]" id="description-${index}" cols="30" rows="10" class="w-full"></textarea>
                </div>

            </div>

                    `
                    // ✅ Append after the last itinerary box
                    $('.itinerary-box').last().after(newGroup);

                    tinymce.init({
                        selector: `#description-${index}`,
                        height: 500,
                        plugins: 'image link media code lists fullscreen paste autolink preview print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
                        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link image  | code',
                        menubar: true,
                        branding: false
                    });

                    index++;

                    if ($('.itinerary-box').length > 1) {
                        $('.remove-btn').show();
                    }


                });


                $('.remove-btn').click(function() {
                    event.preventDefault();

                    let boxes = $('.itinerary-box');

                    if (boxes.length > 1) {
                        const lastBox = boxes.last();
                        const textareaId = lastBox.find('textarea').attr('id');

                        if (tinymce.get(textareaId)) {
                            tinymce.get(textareaId).remove();
                        }

                        lastBox.remove();
                    }


                    // Hide remove if only one left
                    if ($('.itinerary-box').length === 1) {
                        $('.remove-btn').hide();
                    }

                });
            });
        </script>
    @endsection

</x-app-layout>
