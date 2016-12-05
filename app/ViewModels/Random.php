<?php

namespace App\ViewModels;

class Random
{
    public static $RANDOM_NAMES = [
        'aaron',
        'econtreras',
        'jaguerra',
        'anamateo',
        'amateos',
        'ramon',
        'agsantiago',
        'dzayas',
        'anna',
    ];

    public static function choose($array) {
        return $array[array_rand($array)];
    }
}