<?php

class Paginator implements IteratorAggregate
{
    protected array $data = [];
    protected int $totalRecord = 0;
    protected int $perPage;
    protected int $currentPage = 1;
    protected string $baseUrl = '';
    protected string $pageName = 'page';

    public function __construct(array $data, string $baseUrl, int $totalRecord, int $perPage, int $currentPage) {
        $this->data = $data;
        $this->totalRecord = $totalRecord;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;
        $this->baseUrl = $baseUrl;
    }

    public function getIterator(): Traversable {
        return new ArrayIterator($this->data);
    }

    public function total(): int {
        return $this->totalRecord;
    }

    public function currentPage(): int {
        return $this->currentPage;
    }

    public function lastPage(): int {
        return (int) ceil($this->totalRecord / $this->perPage);
    }

    public function paginatorHeader() {
        $header =   '<nav aria-label="Pagination">
                        <ul class="pagination pagination-sm justify-content-end">';
        return $header;
    }

    public function paginatorFooter() {
        $footer = '</ul>
                    </nav>';
        return $footer;
    }

    /**
     * Generate the pagination links
     * @return string
     */
    public function links() {
        $lastPage = $this->lastPage();
        $currentPage = $this->currentPage;
        $baseUrl = $this->baseUrl;
        $pageName = $this->pageName;

        // links
        $links = '';
        // previous page
        $previousPage = $nextPage = '';
        for ($i = 1; $i <= $lastPage; $i++) {
            if ($i == $currentPage) {
                // previous page
                $previousPage = '<li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                        </li>';
                if ($i > 1) {
                    $previousPage = '<li class="page-item">
                                        <a class="page-link" href="' . $baseUrl . '&' . $pageName . '=' . ($i - 1) . '" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>';
                }
                
                // current page
                $links .= '<li class="page-item active"><a class="page-link" href="#"><span class="active">' . $i . '</span></a></li>';

                // next page
                $nextPage = '<li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&raquo;</a>
                            </li>';
                if ($i < $lastPage) {
                        $nextPage = '<li class="page-item">
                                        <a class="page-link" href="' . $baseUrl . '&' . $pageName . '=' . ($i + 1) . '" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>';
                }
            } else {
                $links .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '&' . $pageName . '=' . $i . '">' . $i .'</a></li>';
            }
        }

        $links = $this->paginatorHeader() . $previousPage . $links . $nextPage . $this->paginatorFooter();

        return $links;
    }
}
