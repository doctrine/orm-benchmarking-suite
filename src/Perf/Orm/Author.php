<?php

namespace Perf\Orm;

/**
 * @Entity
 */
class Author
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
    public $firstName;

    /**
     * @Column(length=128)
     */
    public $lastName;

    /**
     * @Column(length=128, nullable=true)
     */
    public $email;
}
