<div>
    <span>Showing</span>
    <span>{{ $paginator->firstItem() }}</span>
    <span>to</span>
    <span>{{ $paginator->lastItem() }}</span>
    <span>of</span>
    <span>{{ $paginator->total() }}</span>
    <span>results</span>
</div>

<div class="pagination">
    @if ($paginator->onFirstPage())
        <button disabled aria-disabled="true" aria-label="@lang('pagination.previous')">&lsaquo;</button>
    @else
        <button rel="prev" aria-label="@lang('pagination.previous')" onclick="window.location.href = '{{ $paginator->previousPageUrl() }}'">&lsaquo;</button>
    @endif
    
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <button class="active" aria-current="page">{{ $page }}</button>
                @else
                    <button onclick="window.location.href = '{{ $url }}'">{{ $page }}</button>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <button rel="next" aria-lang="@lang('pagination.next')" onclick="window.location.href = '{{ $paginator->nextPageUrl() }}'">&rsaquo;</button>
    @else
        <button disabled aria-disabled="true" aria-label="@lang('pagination.next')">&rsaquo;</button>
    @endif
</div>
