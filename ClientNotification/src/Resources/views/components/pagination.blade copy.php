@if ($meta && $links)
    <nav class="border-t border-gray-200 px-4 py-3 dark:border-gray-800" aria-label="Pagination">
        <ul class="inline-flex -space-x-px text-sm pagination-container">
            {{-- Previous --}}
            @if ($links['prev'])
                <li>
                    <button
                        data-url="{{ request()->url() }}?page={{ $meta['current_page'] - 1 }}"
                        class="pagination-link ajax-page"
                    >
                        &laquo;
                    </button>
                </li>
            @endif

            {{-- Page status --}}
            <li class="disabled">
                <span class="pagination-link !cursor-default">
                    {{ $meta['current_page'] }} / {{ $meta['last_page'] }}
                </span>
            </li>

            {{-- Next --}}
            @if ($links['next'])
                <li>
                    <button
                        data-url="{{ request()->url() }}?page={{ $meta['current_page'] + 1 }}"
                        class="pagination-link ajax-page"
                    >
                        &raquo;
                    </button>
                </li>
            @endif
        </ul>
    </nav>
@endif
