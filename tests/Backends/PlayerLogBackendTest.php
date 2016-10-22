<?php

namespace Backends;

use App\Backends\PlayerLogBackend;
use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models;

class PlayerLogBackendTest extends \TestCase
{
	use DatabaseMigrations;

	public function testPlayerLog()
	{
		$playerLogBackend = new PlayerLogBackend();
		$player1 = new Models\Player();
		$player1->setName($playerName = 'player')
			->setPoints($points = 666)
			->setPosition($position = 4)
			->setDivision($division = 2);


		$playerLogBackend->log($player1, $sharePoints1 = 100);
		$player1->setPoints($points2 = 665);
		$playerLogBackend->log($player1, $sharePoints2 = 200);

		$player2 = new Models\Player();
		$player2->setName('player2')
			->setPoints($points)
			->setPosition($position)
			->setDivision($division);

		$playerLogBackend->log($player2, $sharePoints1);

		$playerLogs = $playerLogBackend->getByName($playerName);

		$this->assertEquals(2, count($playerLogs->getIterator()));
		$this->assertEquals($playerName, $playerLogs[0]->getName());
		$this->assertEquals($points, $playerLogs[0]->getPoints());
		$this->assertEquals($division, $playerLogs[0]->getDivision());
		$this->assertEquals($position, $playerLogs[0]->getPosition());
		$this->assertEquals($sharePoints1, $playerLogs[0]->getSharePoints());

		$this->assertEquals($playerName, $playerLogs[1]->getName());
		$this->assertEquals($points2, $playerLogs[1]->getPoints());
		$this->assertEquals($division, $playerLogs[1]->getDivision());
		$this->assertEquals($position, $playerLogs[1]->getPosition());
		$this->assertEquals($sharePoints2, $playerLogs[1]->getSharePoints());

	}
}