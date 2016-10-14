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
        if ($user === null) {
            return;
        }

        $shareValueCalculator = new ShareValue();
        $neededAmount = $shareValueCalculator->getValueForPlayer($player) * $amount;
        $futureCredit = $user->getCredit() - $neededAmount;
        if ($futureCredit < 0) {
            // TODO: No credit!
            return;
        }

        $shareBackend = new Backends\Share();
        $shareBackend->buy($user, $player, $amount);
        $user->setCredit($futureCredit);
        $user->save();
    }

    public function sell(Models\Share $share, $amount)
    {
        $userBackend = new Backends\User();
        $user = $userBackend->getCurrentUser();
        if ($user === null) {
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