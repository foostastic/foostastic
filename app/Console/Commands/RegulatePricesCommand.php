<?php

namespace App\Console\Commands;

use App\Calculators\ShareValue;
use Illuminate\Console\Command;
use App\Backends;
use App\Models;
use Symfony\Component\Console\Input\InputOption;

class RegulatePricesCommand extends Command
{
    protected $name = 'wallets:regulate';
    protected $description = "Regulate all wallets with current players price. Returning money when needed.";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $calculator = new ShareValue();
        $userBackend = new Backends\User();
        $this->info("Starting regulation...");
        /** @var Models\Share $share */
        foreach (Models\Share::all() as $share) {
            $buyPrice = $share->getBuyPrice();
            $username = $share->getUser();
            $user = $userBackend->getByUsername($username);
            $currentCredit = $user->getCredit();
            $newPrice = $calculator->getValueForPlayerName($share->getPlayer());
            $newCredit = $currentCredit + $buyPrice - $newPrice;
            $this->info("User: {$username} with $currentCredit | {$share->getPlayer()} $buyPrice -> $newPrice | New credit: $newCredit");
            // Updating values
            if ($this->option('update')) {
                $share->setBuyPrice($newPrice);
                $share->save();
                $user->setCredit($newCredit);
                $user->save();
                $this->info("Updated!");
            }
        }
        $this->info('Completed');
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

