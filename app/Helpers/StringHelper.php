<?php

namespace App\Helpers;

class StringHelper
{
    public static function acronym(string $text): string
    {
        $words = preg_split('/\s+/', trim($text));

        if (count($words) === 1) {
            return mb_substr($words[0], 0, 2);
        }

        $first = mb_substr($words[0], 0, 1);
        $last = mb_substr($words[count($words) - 1], 0, 1);

        return strtoupper($first . $last);
    }
}
