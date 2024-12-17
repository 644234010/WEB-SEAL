<div class="pagination-container">
    <ul class="pagination">
        @if ($orders->currentPage() > 1)
            <li class="page-item"><a class="page-link" href="{{ $orders->previousPageUrl() }}">« Previous</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">« Previous</span></li>
        @endif

        @for ($i = 1; $i <= $orders->lastPage(); $i++)
            @if ($i == 1 || $i == $orders->lastPage() || ($i >= $orders->currentPage() - 1 && $i <= $orders->currentPage() + 1))
                @if ($i == $orders->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a></li>
                @endif
            @elseif ($i == $orders->currentPage() - 2 || $i == $orders->currentPage() + 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif
        @endfor

        @if ($orders->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $orders->nextPageUrl() }}">Next »</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">Next »</span></li>
        @endif
    </ul>
</div>