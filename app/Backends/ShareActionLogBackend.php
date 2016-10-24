<?php

namespace App\Backends;
use App\Models;

class ShareActionLogBackend
{

    /**
     * @param $userName
     * @return \App\Models\ShareActionLog[]
     */
    public function getByUsername($userName)
    {
        $users = Models\ShareActionLog::where(Models\ShareActionLog::FIELD_USER, $userName)
            ->orderBy(Models\ShareActionLog::FIELD_LOG_TIME, 'desc')
            ->get();

        return $users;
    }

    /**
     * @param $playerName
     * @return \App\Models\ShareActionLog[]
     */
    public function getByPlayerName($playerName)
    {
        $users = Models\ShareActionLog::where(Models\ShareActionLog::FIELD_PLAYER, $playerName)
            ->orderBy(Models\ShareActionLog::FIELD_LOG_TIME, 'desc')
            ->get();

        return $users;
    }

    /**
     * @param $user
     * @param $player
     * @param $action
     * @param $amount
     * @param $price
     */
    public function create($user, $player, $action, $amount, $price)
    {
        $shareActionLog = new Models\ShareActionLog();
        $shareActionLog->setAction($action);
        $shareActionLog->setUser($user);
        $shareActionLog->setAmount($amount);
        $shareActionLog->setActionPrice($price);
        $shareActionLog->setPlayer($player);
        $shareActionLog->save();
    }

}
