<?php

namespace App\tmp;

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
        $info = new UserInfo();
        $info->isLogged = true;
        $info->id = $id;
        $info->name = $id;
        $info->capital = rand(0, 500);
        $info->wallet = Wallet::random();
        return $info;
    }

}