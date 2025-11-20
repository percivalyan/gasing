<?php

namespace App\Helpers;

class LetterHelper
{
    public static function romanMonth($month)
    {
        $roman = [
            'I',
            'II',
            'III',
            'IV',
            'V',
            'VI',
            'VII',
            'VIII',
            'IX',
            'X',
            'XI',
            'XII'
        ];
        return $roman[$month - 1] ?? '';
    }
}
