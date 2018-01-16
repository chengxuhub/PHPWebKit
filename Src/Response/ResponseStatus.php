<?php

namespace Tanel\PHPWebKit\Response;

use ArrayIterator;
use Countable;
use InvalidArgumentException;

class ResponseStatus implements Countable, IteratorAggregate {
    private $lang = 'chinese';

    /**
     * Create a new Response Instance.
     *
     * @throws InvalidArgumentException if the collection is not valid
     */
    public function __construct($statusArray = [], $configure = []) {
        if (!empty($configure)) {
            $this->setConfigure($configure);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count() {
        return count($this->responseStatus);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator() {
        return new ArrayIterator($this->responseStatus);
    }

    /**
     * 配置解析
     *
     * @param array $configure 配置项
     */
    private function setConfigure($configure) {
        if (isset($configure['lang'])) {
            $this->lang = $configure['lang'];
        }
    }
}