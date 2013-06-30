<?php
/**
 * @Entity
 */
class Book
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue
     */
    public $id;

    /**
     * @Column(length=128)
     */
    public $title;

    /**
     * @Column(length=24)
     */
    public $isbn;

    /**
     * @Column(type="decimal")
     */
    public $price;

    /**
     * @ManyToOne(targetEntity="Author")
     */
    public $author;
}