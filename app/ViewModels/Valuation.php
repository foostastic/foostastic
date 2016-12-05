<?php

namespace App\ViewModels;

class Valuation
{
    /**
     * @var float
     */
    public $buyPrice;

    /**
     * @var float
     */
    public $sellPrice;

    /**
     * @param float $buyPrice
     * @param float $sellPrice
     * @return Valuation
     */
    public static function create($buyPrice, $sellPrice) {
        $valuation = new Valuation();
        $valuation->buyPrice = $buyPrice;
        $valuation->sellPrice = $sellPrice;
        return $valuation;
    }

}