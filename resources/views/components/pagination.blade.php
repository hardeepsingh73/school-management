<div class="pagination_wrap d-flex align-items-center justify-content-between px-2">
    @if ($paginator->lastPage() > 1)
        <!-- Results summary -->
        <div class="total-records mb-2">
            <p class="mb-0 text-muted small">
                Showing
                <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                to
                <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                of
                <span class="fw-semibold">{{ $paginator->total() }}</span>
                results
            </p>
        </div>

        <!-- Bootstrap 5 Pagination -->
        <nav aria-label="Pagination">
            <ul class="pagination justify-content-center">

                <!-- Previous Button -->
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() ?? '#' }}" aria-label="Previous"
                        tabindex="{{ $paginator->onFirstPage() ? '-1' : '0' }}">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>

                <!-- Page Numbers -->
                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                <!-- Next Button -->
                <li class="page-item {{ $paginator->onLastPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() ?? '#' }}" aria-label="Next"
                        tabindex="{{ $paginator->onLastPage() ? '-1' : '0' }}">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>

            </ul>
        </nav>
    @endif
</div>
