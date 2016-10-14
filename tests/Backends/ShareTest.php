<?php


namespace Backends;

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Backends\Share;
use App\Models;

class ShareTest extends \TestCase
{
    use DatabaseMigrations;

    public function testGetByUser()
    {
        $user = new Models\User();
        $user->setUserName($username = 'da_user');
        $user->save();

        $share = new Models\Share();
        $share->setPlayer($player = 'da_player');
        $share->setUser($user->getUserName());
        $share->setAmount($amount = 666);
        $share->setBuyPrice($buyPrice = 777);
        $share->save();

        $backend = new Share();
        $shares = $backend->getByUser($user);
        $share = $shares->first();

        $this->assertEquals(1, $shares->count());
        $this->assertEquals($player, $share->getPlayer());
        $this->assertEquals($username, $share->getUser());
        $this->assertEquals($amount, $share->getAmount());
        $this->assertEquals($buyPrice, $share->getBuyPrice());
    }

    public function testBuyAndSell()
    {
        $user = new Models\User();
        $user->setUserName($username = 'da_user');
        $user->save();

        $player = new Models\Player();
        $player->setName($playerName = 'da_player');
        $player->save();

        $backend = new Share();

        $this->assertEquals(0, $backend->getByUser($user)->count());

        $backend->buy($user, $player, 2);
        $shares = $backend->getByUser($user);
        $share = $shares->first();
        $this->assertEquals(1, $shares->count());
        $this->assertEquals(2, $share->getAmount());

        $backend->sell($share, 1);
        $shares = $backend->getByUser($user);
        $share = $shares->first();
        $this->assertEquals(1, $shares->count());
        $this->assertEquals(1, $share->getAmount());

        $backend->sell($share, 1);
        $shares = $backend->getByUser($user);
        $this->assertEquals(0, $shares->count());
    }

    /**
     * @expectedException \Illuminate\Database\QueryException
     */
    public function testUserAndPlayerUnique()
    {
        $share = new Models\Share();
        $share->setUser($user = 'da_user');
        $share->setPlayer($user = 'da_player');
        $share->save();

        $share = new Models\Share();
        $share->setUser($user = 'da_user');
        $share->setPlayer($user = 'da_player');
        $share->save();
    }

}