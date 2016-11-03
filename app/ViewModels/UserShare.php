<?php

namespace App\ViewModels;


class UserShare
{
    /** @var  string */
    public $playerName;

    /** @var  int */
    public $shareId;

    /** @var int */
    public $currentPrice;

    /** @var int */
    public $amount;

    /** @var int */
    public $buyPrice;

    /** @var int */
    public $difference;

    /** @var float */
    public $percentage;
}