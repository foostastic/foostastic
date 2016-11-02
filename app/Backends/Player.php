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
        if ($this->isCacheStale()) {
            $this->prefetch();
        }
        return isset(self::$cacheByName[$name]) ? self::$cacheByName[$name] : null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        if ($this->isCacheStale()) {
            $this->prefetch();
        }
        return self::$cachedGetAll;
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
    private static $cachedGetAll;

    private function isCacheStale()
    {
        return count(self::$cacheByName) == 0;
    }

    private function prefetch()
    {
        self::$cacheByName = array();
        self::$cachedGetAll = $this->executeGetAll();
        foreach (self::$cachedGetAll as $player) {
            self::$cacheByName[$player->getName()] = $player;
        }
    }

    public static function flush()
    {
        self::$cacheByName = [];
        self::$cachedGetAll = null;
    }
}