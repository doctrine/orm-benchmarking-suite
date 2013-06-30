<?php

namespace Perf\Orm;

/**
 * @Entity
 */
class OrderItem
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    public $id;
    /**
     * @ManyToOne(targetEntity="Order", inversedBy="items")
     */
    public $order;
    /**
     * @Column
     */
    public $productName;
    /**
     * @Column(type="float")
     */
    public $price;
    /**
     * @Column(type="integer")
     */
    public $count;
}
