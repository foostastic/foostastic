<?php


namespace App\Backends;
use App\Models;

class PlayerLogBackend
{
    /**
     * @param $name
     * @return \App\Models\PlayerLog[]
     */
    public function getByName($name)
    {
        return Models\PlayerLog::where(Models\Player::FIELD_NAME, $name)
            ->orderBy(Models\PlayerLog::FIELD_LOG_TIME, 'desc')
            ->get();
    }

    /**
     * @param Models\Player $player
     */
    public function log($player, $sharePoints)
    {
        $playerLog = new Models\PlayerLog();
        $playerLog->setName($player->getName())
            ->setPoints($player->getPoints())
            ->setDivision($player->getDivision())
            ->setPosition($player->getPosition())
            ->setSharePoints($sharePoints)
            ->save();
    }
}