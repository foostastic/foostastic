<?php


namespace Backends;

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Backends\User;
use App\Models;

class UserTest extends \TestCase
{
    use DatabaseMigrations;

    public function testUser()
    {
        $user = new Models\User();
        $user->setUserName($username = 'user');
        $user->setCredit($credit = 666);
        $user->save();

        $userBackend = new User();
        $user = $userBackend->getByUsername($username);

        $this->assertEquals($username, $user->getUserName());
        $this->assertEquals($credit, $user->getCredit());
    }
}