<x-admin::layouts>
    <x-slot:title>
        @lang('clientmessage::app.admin.messages.view.title')
    </x-slot:title>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap mb-[20px]">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('clientmessage::app.admin.messages.view.title_from', ['name' => $message->contact_person])
        </p>

        <div class="flex gap-x-[10px] items-center">
            <a href="{{ route('admin.client_messages.index') }}">
                Back
            </a>
        </div>
    </div>

    <x-admin::form>
        {{-- Row 1: Client Message Details (2 Columns) --}}
        <div class="flex gap-4 justify-between items-start max-lg:flex-wrap mb-4">
            {{-- Left Column (60%): Message Content, Images, Videos --}}
            <div class="flex flex-col gap-2 w-full lg:w-[calc(60%-0.5rem)]">
                <div class="p-4 bg-white dark:bg-gray-900 rounded-lg shadow">
   

                    {{-- Nội dung --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('clientmessage::app.admin.messages.view.message')
                        </x-admin::form.control-group.label>
                        <div class="form-control bg-gray-100 dark:bg-gray-800 rounded px-3 py-2 whitespace-pre-wrap text-gray-900 dark:text-white">
                            {{ $message->message }}
                        </div>
                    </x-admin::form.control-group>

                    {{-- Hình ảnh --}}
                    @if (!empty($message->image_urls))
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('clientmessage::app.admin.messages.view.images')
                            </x-admin::form.control-group.label>
                            <div class="form-control">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($message->image_urls as $image)
                                        <a href="{{ $image }}" target="_blank">
                                            <img src="{{ $image }}" class="w-24 h-24 object-cover rounded shadow" />
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </x-admin::form.control-group>
                    @endif

                    {{-- Video --}}
                    @if (!empty($message->video_urls))
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('clientmessage::app.admin.messages.view.videos')
                            </x-admin::form.control-group.label>
                            <div class="form-control">
                                <ul class="list-disc pl-4">
                                    @foreach ($message->video_urls as $video)
                                        <li><a href="{{ $video }}" class="text-blue-600 hover:underline" target="_blank">{{ $video }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </x-admin::form.control-group>
                    @endif
                </div>
            </div>

            {{-- Right Column (40%): Sender Info, Subject, Timestamps, Status --}}
            <div class="flex flex-col gap-2 w-full lg:w-[calc(40%-0.5rem)]">
                <div class="p-4 bg-white dark:bg-gray-900 rounded-lg shadow">

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('clientmessage::app.admin.messages.view.request_id')
                        </x-admin::form.control-group.label>
                        <div class="form-control bg-gray-100 dark:bg-gray-800 rounded px-3 py-2 text-gray-800 dark:text-white">
                            {{ $message->request_id }}
                        </div>
                    </x-admin::form.control-group>

                    {{-- Tên người gửi --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('clientmessage::app.admin.messages.view.sender_name')
                        </x-admin::form.control-group.label>
                        <div class="form-control bg-gray-100 dark:bg-gray-800 rounded px-3 py-2 text-gray-800 dark:text-white">
                            {{ $message->contact_person }}
                        </div>
                    </x-admin::form.control-group>

                    {{-- Email --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('clientmessage::app.admin.messages.view.email')
                        </x-admin::form.control-group.label>
                        <div class="form-control bg-gray-100 dark:bg-gray-800 rounded px-3 py-2">
                            <a href="mailto:{{ $message->email }}" class="text-blue-600 hover:underline">{{ $message->email }}</a>
                        </div>
                    </x-admin::form.control-group>

                    {{-- Giới tính --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('clientmessage::app.admin.messages.view.gender')
                        </x-admin::form.control-group.label>
                        <div class="form-control bg-gray-100 dark:bg-gray-800 rounded px-3 py-2">
                            @lang('clientmessage::app.admin.messages.view.gender_options.' . $message->gender)
                        </div>
                    </x-admin::form.control-group>

                    {{-- Chủ đề --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('clientmessage::app.admin.messages.view.subject')
                        </x-admin::form.control-group.label>
                        <div class="form-control bg-gray-100 dark:bg-gray-800 rounded px-3 py-2">
                            {{ $message->subject }}
                        </div>
                    </x-admin::form.control-group>

                    {{-- Thời gian nhận --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('clientmessage::app.admin.messages.view.received_at')
                        </x-admin::form.control-group.label>
                        <div class="form-control bg-gray-100 dark:bg-gray-800 rounded px-3 py-2">
                            {{ $message->created_at->format('d/m/Y H:i') }}
                        </div>
                    </x-admin::form.control-group>

                    {{-- Trạng thái phản hồi --}}
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('clientmessage::app.admin.messages.view.status')
                        </x-admin::form.control-group.label>
                        <div class="form-control">

                            @php
                                $status = $message->status;
                                $backgroundColor = ''; // Biến để lưu màu nền
                                $textColor = '';       // Biến để lưu màu chữ
                                $text = '';

                                switch ($status) {
                                    case 'pending':
                                        $backgroundColor = '#008000'; // Xanh lá cây đậm - Tượng trưng cho đã nhận/hoàn thành
                                        $textColor = '#FFFFFF';      // Chữ trắng để tương phản với nền xanh đậm
                                        $text = __('clientmessage::app.admin.status_options.pending');
                                        break;
                                    case 'received': // Đã sửa lỗi chính tả
                                        $backgroundColor = '#FFD700'; // Vàng đậm - Tương phản với xanh và đỏ
                                        $textColor = '#333333';      // Chữ đen hoặc xám đậm để dễ đọc trên nền vàng
                                        $text = __('clientmessage::app.admin.status_options.received');
                                        break;
                                    case 'replied':
                                        $backgroundColor = '#DC143C'; // Đỏ tươi - Tượng trưng cho hành động đã thực hiện (trả lời)
                                        $textColor = '#FFFFFF';      // Chữ trắng để tương phản với nền đỏ
                                        $text = __('clientmessage::app.admin.status_options.replied');
                                        break;
                                    default:
                                        $backgroundColor = '#CCCCCC'; // Xám nhạt cho trạng thái không xác định
                                        $textColor = '#333333';
                                        $text = ucfirst($status);
                                        break;
                                }
                            @endphp

            {!! "<button type='button' style='background-color: " . $backgroundColor . "; color: " . $textColor . "; padding: 4px 12px; font-size: 0.875rem; font-weight: 600; border-radius: 9999px; border: none; cursor: default; white-space: nowrap;'>" . $text . "</button>" !!}
   

                        </div>
                    </x-admin::form.control-group>
                </div>
            </div>
        </div>
       
    </x-admin::form>
</x-admin::layouts>
