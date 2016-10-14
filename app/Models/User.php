<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    const FIELD_USERNAME = 'username';
    const FIELD_CREDIT = 'credit';

    public $primaryKey = 'username';
    public $incrementing = false;

    public function getUserName()
    {
        return $this->getAttribute(self::FIELD_USERNAME);
    }

    public function setUserName($value)
    {
        return $this->setAttribute(self::FIELD_USERNAME, $value);
    }

    public function getCredit()
    {
        return $this->getAttribute(self::FIELD_CREDIT);
    }

    public function setCredit($value)
    {
        return $this->setAttribute(self::FIELD_CREDIT, $value);
    }
}