<?php

namespace App\tmp;

use App\Backends\Player;
use App\Backends\Share;
use App\Calculators\ShareValue;

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

    /**
     * @return Wallet
     */
    public static function getForUser($user) {
        $wallet = Wallet::create();
        $shareBackend = new Share();
        $sharesList = $shareBackend->getByUser($user);
        /**
         * @var $share \App\Models\Share
         */
        $shares = $sharesList->all();
        foreach($shares as $share) {
            if ($share->getAmount() > 0) {
                $wallet->add(StockPurchase::create($share->getPlayer(), $share->getBuyPrice(), $share->getAmount()));
            }
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
        $playerBackend = new Player();
        $player = $playerBackend->getByName($stockId);
        return $player->getPoints();
    }
}