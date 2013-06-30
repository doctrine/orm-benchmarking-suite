<?php

namespace Perf\Orm;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="orders")
 */
class Order
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    public $id;

    /**
     * @OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"persist", "remove"})
     */
    public $items;

    /**
     * @Column(type="datetime")
     */
    public $created;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->created = new \DateTime;
    }

    public function addItem($productName, $price, $count = 1)
    {
        $item = new OrderItem();
        $item->order = $this;
        $item->productName = $productName;
        $item->price = $price;
        $item->count = $count;

        $this->items[] = $item;
    }
}
