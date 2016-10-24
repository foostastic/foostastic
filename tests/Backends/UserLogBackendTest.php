<?php

namespace Backends;

use App\Backends\UserLogBackend;
use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models;

class UserLogBackendTest extends \TestCase
{
	use DatabaseMigrations;

	const USERNAME1 = 'user';
	const CREDIT1 = 50;
	const CREDIT2 = 120;
	const POINTS1 = 100;
	const POINTS2 = 200;
	const USERNAME2 = 'user2';

	public function testUserLog()
	{
		$userLogBackend = new UserLogBackend();
		$user = new Models\User();
		$user->setUserName(self::USERNAME1)
			->setCredit(self::CREDIT1);
		$userLogBackend->log($user, self::POINTS1);

		$user->setCredit(self::CREDIT2);
		$userLogBackend->log($user, self::POINTS2);

		$user2 = new Models\User();
		$user2->setUserName(self::USERNAME2)
			->setCredit(self::CREDIT1);

		$userLogs = $userLogBackend->getByUsername(self::USERNAME1);
		$this->assertEquals(2, count($userLogs->getIterator()));
		$this->assertEquals(self::USERNAME1, $userLogs[0]->getUserName());
		$this->assertEquals(self::POINTS1, $userLogs[0]->getPoints());
		$this->assertEquals(self::CREDIT1, $userLogs[0]->getCredit());

		$this->assertEquals(self::USERNAME1, $userLogs[1]->getUserName());
		$this->assertEquals(self::POINTS2, $userLogs[1]->getPoints());
		$this->assertEquals(self::CREDIT2, $userLogs[1]->getCredit());

	}
}