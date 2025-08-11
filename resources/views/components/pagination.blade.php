@if ($paginator->lastPage() > 1)
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 pagination_wrap">

        <!-- Total Records -->
        <div class="total-records small text-muted">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </div>

        <!-- Pagination Links -->
        <nav aria-label="Pagination Navigation">
            <ul class="pagination mb-0">

                {{-- Previous Page Link --}}
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() ?? '#' }}"
                        tabindex="{{ $paginator->onFirstPage() ? '-1' : '0' }}"
                        aria-disabled="{{ $paginator->onFirstPage() ? 'true' : 'false' }}" aria-label="@lang('pagination.previous')">
                        &laquo;
                    </a>
                </li>

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}"
                                    @if ($page == $paginator->currentPage()) aria-current="page" @endif>
                                    {{ $page }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                <li class="page-item {{ $paginator->onLastPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() ?? '#' }}"
                        tabindex="{{ $paginator->onLastPage() ? '-1' : '0' }}"
                        aria-disabled="{{ $paginator->onLastPage() ? 'true' : 'false' }}"
                        aria-label="@lang('pagination.next')">
                        &raquo;
                    </a>
                </li>
            </ul>
        </nav>
    </div>
@endif
