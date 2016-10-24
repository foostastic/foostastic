<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    const FIELD_NAME = 'name';
    const FIELD_DIVISION = 'division';
    const FIELD_POSITION = 'position';
    const FIELD_POINTS = 'points';

    public $primaryKey = 'name';
    public $incrementing = false;


    public function getName()
    {
        return $this->getAttribute(self::FIELD_NAME);
    }

    /**
     * @param $value
     * @return $this
     */
    public function setName($value)
    {
        return $this->setAttribute(self::FIELD_NAME, $value);
    }

    public function getDivision()
    {
        return $this->getAttribute(self::FIELD_DIVISION);
    }

    /**
     * @param $value
     * @return $this
     */
    public function setDivision($value)
    {
        return $this->setAttribute(self::FIELD_DIVISION, $value);
    }

    public function getPosition()
    {
        return $this->getAttribute(self::FIELD_POSITION);
    }

    /**
     * @param $value
     * @return $this
     */
    public function setPosition($value)
    {
        return $this->setAttribute(self::FIELD_POSITION, $value);
    }

    public function getPoints()
    {
        return $this->getAttribute(self::FIELD_POINTS);
    }

    /**
     * @param $value
     * @return $this
     */
    public function setPoints($value)
    {
        return $this->setAttribute(self::FIELD_POINTS, $value);
    }

}