<!-- resources/themes/default/views/notifications/index.blade.php -->
<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('client_notification::app.notifications.title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-col">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('client_notification::app.notifications.title')
        </p>

        <div class="flex items-center gap-x-2">
            <!-- Example of a common Bagisto button for actions -->
            <!-- <a href="#" class="primary-button">
                lang('client_notification::app.notifications.mark-all-as-read')
            </a> -->
        </div>
    </div>

    {{-- Main content area with two columns, now always 1/2 - 1/2 split by default --}}
    <div class="mt-8 grid grid-cols-2 gap-6">
        {{-- Column 1: Notifications (Takes 1/2 width by default) --}}
        <div>
            <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
                <div class="relative overflow-x-auto">
                     @include('client_notification::admin.partials.notification-list', [
                                                                    'notifications' => $notifications,
                                                                    'meta' => $meta,
                                                                    'links' => $links
                                                                ])
                </div>
            </div>
        </div>

    <script>
        document.addEventListener('click', function (e) {
            // Phân trang
            const paginationBtn = e.target.closest('.ajax-page');
            if (paginationBtn) {
                e.preventDefault();
                console.log('[Pagination Click] Sự kiện click:', paginationBtn);

                const url = paginationBtn.getAttribute('data-url');
                if (!url) {
                    console.warn('[Pagination Click] Không có data-url');
                    return;
                }

                console.log('[Pagination Click] Đang fetch URL:', url);
                fetchNotifications(url);
                return;
            }

            // Đánh dấu đã đọc (mark as read)
            const readLink = e.target.closest('.notification-read-link');
            if (readLink) {
                e.preventDefault();
                const url = readLink.getAttribute('data-read-url');
                const linkHref = readLink.getAttribute('data-link');
                const notificationId = readLink.getAttribute('data-id');
                if (linkHref) {
                    window.open(linkHref, '_blank');
                }
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                }).then(res => {
                    if (!res.ok) {
                        return;
                    }
                    // ✅ Xóa badge "New"
                    document.getElementById('new-badge-' + notificationId)?.remove();
            
                }).catch(err => {
                    console.error('[Notification] Lỗi đánh dấu đã đọc:', err);
                });
            }

            
        });

        function fetchNotifications(url) {
            console.log('[Pagination Click] Đang fetch URL:', url);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const content = doc.querySelector('.grid-cols-1');
                const pagination = doc.querySelector('.pagination-container')?.parentElement;

                document.querySelector('.grid-cols-1').innerHTML = content?.innerHTML ?? '';
                document.querySelector('.pagination-container')?.parentElement.replaceWith(pagination);
            })
            .catch(err => {
                console.error('[Pagination] AJAX Error:', err);
                alert('Không thể tải trang mới.');
            });
        }

    </script>

        {{-- Column 2: Contact Form (Takes 1/2 width by default) --}}
        
    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">

            @if (session()->has('success'))
                <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            @include('client_notification::admin.partials.notification-token', [
                                                'domain_tokens' => $domain_tokens,
                                            ])

    </div>

        
    </div>
</x-admin::layouts>
