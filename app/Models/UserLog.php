<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    const FIELD_USERNAME = 'username';
    const FIELD_POINTS = 'points';
    const FIELD_CREDIT = 'credit';
    const FIELD_LOG_TIME = 'created_at';

    public $primaryKey = 'user_log_id';
    public $incrementing = true;

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

    public function getPoints()
    {
        return $this->getAttribute(self::FIELD_POINTS);
    }

    public function setPoints($value)
    {
        return $this->setAttribute(self::FIELD_POINTS, $value);
    }

    /**
     * @return Carbon
     */
    public function getLogTime() {
        return  $this->getAttribute(self::FIELD_LOG_TIME);
    }
}