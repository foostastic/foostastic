<?php

namespace App\ViewModels;

use App\ViewModels\Random;

class StockPurchase
{
    /**
     * @var String
     */
    public $stockId;

    /**
     * @var int
     */
    public $purchaseAmount;

    /**
     * @var float
     */
    public $purchaseValue;

    /**
     * @var int
     */
    public $shareId;

    /**
     * @param String $stockId
     * @param float $price
     * @param int $amount
     * @return StockPurchase
     */
    public static function create($stockId, $price, $amount, $shareId) {
        $purchase = new StockPurchase();
        $purchase->stockId = $stockId;
        $purchase->purchaseAmount = $amount;
        $purchase->purchaseValue = $price;
        $purchase->shareId = $shareId;
        return $purchase;
    }

    /**
     * @return StockPurchase
     */
    public static function random()
    {
        return StockPurchase::create(Random::choose(Random::$RANDOM_NAMES), rand(1, 300), rand(1,3));
    }

}