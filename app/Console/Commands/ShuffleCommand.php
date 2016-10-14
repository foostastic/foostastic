<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Backends;
use App\Models;

class ShuffleCommand extends Command
{
    protected $name = 'foos:shuffle';
    protected $description = "---";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $playerBackend = new Backends\Player();
        /** @var Models\Player $player */
        foreach ($playerBackend->getAll() as $player) {
            $player->setPoints(mt_rand(50, 280));
            $player->save();
        }
        $this->info('Shuffled');
    }

}

