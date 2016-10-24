<?php

namespace App\Backends;
use App\Models;

class UserLogBackend
{

    /**
     * @param $username
     * @return \App\Models\UserLog[]
     */
    public function getByUsername($username)
    {
        $users = Models\UserLog::where(Models\UserLog::FIELD_USERNAME, $username)
            ->orderBy(Models\UserLog::FIELD_LOG_TIME, 'desc')
            ->get();
        return $users;
    }

    /**
     * @param Models\User $user
     * @return \App\Models\UserLog
     */
    public function log($user, $points)
    {
        $userLog = new Models\UserLog();
        $userLog->setPoints($points);
        $userLog->setUserName($user->getUserName());
        $userLog->setCredit($user->getCredit());
        $userLog->save();
        return $userLog;
    }

}
