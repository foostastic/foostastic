<?php

namespace App\Console\Commands;

use App\Api\Logger;
use Illuminate\Console\Command;
use App\Backends;
use App\Models;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class SusoRefreshCommand
 * @package App\Console\Commands
 * @see     https://github.com/laravel/lumen-framework/blob/5.0/src/Console/Commands/ServeCommand.php
 */
class SusoRefreshCommand extends Command
{
    protected $name = 'suso:refresh';
    protected $description = "Update foos player data using suso API";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $url = env('SUSO_FOOS_API_URL', false);
        if (!$url) {
            $this->error('Missing SUSO_FOOS_API_URL config');
            return;
        }
        $players = $this->susoGet($url, '/players');
        $current = $this->susoGet($url, '/seasons/current');
        $results = [];
        $divisionNum = 1;
        foreach ($current['divisions'] as $division) {
            $id = $division['id'];
            $playerInfos = $this->susoGet($url, "/divisions/$id/classification");
            foreach ($playerInfos as $playerInfo) {
                $name = $players[$playerInfo['player_id']]['name'];
                $results[] = array(
                    'division' => $divisionNum,
                    'pos' => $playerInfo['position'],
                    'player' => $name,
                    'points' => round($playerInfo['points']),
                );
            }
            $divisionNum++;
        }
        $this->info(sprintf('Found %s players', count($results)));
        if ($this->option('update') && count($results) > 0) {
            $this->persist($results);
            $this->info('Persisted');
        } else {
            var_dump($results);
        }
    }

    private function persist($records)
    {
        $backend = new Backends\Player();
        $skippedPlayers = [];
        $notNeededToUpdatedPlayers = [];
        $updatedPlayers = [];
        foreach ($records as $record) {
            $name = $record['player'];
            $points = $record['points'];
            if ($points == 0) {
                $skippedPlayers[] = $name;
                continue;
            }

            $player = $backend->getByName($name);
            if ($player === null) {
                $player = new Models\Player();
                $player->setName($record['player']);
            }

            if ($this->isNeededToUpdated($player, $record)) {
                $player->setDivision($record['division']);
                $player->setPoints($points);
                $player->setPosition($record['pos']);
                $player->save();
                $updatedPlayers[] = $name;
            } else {
                $notNeededToUpdatedPlayers[] = $name;
            }

        }
        \Log::info(sprintf('Updated %s players', count($updatedPlayers)));
        \Log::info(sprintf('%s players were not need to update', count($notNeededToUpdatedPlayers)));
        if (count($skippedPlayers) > 0) {
            \Log::warn(sprintf("Skipped %s players with 0 points", count($skippedPlayers)), $skippedPlayers);
        }

        Backends\Player::flush();

        $logger = new Logger();
        $logger->logPlayersWithChange($updatedPlayers);
    }

    /**
     * @param Models\Player $player
     * @param $record
     * @return boolean
     */
    private function isNeededToUpdated($player, $record)
    {
        return $player->getPoints() != $record['points'] ||
        $player->getPosition() != $record['pos'] ||
        $player->getDivision() != $record['division'];
    }

    /**
     * @param String $baseUrl
     * @param String $path
     * @return mixed
     */
    private function susoGet($baseUrl, $path) {
        $json = file_get_contents($baseUrl . $path);
        return json_decode($json, true);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['update', null, InputOption::VALUE_NONE, 'If this option is given database will be updated.', null],
        ];
    }
}

