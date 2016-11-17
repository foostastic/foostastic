<?php

namespace App\Backends;
use App\Models;

class UserLogBackend
{

    /**
     * @param string $username
     * @return \App\Models\UserLog[]
     */
    public function getByUsername($username)
    {
        $logs = Models\UserLog::where(Models\UserLog::FIELD_USERNAME, $username)
            ->orderBy(Models\UserLog::FIELD_LOG_TIME, 'desc')
            ->get();
        return $logs;
    }

    /**
     * @param string $username
     * @return \App\Models\UserLog|null
     */
    public function getLastChange($username, $currentPoints)
    {
        return Models\UserLog::query()->where(Models\UserLog::FIELD_USERNAME, $username)
            ->where(Models\UserLog::FIELD_POINTS, '!=', $currentPoints)
            ->orderBy(Models\UserLog::FIELD_LOG_TIME, 'desc')
            ->first();
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
