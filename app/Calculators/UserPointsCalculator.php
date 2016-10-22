<?php

namespace App\Calculators;
use App\Models\Player;
use App\Models\Share;
use App\Backends;
use App\Models\User;

class UserPointsCalculator
{
    /**
     * @param User $user
     * @return int
     */
    public function getPointsForUser($user)
    {
        $shareValueCalculator = new ShareValue();
        $shareBackend = new Backends\Share();
        $shares = $shareBackend->getByUser($user);
        $totalPoints = $user->getCredit();
        foreach($shares->getIterator() as $share) {
            $totalPoints += $shareValueCalculator->getValueForShare($share);
        }
        return $totalPoints;
    }

    public function getPointsForUserName($userName)
    {
        $userBackend = new Backends\User();
        $user = $userBackend->getByUsername($userName);
        return $user !== null ? $this->getPointsForUser($user) : 0;
    }
}