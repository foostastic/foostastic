<?php


namespace App\Backends;
use App\Models;

class Player
{
    /**
     * @param $name
     * @return \App\Models\Player|null
     */
    public function getByName($name)
    {
        return Models\Player::where(Models\Player::FIELD_NAME, $name)
            ->limit(1)
            ->get()
            ->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        if ($this->isCacheStale()) {
            $this->prefetch();
        }
        return array_values(self::$cacheByName);
    }

    private function executeGetAll()
    {
        return Models\Player::orderBy(Models\Player::FIELD_DIVISION)
            ->orderBy(Models\Player::FIELD_POSITION)
            ->get();
    }

    public function clearAll()
    {
        foreach ($this->getAll() as $player) {
            $player->delete();
        }
    }

    private static $cacheByName = array();

    private function isCacheStale()
    {
        return count(self::$cacheByName) == 0;
    }

    private function prefetch()
    {
        self::$cacheByName = array();
        foreach ($this->executeGetAll() as $player) {
            self::$cacheByName[$player->getName()] = $player;
        }
    }
}