<?php

namespace App\tmp;

class Wallet
{
    /**
     * @var StockPurchase[]
     */
    public $purchases = [];

    /**
     * @return Wallet
     */
    public static function create() {
        return new Wallet();
    }

    /**
     * @return Wallet
     */
    public static function random() {
        $wallet = Wallet::create();
        for ($i=0; $i<rand(0, 3); $i++) {
            $wallet->add(StockPurchase::random());
        }
        return $wallet;
    }

    /*
     * PUBLIC METHODS
     */

    public function add(StockPurchase $stockPurchase) {
        $this->purchases[] = $stockPurchase;
    }

    /**
     * @return StockPurchase[]
     */
    public function getAllByStock() {
        $byStock = [];
        foreach ($this->purchases as $purchase) {
            if (!isset($byStock[$purchase->stockId])) {
                $byStock[$purchase->stockId] = [];
            }
            $byStock[$purchase->stockId][] = $purchase;
        }
        return $byStock;
    }

    public function getTotalValue() {
        $valuation = 0;
        foreach ($this->purchases as $purchase) {
            $valuation += $this->getPrice($purchase->stockId);
        }
    }

    /*
     * PRIVATE METHODS
     */

    private function getPrice($stockId)
    {
        return rand(0, 300);
    }
}