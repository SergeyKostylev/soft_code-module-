<?php

namespace Model\Pagination;

class Pagination implements \Iterator
{
    public $buttons = array();

    public function __construct(array $options = array('itemsCount' => 257, 'itemsPerPage' => 10, 'currentPage' => 1))
    {
        extract($options);

        /** @var int $currentPage */
        if (!$currentPage) {
            return;
        }

        /** @var int $pagesCount
         *  @var int $itemsCount
         *  @var int $itemsPerPage
         */
        $pagesCount = ceil($itemsCount / $itemsPerPage);

        if ($pagesCount == 1) {
            return;
        }

        /** @var int $currentPage */
        if ($currentPage > $pagesCount) {
            $currentPage = $pagesCount;
        }

        $this->buttons[] = new Button($currentPage - 1, $currentPage > 1, 'Previous');

        for ($i = 1; $i <= $pagesCount; $i++) {
            $active = $currentPage != $i;
            $this->buttons[] = new Button($i, $active);
        }

        $this->buttons[] = new Button($currentPage + 1, $currentPage < $pagesCount, 'Next');
    }

    public function rewind()
    {
        reset($this->buttons);
    }

    public function current()
    {
        $var = current($this->buttons);

        return $var;
    }

    public function key()
    {
        $var = key($this->buttons);

        return $var;
    }

    public function next()
    {
        $var = next($this->buttons);

        return $var;
    }

    public function valid()
    {
        $key = $this->key();
        $var = ($key !== null && $key !== false);

        return $var;
    }
}