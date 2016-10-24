<?php

namespace App\Console\Commands;

use App\Api\Logger;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DomCrawler\Crawler;
use App\Backends;
use App\Models;

/**
 * Class CrawlerCommand
 * @package App\Console\Commands
 * @see     https://github.com/laravel/lumen-framework/blob/5.0/src/Console/Commands/ServeCommand.php
 */
class CrawlerCommand extends Command
{
	protected $name = 'crawl:foos';
	protected $description = "Crawl and update foos player positions";

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$url = env('FOOS_RESULTS_URL', false);
		if (!$url) {
			$this->error('Missing crawler URL config');
			return;
		}
		$html = file_get_contents($url);
		$crawler = new Crawler($html);
		$results = array();
		$crawler->filter('div.col-md-4')
			->reduce(function (Crawler $node, $i) use (&$results) {
				$title = $node->filter('h4')->text();
				$matches = array();
				preg_match('/.* ([0-9]+)/', $title, $matches);
				$division = (int)$matches[1];
				$rows = $node->filter('tr')
					->reduce(function (Crawler $node, $i) {
						return count($node->filter('td')) === 3;
					});
				$rows->each(function (Crawler $node, $i) use ($division, &$results) {
					$pos = (int)$node->filter('td')->eq(0)->text();
					$player = utf8_decode($node->filter('td')->eq(1)->text());
					$points = (int)$node->filter('td')->eq(2)->text();
					$results[] = array(
						'division' => $division,
						'pos' => $pos,
						'player' => $player,
						'points' => $points,
					);
				});
			});
		$this->info(sprintf('Found %s players', count($results)));
		if (count($results) > 0) {
			$this->persist($results);
			$this->info('Persisted');
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
		return $player->getPoints() != $record['pos'] ||
		$player->getPosition() != $record['position'] ||
		$player->getDivision() != $record['division'];
	}

}

