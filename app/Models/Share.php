<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    const FIELD_ID = 'id';
    const FIELD_PLAYER = 'player';
    const FIELD_USER = 'user';
    const FIELD_AMOUNT = 'amount';
    const FIELD_BUY_PRICE = 'buy_price';

    public $timestamps = false;

    public function getId()
    {
        return $this->getAttribute(self::FIELD_ID);
    }

    public function getPlayer()
    {
        return $this->getAttribute(self::FIELD_PLAYER);
    }

    public function setPlayer($value)
    {
        return $this->setAttribute(self::FIELD_PLAYER, $value);
    }

    public function getUser()
    {
        return $this->getAttribute(self::FIELD_USER);
    }

    public function setUser($value)
    {
        return $this->setAttribute(self::FIELD_USER, $value);
    }

    public function getAmount()
    {
        return $this->getAttribute(self::FIELD_AMOUNT);
    }

    public function setAmount($value)
    {
        return $this->setAttribute(self::FIELD_AMOUNT, $value);
    }

    public function getBuyPrice()
    {
        return $this->getAttribute(self::FIELD_BUY_PRICE);
    }

    public function setBuyPrice($value)
    {
        return $this->setAttribute(self::FIELD_BUY_PRICE, $value);
    }

}