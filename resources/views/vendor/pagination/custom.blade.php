@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
    @endphp

    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-end">
        <ul class="flex items-center gap-2">
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

            {{-- First Page Link --}}
            @if ($current > 1)
                <li>
                    <a href="{{ $paginator->url(1) }}" class="px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                        1
                    </a>
                </li>
            @endif

            {{-- Previous Page Links --}}
            @if ($current > 2)
                <li aria-disabled="true">
                    <span class="px-2 py-2 text-sm text-zinc-400">...</span>
                </li>
            @endif

            {{-- Current Page --}}
            @if ($current > 1 || $last > 1)
                <li aria-current="page">
                    <span class="px-3 py-2 text-sm font-bold text-white bg-teal-600 dark:bg-teal-700 border border-teal-600 dark:border-teal-700 rounded-lg">
                        {{ $current }}
                    </span>
                </li>
            @endif

            {{-- Next Page Links --}}
            @if ($current < $last - 1)
                <li aria-disabled="true">
                    <span class="px-2 py-2 text-sm text-zinc-400">...</span>
                </li>
            @endif

            {{-- Last Page Link --}}
            @if ($current < $last)
                <li>
                    <a href="{{ $paginator->url($last) }}" class="px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
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
