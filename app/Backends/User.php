<?php

namespace App\Backends;
use App\Models;

class User
{
    /**
     * @param $username
     * @return \App\Models\User|null
     */
    public function getByUsername($username)
    {
        $user = Models\User::where(Models\User::FIELD_USERNAME, $username)
            ->limit(1)
            ->get()
            ->first();
        if ($user === null) {
            $user = $this->create($username);
        }
        return $user;
    }

    /**
     * @param $username
     * @return \App\Models\User|null
     */
    public function create($username)
    {
        $user = new Models\User();
        $user->setCredit(env('INIT_CREDIT'));
        $user->setUserName($username);
        $user->save();
        return $user;
    }

}