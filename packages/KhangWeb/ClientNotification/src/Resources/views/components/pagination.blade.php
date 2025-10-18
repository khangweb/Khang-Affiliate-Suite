@if ($meta && $links)
    <nav class="flex items-center justify-center border-t border-gray-200 px-4 py-3 dark:border-gray-800" aria-label="Pagination">
        {{-- Changed -space-x-px to space-x-2 for spacing between elements --}}
        <ul class="inline-flex space-x-2 text-sm pagination-container">
            {{-- Previous Button --}}
            <li>
                <button
                    data-url="{{ request()->fullUrlWithQuery(['page' => $meta['current_page'] - 1]) }}"
                    class="pagination-link ajax-page {{ !$links['prev'] ? '!cursor-not-allowed opacity-50' : '' }} rounded-l-md"
                    @if (!$links['prev']) disabled @endif
                >
                    &laquo; Prev {{-- Changed from __('') to 'Prev' --}}
                </button>
            </li>

            {{-- Page Status --}}
            <li>
                {{-- Removed border-l-0 border-r-0 as space-x-2 now handles spacing --}}
                <span class="pagination-link !cursor-default">
                    {{ $meta['current_page'] }} / {{ $meta['last_page'] }}
                </span>
            </li>

            {{-- Next Button --}}
            <li>
                <button
                    data-url="{{ request()->fullUrlWithQuery(['page' => $meta['current_page'] + 1]) }}"
                    class="pagination-link ajax-page {{ !$links['next'] ? '!cursor-not-allowed opacity-50' : '' }} rounded-r-md"
                    @if (!$links['next']) disabled @endif
                >
                    Next &raquo; {{-- Changed from __('') to 'Next' --}}
                </button>
            </li>
        </ul>
    </nav>
@endif


