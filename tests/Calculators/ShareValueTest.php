<?php

namespace Calculators;


use App\Calculators\ShareValue;
use App\Models\Player;
use TestCase;

class ShareValueTest extends TestCase
{
    /**
     * @var ShareValue
     */
    private $calculator;

    public function setUp()
    {
        parent::setUp();
        $this->calculator = new ShareValue();
    }

    public function testPlayerValues()
    {
        $player = $this->mockPlayer(3, 10);
        $this->assertEquals(10, $this->calculator->getValueForPlayer($player));
        $player = $this->mockPlayer(2, 10);
        $this->assertEquals(320, $this->calculator->getValueForPlayer($player));
        $player = $this->mockPlayer(1, 10);
        $this->assertEquals(940, $this->calculator->getValueForPlayer($player));
    }

    /**
     * @param int $division
     * @param int $points
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockPlayer($division, $points):\PHPUnit_Framework_MockObject_MockObject
    {
        $player = $this->createMock(Player::class);
        $player->expects($this->once())->method('getDivision')->willReturn($division);
        $player->expects($this->once())->method('getPoints')->willReturn($points);
        return $player;
    }
}
