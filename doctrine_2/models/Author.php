<?php

/** @Entity */
class Author
{
    /** @Id @Column(type="integer") @GeneratedValue */
    public $id;
    /** @Column(length=128) */
    public $firstName;
    /** @Column(length=128) */
    public $lastName;
    /** @Column(length=128, nullable=true) */
    public $email;
    /** @OneToMany(targetEntity="Book", mappedBy="author") */
    public $books;
}