<?php
/**
 * LICENSE: This file can only be stored on servers belonging to
 * Tuenti Technologies S.L.
 *
 * @copyright 2016, (c) Tuenti Technologies S.L.
 */

namespace App\Api;

use App\Backends;
use App\Backends\PlayerLogBackend;
use App\Backends\User;
use App\Backends\UserLogBackend;
use App\Calculators\ShareValue;
use App\Calculators\UserPointsCalculator;
use App\Models\Player;

class Logger
{
	const BUY = "BUY";
	const SELL = "SELL";

	/**
	 * @param String[] $playerNAmes
	 */
	public function logPlayersWithChange($players)
	{
		$playerLogBackend = new PlayerLogBackend();
		$shareValue = new ShareValue();
		$shareBackend = new Share();
		$playerBackend = new \App\Backends\Player();
		$userBackend = new User();
		$usersToLogChange = array();
		foreach ($players as $playerName) {
			$player = $playerBackend->getByName($playerName);
			$playerLogBackend->log($player, $shareValue->getValueForPlayer($player));
			$shares = $shareBackend->getByPlayer($player);
			/* @var $share \App\Models\Share */
			foreach ($shares as $share) {
				$usersToLogChange[$share->getUser()] = $userBackend->getByUsername($share->getUser());
			}
		}

		foreach ($usersToLogChange as $user) {
			$this->logUserWithChange($user);
		}

	}

	public function logUserWithChange($user)
	{
		$userPointsCalculator = new UserPointsCalculator();
		$userLogBackend = new UserLogBackend();
		$userLogBackend->log($user, $userPointsCalculator->getPointsForUser($user));
	}

	public function logBuyAction($userName, $playerName, $amount, $price) {
		$this->logShareAction($userName, $playerName, $amount, $price, self::BUY);
	}

	public function logSellAction($userName, $playerName, $amount, $price) {
		$this->logShareAction($userName, $playerName, $amount, $price, self::SELL);
	}

	private function logShareAction($userName, $playerName, $amount, $price, $action)
	{
		$shareActionLogBackend = new Backends\ShareActionLogBackend();
		$shareActionLogBackend->create($userName, $playerName, $action, $amount, $price);
		$userBackend = new User();
		$this->logUserWithChange($userBackend->getByUsername($userName));
	}
}