<?php

namespace App\Helpers;

class Str
{
    /**
     * 下划线转驼峰
     * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
     * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
     *
     * @param string $uncamelized_words
     * @param string $separator
     * @return string
     */
    public static function camelize(string $uncamelized_words, string $separator = '_'): string
    {
        return str_replace(' ', '', ucwords(str_replace($separator, ' ', $uncamelized_words)));
    }

    /**
     * 驼峰命名转下划线命名
     * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
     *
     * @param string $camelCaps
     * @param string $separator
     * @return string
     */
    public static function uncamelize(string $camelCaps, string $separator = '_'): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }
}
