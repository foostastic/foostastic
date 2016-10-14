<?php

namespace App\tmp;

class Market
{
    /**
     * @var Stock[]
     */
    public $stocks = [];

    /**
     * @return Market
     */
    public static function create() {
        return new Market();
    }

    /**
     * @return Market
     */
    public static function random() {
        $market = Market::create();
        foreach (Random::$RANDOM_NAMES as $name) {
            $market->add(Stock::create($name, rand(0, 300), rand(1,3)));
        }
        return $market;
    }

    /*
     * PUBLIC METHODS
     */

    public function add(Stock $stock) {
        $this->stocks[] = $stock;
    }

    /**
     * @return Stock[]
     */
    public function getAllAvailable()
    {
        return array_filter($this->stocks, function (Stock $stock) { return $stock->amountAvailable > 0;});
    }

    /**
     * @param String $stockId
     * @return float
     */
    public function getPrice($stockId) {
        // TODO Convert this to map
        foreach($this->stocks as $stock) {
            if ($stock->id == $stockId) {
                return $stock->currentPrice;
            }
        }
        return 0;
    }
}