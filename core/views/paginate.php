<nav aria-label="Pagination">
    <ul class="pagination pagination-sm justify-content-end">
        @if ($currentPage > 1)
            <li class="page-item">
                <a class="page-link" href="{{ $baseUrl . $separator . $pageName . '=' . ($currentPage - 1) }}" aria-label="Previous">
                    <span aria-hidden="true">«</span>
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">«</span>
                </a>
            </li>
        @endif
        @for ($i = 1; $i <= $lastPage; $i++)
            @if ($i == $currentPage)
                <li class="page-item active">
                    <a class="page-link" href="#"><span class="active">{{ $i }}</span></a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $baseUrl . $separator . $pageName . '=' . $i }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        @if ($currentPage < $lastPage)
            <li class="page-item">
                <a class="page-link" href="{{ $baseUrl . $separator . $pageName . '=' . ($currentPage + 1) }}" aria-label="Next">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
