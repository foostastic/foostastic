<?php

namespace App\tmp;
use App\Backends\User;

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
     * @return UserInfo
     */
    public static function unknown() {
        $info = new UserInfo();
        $info->isLogged = false;
        return $info;
    }

    /**
     * @param $id
     * @return UserInfo
     */
    public static function create($id) {
        $userBackend = new User();
        $user = $userBackend->getByUsername($id);
        $info = new UserInfo();
        $info->isLogged = true;
        $info->id = $user->getUserName();
        $info->name = $user->getUserName();
        $info->capital = $user->getCredit();
        $info->wallet = Wallet::getForUser($user);
        return $info;
    }

}