@if ($users->lastPage() > 1)
    <ul class="pagination">
        @if ($users->onFirstPage())
            <li class="page-item disabled"><span class="page-link">ย้อนกลับ</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $users->previousPageUrl() }}"
                    data-page="{{ $users->currentPage() - 1 }}">ย้อนกลับ</a></li>
        @endif

        @for ($i = 1; $i <= $users->lastPage(); $i++)
            @if ($i == 1 || $i == $users->lastPage() || ($i >= $users->currentPage() - 1 && $i <= $users->currentPage() + 1))
                @if ($i == $users->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $users->url($i) }}"
                            data-page="{{ $i }}">{{ $i }}</a></li>
                @endif
            @elseif ($i == $users->currentPage() - 2 || $i == $users->currentPage() + 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif
        @endfor

        @if ($users->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $users->nextPageUrl() }}"
                    data-page="{{ $users->currentPage() + 1 }}">ถัดไป</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">ถัดไป</span></li>
        @endif
    </ul>
@endif
