<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareActionLog extends Model
{

	const FIELD_PLAYER = 'player';
	const FIELD_USER = 'user';
	const FIELD_ACTION = 'action';
	const FIELD_AMOUNT = 'amount';
	const FIELD_ACTION_PRICE = 'action_price';
	const FIELD_LOG_TIME = 'created_at';

	public $primaryKey = 'action_log_id';
	public $incrementing = true;

	public function getPlayer()
	{
		return $this->getAttribute(self::FIELD_PLAYER);
	}

	public function setPlayer($value)
	{
		return $this->setAttribute(self::FIELD_PLAYER, $value);
	}

	public function getActionPrice()
	{
		return $this->getAttribute(self::FIELD_ACTION_PRICE);
	}

	public function setActionPrice($value)
	{
		return $this->setAttribute(self::FIELD_ACTION_PRICE, $value);
	}

	public function getAmount()
	{
		return $this->getAttribute(self::FIELD_AMOUNT);
	}

	public function setAmount($value)
	{
		return $this->setAttribute(self::FIELD_AMOUNT, $value);
	}

	public function getAction()
	{
		return $this->getAttribute(self::FIELD_ACTION);
	}

	public function setAction($value)
	{
		return $this->setAttribute(self::FIELD_ACTION, $value);
	}

	public function getUser()
	{
		return $this->getAttribute(self::FIELD_USER);
	}

	public function setUser($value)
	{
		return $this->setAttribute(self::FIELD_USER, $value);
	}

	public function getLogTime()
	{
		return $this->getAttribute(self::FIELD_LOG_TIME);
	}
}