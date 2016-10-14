<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    const FIELD_NAME = 'name';
    const FIELD_DIVISION = 'division';
    const FIELD_POSITION = 'position';
    const FIELD_POINTS = 'points';

    public function getName()
    {
        return $this->getAttribute(self::FIELD_NAME);
    }

    public function setName($value)
    {
        return $this->setAttribute(self::FIELD_NAME, $value);
    }

    public function getDivision()
    {
        return $this->getAttribute(self::FIELD_DIVISION);
    }

    public function setDivision($value)
    {
        return $this->setAttribute(self::FIELD_DIVISION, $value);
    }

    public function getPosition()
    {
        return $this->getAttribute(self::FIELD_POSITION);
    }

    public function setPosition($value)
    {
        return $this->setAttribute(self::FIELD_POSITION, $value);
    }

    public function getPoints()
    {
        return $this->getAttribute(self::FIELD_POINTS);
    }

    public function setPoints($value)
    {
        return $this->setAttribute(self::FIELD_POINTS, $value);
    }

}