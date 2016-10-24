<?php

namespace App\Api;
use App\Models;
use App\Backends;
use App\Calculators\ShareValue;

class Share
{
    /**
     * @param Models\Player $player
     * @param int $amount
     * @return bool Whether the buy operation was successful or not
     */
    public function buy(Models\Player $player, $amount)
    {
        $userBackend = new Backends\User();
        $user = $userBackend->getCurrentUser();
        if ($user === null || $amount < 1) {
            return false;
        }

        $shareValueCalculator = new ShareValue();
        $valueForPlayer = $shareValueCalculator->getValueForPlayer($player);
        $neededAmount = $valueForPlayer * $amount;
        $futureCredit = $user->getCredit() - $neededAmount;
        if ($futureCredit < 0) {
            \Log::warn('Tried to buy without credit', array($player->getName(), $amount));
            return false;
        }

        $shareBackend = new Backends\Share();
        $availableStock = $shareBackend->getPlayerAmountStock($player);
        if ($availableStock - $amount < 0) {
            \Log::warn('Tried to buy without enough available amount', array($player->getName(), $amount));
            return false;
        }

        $shareBackend->buy($user, $player, $amount, $valueForPlayer);

        $user->setCredit($futureCredit);
        $user->save();
        $logger = new Logger();
        $logger->logBuyAction($user->getUserName(), $player->getName(), $amount, $neededAmount);
        return true;
    }

    public function sell(Models\Share $share, $amount)
    {
        $userBackend = new Backends\User();
        $user = $userBackend->getCurrentUser();
        if ($user === null || $amount < 1) {
            return false;
        }
        if ($amount > $share->getAmount()) {
            \Log::warn('Tried to sell more amount of share than exists', array($share->getPlayer(), $share->getId(), $amount));
            return false;
        }

        $playerBackend = new Backends\Player();
        $player = $playerBackend->getByName($share->getPlayer());
        $shareValueCalculator = new ShareValue();
        $addedAmount = $shareValueCalculator->getValueForPlayer($player) * $amount;

        $shareBackend = new Backends\Share();
        $shareBackend->sell($share, $amount);

        $user->setCredit($user->getCredit() + $addedAmount);
        $user->save();
        $logger = new Logger();
        $logger->logSellAction($user->getUserName(), $player->getName(), $amount, $addedAmount);
        return true;
    }

}