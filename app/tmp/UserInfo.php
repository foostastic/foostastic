<?php

namespace App\tmp;
use App\Backends\User;
use App\Backends\UserLogBackend;
use App\Models\UserLog;

class UserInfo
{
    /**
     * @var boolean
     */
    public $isLogged;

    /**
     * @var String
     */
    public $id;

    /**
     * @var String
     */
    public $name;

    /**
     * @var float
     */
    public $capital;

    /**
     * @var Wallet
     */
    public $wallet;

    /**
     * @var int
     */
    public $totalPoints;

    /**
     * @var UserLog|null
     */
    public $lastChange;

    /**
     * @return UserInfo
     */
    public static function unknown() {
        $info = new UserInfo();
        $info->isLogged = false;
        return $info;
    }

    /**
     * @param $username
     * @return UserInfo
     */
    public static function create($username) {
        $userBackend = new User();
        $user = $userBackend->getByUsername($username);
        $info = new UserInfo();
        $info->isLogged = true;
        $info->id = $user->getUserName();
        $info->name = $user->getUserName();
        $info->capital = $user->getCredit();
        $info->wallet = Wallet::getForUser($user);
        $info->totalPoints = $info->wallet->getTotalValue() + $info->capital;
        $logBackend = new UserLogBackend();
        $info->lastChange = $logBackend->getLastChange($username, $info->totalPoints);
        return $info;
    }

}