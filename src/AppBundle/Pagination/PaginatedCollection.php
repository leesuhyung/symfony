<?php
namespace AppBundle\Pagination;

use JMS\Serializer\Annotation as Serializer;

class PaginatedCollection
{
    /**
     * 아이템 리스트
     * @Serializer\Type("array")
     * @Serializer\Groups({"list"})
     */
    private $items;

    /**
     * 총 아이템 개수
     * @Serializer\Type("integer")
     * @Serializer\Groups({"list"})
     */
    private $total;

    /**
     * 현재 페이지의 아이템 개수
     * @Serializer\Type("integer")
     * @Serializer\Groups({"list"})
     */
    private $count;

    /**
     * 페이지 링크
     * @Serializer\Type("array")
     * @Serializer\Groups({"list"})
     */
    private $_links = [];

    public function addLink($ref, $url)
    {
        $this->_links[$ref] = $url;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
        $this->count = count($items);
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }


}