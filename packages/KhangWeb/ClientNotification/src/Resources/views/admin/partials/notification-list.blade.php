@php
    use Carbon\Carbon;
@endphp


@if (empty($notifications))
    <div class="flex items-center justify-center p-5">
        <p class="text-base text-gray-400 dark:text-gray-500">
            @lang('client_notification::app.notifications.no-notifications-found')
        </p>
    </div>
@else
    <div class="grid grid-cols-1 gap-4 p-4" id="notification-list-container">
        @foreach ($notifications as $notification)
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900 flex items-start gap-x-4">
                @if (!empty($notification['thumbnail']))
                    <img src="{{ $notification['thumbnail'] }}"
                         onerror="this.onerror=null;this.src='https://placehold.co/64x64/E0E0E0/333333?text=No+Img';"
                         alt="Thumbnail"
                         class="w-16 h-16 rounded-md object-cover flex-shrink-0">
                @else
                    <img src="https://placehold.co/64x64/E0E0E0/333333?text=No+Img"
                         alt="No Thumbnail"
                         class="w-16 h-16 rounded-md object-cover flex-shrink-0">
                @endif
                {{-- Changed: Added flex flex-col and w-full to make it a vertical flex container that takes full width --}}
                <div class="flex-grow flex flex-col w-full">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ $notification['title'] ?? 'Title Notication' }}
                        </h3>
                        @if (!($notification['is_read'] ?? true))
                            <span id="new-badge-{{$notification['id']}}" class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                @lang('client_notification::app.notifications.new')
                            </span>
                        @endif
                    </div>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ $notification['content'] ?? 'Detailed content of the notice.' }}
                    </p>
                    {{-- Changed: Added self-end to align this div to the right --}}
                    <div class="mt-3 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>{{ Carbon::parse($notification['created_at'])->diffForHumans() }}</span>
                        <a href="#"
                            data-read-url="{{ route('admin.client-notification.read', $notification['id']) }}"
                            data-link="{{ $notification['link'] }}"
                            data-id="{{ $notification['id'] }}"
                            class="notification-read-link text-blue-600 hover:underline dark:text-blue-400"
                            target="_blank">
                            @lang('client_notification::app.notifications.view-details')
                        </a>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="border-t border-gray-200 p-4 dark:border-gray-800">
        @include('client_notification::components.pagination', ['links' => $links, 'meta' => $meta])
    </div>
@endif
