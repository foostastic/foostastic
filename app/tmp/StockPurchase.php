<?php

namespace App\tmp;

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
     * @param String $stockId
     * @param float $price
     * @param int $amount
     * @return StockPurchase
     */
    public static function create($stockId, $price, $amount) {
        $purchase = new StockPurchase();
        $purchase->stockId = $stockId;
        $purchase->purchaseAmount = $amount;
        $purchase->purchaseValue = $price;
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