<?php


namespace Backends;

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Backends\Player;
use App\Models;

class PlayerTest extends \TestCase
{
    use DatabaseMigrations;

    public function testGetByName()
    {
        $player = new Models\Player();
        $player->setName($name = 'pepe');
        $player->setDivision($division = 2);
        $player->setPosition($position = 66);
        $player->setPoints($points = 55);
        $player->save();

        $backend = new Player();
        $player = $backend->getByName($name);

        $this->assertEquals($name, $player->getName());
        $this->assertEquals($division, $player->getDivision());
        $this->assertEquals($position, $player->getPosition());
        $this->assertEquals($points, $player->getPoints());
    }

    public function testGetByNameOnNoMatch()
    {
        $backend = new Player();
        $player = $backend->getByName($name = 'any_name');
        $this->assertNull($player);
    }

    public function testGetAll()
    {
        $player = new Models\Player();
        $player->setName($name = 'pepe');
        $player->setDivision($division = 2);
        $player->setPosition($position = 66);
        $player->setPoints($points = 55);
        $player->save();

        $backend = new Player();
        $players = $backend->getAll();

        $this->assertEquals(1, $players->count());
        $this->assertEquals($name, $players->first()->getName());
    }

}