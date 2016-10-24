<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerLog extends Player
{
	const FIELD_LOG_TIME = 'created_at';
	const FIELD_SHARE_POINTS = 'share_points';

	public $primaryKey = 'player_log_id';
	public $incrementing = true;

	public function getLogTime()
	{
		return $this->getAttribute(self::FIELD_LOG_TIME);
	}

	public function getSharePoints()
	{
		return $this->getAttribute(self::FIELD_SHARE_POINTS);
	}

	/**
	 * @param $value
	 * @return $this
	 */
	public function setSharePoints($value)
	{
		return $this->setAttribute(self::FIELD_SHARE_POINTS, $value);
	}

}