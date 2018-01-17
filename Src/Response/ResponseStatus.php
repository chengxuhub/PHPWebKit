<?php

namespace Tanel\PHPWebKit\Response;

use ArrayIterator;
use Countable;
use InvalidArgumentException;

class ResponseStatus implements Countable, IteratorAggregate {
    /**
     * 响应错误码范围
     */
    const MINIMUM = 0;
    const MAXIMUM = 100000;

    private $lang           = 'zh-cn';
    private $langpath       = '';
    private $responseStatus = [];

    /**
     * Create a new Response Instance.
     *
     * @throws InvalidArgumentException if the collection is not valid
     */
    public function __construct($statusArray = [], $configure = []) {
        //配置
        if (!empty($configure)) {
            $this->setConfigure($configure);
        }

        //加载语言包
        $this->autoLoadLocalLang($this->lang);

        //合并信息追加的语言
        foreach ($this->filterCollection($statusArray) as $code => $text) {
            $this->mergeresponseStatus($code, $text);
        }
    }

    /**
     * 配置解析
     *
     * @param array $configure 配置项
     */
    private function setConfigure($configure) {
        $this->lang = isset($configure['lang']) ? $configure['lang'] : $this->lang;
        //语言包文件路径
        if (isset($configure['langPath'])) {
            $this->langPath = $configure['langPath'];
        } else {
            $this->langPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Lang' . DIRECTORY_SEPARATOR;
        }
    }

    /**
     * 加载语言包
     * @param  string $lang zh-cn
     * @return array
     */
    private function autoLoadLocalLang($lang) {
        if (file_exists($langFile = $this->langPath . $lang . ".php")) {
            $this->responseStatus = require $langFile;
        } else {
            throw new RuntimeException('language is not found');
        }
    }

    /**
     * 检查过滤状态码范围
     * Filter a HTTP Status code.
     *
     * @param int $code
     *
     * @throws InvalidArgumentException if the HTTP status code is invalid
     *
     * @return int
     */
    protected function filterStatusCode($code) {
        $code = filter_var($code, FILTER_VALIDATE_INT, ['options' => [
            'min_range' => self::MINIMUM,
            'max_range' => self::MAXIMUM,
        ]]);
        if (!$code) {
            throw new InvalidArgumentException(
                'The submitted code must be a positive integer between ' . self::MINIMUM . ' and ' . self::MAXIMUM
            );
        }

        return $code;
    }

    /**
     * 检查过滤非Collection数组
     * Filter a Collection array.
     *
     * @param Traversable|array $collection
     *
     * @throws InvalidArgumentException if the collection is not valid
     *
     * @return Traversable|array
     */
    protected function filterCollection($collection) {
        if (!$collection instanceof Traversable && !is_array($collection)) {
            throw new InvalidArgumentException('The collection must be a Traversable object or an array');
        }

        return $collection;
    }

    /**
     * 检查过滤状态说明文字
     * Filter a Reason Phrase.
     *
     * @param string $text
     *
     * @throws InvalidArgumentException if the reason phrase is not a string
     * @throws InvalidArgumentException if the reason phrase contains carriage return characters
     *
     * @see http://tools.ietf.org/html/rfc2616#section-6.1.1
     *
     * @return string
     */
    protected function filterReasonPhrase($text) {
        if (!(is_object($text) && method_exists($text, '__toString')) && !is_string($text)) {
            throw new InvalidArgumentException('The reason phrase must be a string');
        }

        $text = trim($text);
        if (preg_match(',[\r\n],', $text)) {
            throw new InvalidArgumentException('The reason phrase can not contain carriage return characters');
        }

        return $text;
    }

    /**
     * Fetch the status code for a given reason phrase.
     *
     * @param string $text the reason phrase
     *
     * @return mixed
     */
    protected function fetchStatusCode($text) {
        return array_search(strtolower($text), array_map('strtolower', $this->responseStatus));
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
     * 添加信息的HTTP状态
     * Add or Update the HTTP Status array.
     *
     * @param int    $code a HTTP status Code
     * @param string $text a associated reason phrase
     *
     * @throws RuntimeException if the HTTP status code or the reason phrase are invalid
     */
    public function mergeResponseStatus($code, $text) {
        $code = $this->filterStatusCode($code);
        $text = $this->filterReasonPhrase($text);
        if ($this->hasReasonPhrase($text) && $this->getStatusCode($text) !== $code) {
            throw new RuntimeException('The submitted reason phrase is already present in the collection');
        }

        $this->responseStatus[$code] = $text;
    }

    /**
     * Check if the hasReasonPhrase exists in a collection.
     *
     * @param int $statusText http status text
     *
     * @throws InvalidArgumentException If the requested $statusText is not valid
     *
     * @return bool true|false
     */
    public function hasReasonPhrase($statusText) {
        try {
            $statusText = $this->filterReasonPhrase($statusText);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return (bool) $this->fetchStatusCode($statusText);
    }

    /**
     * 文字对应的状态码
     * Get the code for a given status text.
     *
     * @param string $statusText http status text
     *
     * @throws InvalidArgumentException If the requested $statusText is not valid
     * @throws OutOfBoundsException     If not status code is found
     *
     * @return string Returns code for the given status text
     */
    public function getStatusCode($statusText) {
        $statusText = $this->filterReasonPhrase($statusText);
        $statusCode = $this->fetchStatusCode($statusText);
        if ($statusCode !== false) {
            return $statusCode;
        }

        throw new OutOfBoundsException(sprintf('No Http status code is associated to `%s`', $statusText));
    }

    /**
     * Check if the code exists in a collection.
     *
     * @param int $statusCode http status code
     *
     * @throws InvalidArgumentException If the requested $statusCode is not valid
     *
     * @return bool true|false
     */
    public function hasStatusCode($statusCode) {
        try {
            $statusCode = $this->filterStatusCode($statusCode);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return isset($this->responseStatus[$statusCode]);
    }

    /**
     * 状态码对应的文字
     * Get the text for a given status code.
     *
     * @param string $statusCode http status code
     *
     * @throws InvalidArgumentException If the requested $statusCode is not valid
     * @throws OutOfBoundsException     If the requested $statusCode is not found
     *
     * @return string Returns text for the given status code
     */
    public function getReasonPhrase($statusCode) {
        $statusCode = $this->filterStatusCode($statusCode);

        if (!isset($this->responseStatus[$statusCode])) {
            throw new OutOfBoundsException(sprintf('Unknown response status code: `%s`', $statusCode));
        }

        return $this->responseStatus[$statusCode];
    }
}