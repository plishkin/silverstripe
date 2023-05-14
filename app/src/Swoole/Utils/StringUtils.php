<?php

namespace App\Swoole\Utils;

class StringUtils
{

    public static function toCamelCase(string $s): string
    {
        $res = str_replace(['-', '/', '\\', '_'], ' ', $s);
        $res = ucwords($res);
        return str_replace(' ', '', $res) . '';
    }

}
