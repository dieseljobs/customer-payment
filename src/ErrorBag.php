<?php namespace TheLHC\CustomerPayment;

use IteratorAggregate;
use ArrayIterator;

class ErrorBag implements IteratorAggregate
{

    /**
     * The errors
     *
     * @var array
     */
    protected $errors;

    /**
     * Create new instance with errors
     *
     * @param array $errors
     */
    public function __construct($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Overload's IteratorAggregate getIterator method
     * Default behavior when class instance is used as array
     *
     * @return array
     */
    public function getIterator()
    {
        return new ArrayIterator($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
