<?php

namespace Tanel\PHPWebKit\Functions;

class Str {
    /**
     * 下划线转驼峰
     *
     * @param  string $uncamelized_words
     * @param  string $separator
     * @return string
     */
    public static function camelize($uncamelized_words, $separator = '_') {
        $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
    }

    /**
     * 驼峰命名转下划线命名
     *
     * @param  string $camelCaps
     * @param  string $separator
     * @return string
     */
    public static function uncamelize($camelCaps, $separator = '_') {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }

    /**
     * 人性化的显示字节大小
     *
     * @param  integer $size
     * @return string
     */
    public static function int2size($size) {
        $unit = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    /**
     * 字节大小整数化
     *
     * @param  string $filesize
     * @return integer
     */
    public static function size2int($filesize) {
        $filesize = strtoupper($filesize);
        if (strpos($filesize, 'GB')) {
            $filesize = intval($filesize) * 1073741824;
        } elseif (strpos($filesize, 'MB')) {
            $filesize = intval($filesize) * 1048576;
        } elseif (strpos($filesize, 'KB')) {
            $filesize = intval($filesize) * 1024;
        } elseif (strpos($filesize, 'KB')) {
            $filesize = intval($filesize);
        }
        return $filesize;
    }

    /**
     * 将字符串转换成二进制
     *
     * @param  string $str 字符串转成二进制
     * @return
     */
    public static function str2bin($str) {
        $arr = preg_split('/(?<!^)(?!$)/u', $str);
        foreach ($arr as &$v) {
            $temp = unpack('H*', $v);
            $v    = base_convert($temp[1], 16, 2);
            unset($temp);
        }
        return join(' ', $arr);
    }

    /**
     * 二进制转换成字符串
     *
     * @param  string $value
     * @return
     */
    public static function bin2str($str) {
        $arr = explode(' ', $str);
        foreach ($arr as &$v) {
            $v = pack("H" . strlen(base_convert($v, 2, 16)), base_convert($v, 2, 16));
        }
        return join('', $arr);
    }

    /**
     * 字符串截取类
     *
     * @param string $string
     * @param int    $length
     * @param string $suffix
     * @return string
     */
    public static function sub_string($string, $length = 300, $suffix = '') {
        $string   = mb_convert_encoding($string, 'UTF-8', 'gb2312, UTF-8');
        $s_length = mb_strlen($string, 'UTF-8');

        if ($length >= $s_length) {
            $string = mb_substr($string, 0, $length, 'UTF-8') . $suffix;
        }
        return $string;
    }

    /**
     * 字符串防XSS攻击
     *
     * @param  string $input
     * @return string
     */
    public static function sanitize($input) {
        if (is_array($input)) {
            foreach ($input as $var => $val) {
                $output[$var] = sanitize($val);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $search = [
                '@<script[^>]*?>.*?</script>@si', // Strip out javascript
                '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
                '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
                '@<![\s\S]*?--[ \t\n\r]*>@', // Strip multi-line comments
            ];

            $input  = preg_replace($search, '', $input);
            $output = mysql_real_escape_string($input);
        }
        return $output;
    }


    /**
     * 检查字符串是否是UTF8编码
     *
     * @param  string    $string 字符串
     * @return Boolean
     */
     public static function is_utf8($string) {
        return preg_match('%^(?:
           [\x09\x0A\x0D\x20-\x7E]            # ASCII
           | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
           |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
           | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
           |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
           |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
           | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
           |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
           )*$%xs', $string);
    }

    /**
     *  获取data数组中的参数的签名
     *
     * @param  array $data  待签名字符串
     * @param  string $slot 密码盐
     * @return string
     */
    public static function sign(array $data, $slot) {
        if (empty($data)) {
            return '';
        }
        ksort($data);
        return md5(http_build_query($data) . $slot);
    }
}