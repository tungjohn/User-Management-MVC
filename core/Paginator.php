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

    public function lastPage(): int {
        return (int) ceil($this->totalRecord / $this->perPage);
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

        // nếu không có dữ liệu thì không hiển thị phân trang
        if ($lastPage <= 1) {
            return '';
        }

        // dấu phân cách của tham số page
        $separator = strpos($baseUrl, '?') === false ? '?' : '&';

        // kiểm tra nếu biến page lớn hơn tổng số trang
        if ($currentPage > $lastPage) {
            $currentPage = $lastPage;
        }

        // links
        $links = $this->getPaginationTemplate(compact('lastPage', 'currentPage', 'baseUrl', 'pageName', 'separator'));

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
