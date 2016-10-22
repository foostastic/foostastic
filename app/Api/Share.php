<?php

namespace App\Api;
use App\Models;
use App\Backends;
use App\Calculators\ShareValue;

class Share
{
    public function buy(Models\Player $player, $amount)
    {
        $userBackend = new Backends\User();
        $user = $userBackend->getCurrentUser();
        if ($user === null || $amount < 1) {
            return;
        }

        $shareValueCalculator = new ShareValue();
        $valueForPlayer = $shareValueCalculator->getValueForPlayer($player);
        $neededAmount = $valueForPlayer * $amount;
        $futureCredit = $user->getCredit() - $neededAmount;
        if ($futureCredit < 0) {
            \Log::warn('Tried to buy without credit', array($player->getName(), $amount));
            return;
        }

        $shareBackend = new Backends\Share();
        $availableStock = $shareBackend->getPlayerAmountStock($player);
        if ($availableStock - $amount < 0) {
            \Log::warn('Tried to buy without enough available amount', array($player->getName(), $amount));
            return;
        }

        $shareBackend->buy($user, $player, $amount, $valueForPlayer);
        $user->setCredit($futureCredit);
        $user->save();
    }

    public function sell(Models\Share $share, $amount)
    {
        $userBackend = new Backends\User();
        $user = $userBackend->getCurrentUser();
        if ($user === null || $amount < 1) {
            return;
        }
        if ($amount > $share->getAmount()) {
            \Log::warn('Tried to sell more amount of share than exists', array($share->getPlayer(), $share->getId(), $amount));
            return;
        }

        $playerBackend = new Backends\Player();
        $player = $playerBackend->getByName($share->getPlayer());
        $shareValueCalculator = new ShareValue();
        $addedAmount = $shareValueCalculator->getValueForPlayer($player) * $amount;

        $shareBackend = new Backends\Share();
        $shareBackend->sell($share, $amount);

        $user->setCredit($user->getCredit() + $addedAmount);
        $user->save();
    }

}