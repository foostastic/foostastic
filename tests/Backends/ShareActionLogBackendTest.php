<?php

namespace Backends;

use App\Backends\PlayerLogBackend;
use App\Backends\ShareActionLogBackend;
use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models;

class ShareActionLogBackendTest extends \TestCase
{
	use DatabaseMigrations;

	public function testShareActionLog()
	{
		$shareActionLogBackend = new ShareActionLogBackend();
		$shareActionLogBackend->create($user = 'user', $playerName = 'player', $actionSale = 'sale', $amount1 = 1, $price10 = 10);
		$shareActionLogBackend->create($user, $playerName2 = 'player2', $actionPurchase = 'purchase', $amount2 = 2, $price20 = 20);
		$shareActionLogBackend->create($user2 = 'user2', $playerName3 = 'player3', $actionSale, $amount1, $price30 = 30);
		$shareActionLogBackend->create($user2, $playerName2, $actionPurchase, $amount3 = 3, $price40 = 40);

		$userShareActions = $shareActionLogBackend->getByUsername($user);

		$this->assertEquals(2, count($userShareActions->getIterator()));

		$this->assertEquals($playerName, $userShareActions[0]->getPlayer());
		$this->assertEquals($user, $userShareActions[0]->getUser());
		$this->assertEquals($actionSale, $userShareActions[0]->getAction());
		$this->assertEquals($amount1, $userShareActions[0]->getAmount());
		$this->assertEquals($price10, $userShareActions[0]->getActionPrice());
		$this->assertEquals($playerName2, $userShareActions[1]->getPlayer());
		$this->assertEquals($user, $userShareActions[1]->getUser());
		$this->assertEquals($actionPurchase, $userShareActions[1]->getAction());
		$this->assertEquals($amount2, $userShareActions[1]->getAmount());
		$this->assertEquals($price20, $userShareActions[1]->getActionPrice());

		$playerShareActions = $shareActionLogBackend->getByPlayerName($playerName2);

		$this->assertEquals(2, count($playerShareActions->getIterator()));

		$this->assertEquals($playerName2, $playerShareActions[0]->getPlayer());
		$this->assertEquals($user, $playerShareActions[0]->getUser());
		$this->assertEquals($actionPurchase, $playerShareActions[0]->getAction());
		$this->assertEquals($amount2, $playerShareActions[0]->getAmount());
		$this->assertEquals($price20, $playerShareActions[0]->getActionPrice());

		$this->assertEquals($playerName2, $playerShareActions[1]->getPlayer());
		$this->assertEquals($user2, $playerShareActions[1]->getUser());
		$this->assertEquals($actionPurchase, $playerShareActions[1]->getAction());
		$this->assertEquals($amount3, $playerShareActions[1]->getAmount());
		$this->assertEquals($price40, $playerShareActions[1]->getActionPrice());
	}
}