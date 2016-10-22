<?php


namespace App\Backends;
use App\Models;

class Share
{
    /**
     * @param Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByUser(Models\User $user)
    {
        return Models\Share::where('user', $user->getUserName())
            ->get();
    }

    /**
     * @param $id
     * @return Models\Share|null
     */
    public function getById($id)
    {
        return Models\Share::find($id);
    }

    public function buy(Models\User $user, Models\Player $player, $amount, $price)
    {
        $share = new Models\Share();
        $share->setPlayer($player->getName());
        $share->setUser($user->getUserName());
        $share->setAmount($amount);
        $share->setBuyPrice($price);
        $share->save();
    }

    public function sell(Models\Share $share, $amount = 1)
    {
        $share->setAmount($share->getAmount() - $amount);
        if ($share->getAmount() > 0) {
            $share->save();
        } else {
            $share->delete();
        }
    }

    /**
     * @param Models\User $user
     * @param Models\Player $player
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByUserAndPlayer(Models\User $user, Models\Player $player)
    {
        return Models\Share::where(Models\Share::FIELD_USER, $user->getUserName())
            ->where(Models\Share::FIELD_PLAYER, $player->getName())
            ->get();
    }

    /**
     * @param Models\Player $player
     * @return int
     */
    public function getPlayerAmountStock(Models\Player $player)
    {
        $shares = Models\Share::where(Models\Share::FIELD_PLAYER, $player->getName())
            ->get()->all();
        /* @var $shares \App\Models\Share[] */
        $totalAmount = 0;
        foreach ($shares as $share) {
            $totalAmount += $share->getAmount();
        }
        return max(0, env('AVAILABLE_STOCK') - $totalAmount);
    }

}