<?php

class Paginator implements IteratorAggregate
{
    protected array $data = [];
    protected int $totalPage = 0;
    protected int $perPage;
    protected int $currentPage = 1;
    protected string $baseUrl = '';
    protected string $pageName = 'page';

    public function __construct(array $data, string $baseUrl, int $totalPage, int $perPage, int $currentPage) {
        $this->data = $data;
        $this->totalPage = $totalPage;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;
        $this->baseUrl = $baseUrl;
    }

    public function getIterator(): Traversable {
        return new ArrayIterator($this->data);
    }

    /**
     * Generate the pagination links (string)
     * @return string
     * @example
     * << 1 [2] 3 ... 5 6 7 >>
     * << 1 2 [3] 4 5 6 7 >>
     * << 1 2 3 ... 5 [6] 7 >>
     * << 1 2 3 4 [5] 6 ... 9 10 11 >>
     * << 1 2 3 ... 5 [6] 7 ... 9 10 11 >>
     * << 1 2 3 ... 6 [7] 8 9 10 11 >>
     */
    public function links() {
        $lastPage = $this->totalPage;
        $currentPage = $this->currentPage;
        $baseUrl = $this->baseUrl;
        $pageName = $this->pageName;

        // nếu không có dữ liệu thì không hiển thị phân trang
        if ($lastPage <= 1) {
            return '';
        }

        // phân chia các page thành 3 phần để tránh unlimited pagination
        $beginPartPagination = 3; // số trang phần đầu
        $endPartPagination = 3; // số trang phần cuối
        $beforeCurrentPage = 1; // số trang trước trang hiện tại
        $afterCurrentPage = 1; // số trang sau trang hiện tại
        $middlePartPagination = $beforeCurrentPage + 1 + $afterCurrentPage; // số trang phần giữa
        $startMiddlePart = max($beginPartPagination + 1, $currentPage - $beforeCurrentPage); // bắt đầu phần giữa
        $endMiddlePart = min($lastPage - $endPartPagination, $currentPage + $afterCurrentPage); // kết thúc phần giữa

        // dấu phân cách của tham số page
        $separator = strpos($baseUrl, '?') === false ? '?' : '&';

        // kiểm tra nếu biến page lớn hơn tổng số trang
        if ($currentPage > $lastPage) {
            $currentPage = $lastPage;
        }

        // links
        $links = $this->getPaginationTemplate(compact('lastPage', 'currentPage', 'baseUrl', 'pageName', 'separator', 'beginPartPagination', 'endPartPagination', 'beforeCurrentPage', 'afterCurrentPage', 'middlePartPagination', 'startMiddlePart', 'endMiddlePart'));

        return $links;
    }

    private function getPaginationTemplate($data = []) {
        if (!empty($data)) {
            extract($data);
        }

        $contentView = null;
        
        // layouts sử dụng template
        if (file_exists('core/views/paginate.php')) {
            $contentView = file_get_contents('core/views/paginate.php');
        } 

        $template = new Template();
        return $template->run($contentView, $data);
    }
}
