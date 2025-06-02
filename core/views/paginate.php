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

        <!-- beginPartPagination -->
        <!-- 1 2 3 -->
        @if ($lastPage > $beginPartPagination)
            @for ($i = 1; $i <= $beginPartPagination; $i++)
                @if ($i == $currentPage)
                    <li class="page-item active">
                        <a class="page-link" href="#"><span class="active">{{ $i }}</span></a>
                    </li>
                @else
                    @if ($i == 1) 
                        <li class="page-item">
                            <a class="page-link" href="{{ $baseUrl }}">{{ $i }}</a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $baseUrl . $separator . $pageName . '=' . $i }}">{{ $i }}</a>
                        </li>
                    @endif
                @endif
            @endfor
        @else 
            @for ($i = 1; $i <= $lastPage; $i++)
                @if ($i == $currentPage)
                    <li class="page-item active">
                        <a class="page-link" href="#"><span class="active">{{ $i }}</span></a>
                    </li>
                @else
                    @if ($i == 1) 
                        <li class="page-item">
                            <a class="page-link" href="{{ $baseUrl }}">{{ $i }}</a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $baseUrl . $separator . $pageName . '=' . $i }}">{{ $i }}</a>
                        </li>
                    @endif
                @endif
            @endfor
        @endif

        <!-- 1 2 3 ... 5 [6] [7] 8 9 10 11 -->
        <!-- [1] [2] 3 ... 5 [6] [7] -->
        @if ($lastPage > $beginPartPagination + $endPartPagination && ($endMiddlePart <= $beginPartPagination || $startMiddlePart > $beginPartPagination + 1))
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
        @endif
        
        <!-- middlePartPagination -->
        <!-- 1 2 3 ... 5 [6] 7 ... 9 10 11 -->
        <!-- 1 2 3 ... 6 [7] 8 9 10 11 -->
        <!-- 1 2 3 [4] 5 ... 9 10 11 -->
        <!-- 1 2 [3] [4] [5] 6 7 -->
        @if ($lastPage > $beginPartPagination + $endPartPagination && $startMiddlePart > $beginPartPagination && $endMiddlePart <= $lastPage - $endPartPagination)
            @for ($i = $startMiddlePart; $i <= $endMiddlePart; $i++)
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
        @endif
        
        <!-- 1 2 3 ... 5 [6] 7 ... 9 10 11 -->
         <!-- 1 2 3 4 [5] 6 ... 9 10 11 -->
        <!-- 1 2 3 [4] 5 ... 9 10 11 -->
        <!-- 1 2 [3] 4 ... 6 7 8 -->
        @if ($lastPage >= $beginPartPagination + 1 + 1 + $endPartPagination && $endMiddlePart < $lastPage - $endPartPagination && $startMiddlePart >= $beginPartPagination + 1 && $currentPage >= $beginPartPagination)
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
        @endif

        <!-- endPartPagination -->
        <!-- 10 11 12 -->
        @if ($lastPage > $beginPartPagination + $endPartPagination)
            @for ($i = $lastPage - $endPartPagination + 1; $i <= $lastPage; $i++)
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
        @else
            @for ($i = $beginPartPagination + 1; $i <= $lastPage; $i++)
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
        @endif
        
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
