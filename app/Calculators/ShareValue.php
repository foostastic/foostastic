<?php

namespace App\Calculators;
use App\Models\Player;
use App\Models\Share;
use App\Backends;

class ShareValue
{
    public function getValueForPlayer(Player $player)
    {
        return (int)($player->getPoints() * ($this->getRatioForDivision($player->getDivision())));
    }

    public function getValueForShare(Share $share)
    {
        $playerBackend = new Backends\Player();
        $player = $playerBackend->getByName($share->getPlayer());
        if ($player === null) {
            return 0;
        }
        return (int)($this->getValueForPlayer($player) * $share->getAmount());
    }

    private function getRatioForDivision($division)
    {
        $values = array(
            1 => 10,
            2 => 5,
            3 => 1,
        );
        return $values[$division];
    }

}