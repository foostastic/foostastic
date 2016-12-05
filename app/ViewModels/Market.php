<?php

namespace App\ViewModels;

use App\Backends\Player;
use App\Backends\Share;

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
        $shareBackend = new Share();
        $playerBackend = new Player();
        /* @var $players \App\Models\Player[] */
        $players = $playerBackend->getAll()->all();
        foreach ($players as $player) {
            $availableStocks = $shareBackend->getPlayerAmountStock($player);
            if ($availableStocks > 0) {
                $market->add(Stock::create($player->getName(), $player->getPoints(), $availableStocks));
            }
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