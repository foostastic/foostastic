<?php

namespace App\Calculators;
use App\Models\Player;
use App\Models\Share;
use App\Backends;

class ShareValue
{
    public function getValueForPlayer(Player $player)
    {
        $division = $player->getDivision();
        return (int)($player->getPoints() * $this->getRatioForDivision($division) + $this->getOffsetForDivision($division));
    }

    public function getValueForPlayerName($playerName)
    {
        $playerBackend = new Backends\Player();
        $player = $playerBackend->getByName($playerName);
        return $player !== null ? $this->getValueForPlayer($player) : 0;
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

    /*
     * PRIVATE METHODS
     */

    private function getRatioForDivision($division)
    {
        $values = array(
            1 => 4,
            2 => 2,
            3 => 1,
        );
        return $values[$division];
    }

    private function getOffsetForDivision($division)
    {
        $values = array(
            1 => 900,
            2 => 300,
            3 => 0,
        );
        return $values[$division];
    }
}