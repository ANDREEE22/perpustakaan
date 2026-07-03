@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-end">
        <ul class="flex items-center gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true">
                    <span class="px-3 py-2 text-sm font-medium text-zinc-400 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg cursor-not-allowed">
                        ← Sebelumnya
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       rel="prev"
                       class="px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                        ← Sebelumnya
                    </a>
                </li>
            @endif

            {{-- Dynamic Page Range Calculation --}}
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $range = 2; // 2 halaman sebelum dan sesudah halaman aktif
                
                $start = max($current - $range, 1);
                $end = min($current + $range, $last);
                
                // Adjust jika range tidak cukup 5 halaman
                if ($end - $start < 4) {
                    if ($start == 1) {
                        $end = min(5, $last);
                    } elseif ($end == $last) {
                        $start = max(1, $last - 4);
                    }
                }
            @endphp

            {{-- Page 1 --}}
            @if ($start > 1)
                <li>
                    <a href="{{ $paginator->url(1) }}"
                       class="px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                        1
                    </a>
                </li>
                
                {{-- Ellipsis before range --}}
                @if ($start > 2)
                    <li aria-disabled="true">
                        <span class="px-2 py-2 text-sm text-zinc-400">...</span>
                    </li>
                @endif
            @endif

            {{-- Page Range --}}
            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $current)
                    <li aria-current="page">
                        <span class="px-3 py-2 text-sm font-bold text-white bg-teal-600 dark:bg-teal-700 border border-teal-600 dark:border-teal-700 rounded-lg">
                            {{ $i }}
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->url($i) }}"
                           class="px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                            {{ $i }}
                        </a>
                    </li>
                @endif
            @endfor

            {{-- Last Page --}}
            @if ($end < $last)
                {{-- Ellipsis after range --}}
                @if ($end < $last - 1)
                    <li aria-disabled="true">
                        <span class="px-2 py-2 text-sm text-zinc-400">...</span>
                    </li>
                @endif
                
                <li>
                    <a href="{{ $paginator->url($last) }}"
                       class="px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                        {{ $last }}
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       rel="next"
                       class="px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                        Berikutnya →
                    </a>
                </li>
            @else
                <li aria-disabled="true">
                    <span class="px-3 py-2 text-sm font-medium text-zinc-400 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg cursor-not-allowed">
                        Berikutnya →
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
