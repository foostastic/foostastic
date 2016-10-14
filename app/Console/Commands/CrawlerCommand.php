<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DomCrawler\Crawler;
use App\Backends;
use App\Models;

/**
 * Class CrawlerCommand
 * @package App\Console\Commands
 * @see https://github.com/laravel/lumen-framework/blob/5.0/src/Console/Commands/ServeCommand.php
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
        $html = file_get_contents('http://www.suso.eu/foos/ajax/summary/3');
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
        $backend->clearAll();
        foreach($records as $record) {
            $player = new Models\Player();
            $player->setName($record['player']);
            $player->setDivision($record['division']);
            $player->setPoints($record['points']);
            $player->setPosition($record['pos']);
            $player->save();
        }
    }

}

