<?php

namespace Tanel\Response;

use ArrayIterator;
use Countable;
use InvalidArgumentException;

class ResponseStatus implements Countable, IteratorAggregate
{
    /**
     * Create a new Httpstatus Instance.
     *
     * @throws InvalidArgumentException if the collection is not valid
     */
    public function __construct($statusArray = [])
    {

    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->httpStatus);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->httpStatus);
    }
}