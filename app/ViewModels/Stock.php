<?php

namespace App\ViewModels;

class Stock
{
    /**
     * @var String
     */
    public $id;

    /**
     * @var String
     */
    public $name;

    /**
     * @var float
     */
    public $currentPrice;

    /**
     * @var int
     */
    public $amountAvailable;

    /**
     * @param String $id
     * @param float $currentPrice
     * @return Stock
     */
    public static function create($id, $currentPrice, $available) {
        $stock = new Stock();
        $stock->id = $id;
        $stock->name = $id;
        $stock->currentPrice = $currentPrice;
        $stock->amountAvailable = $available;
        return $stock;
    }

}