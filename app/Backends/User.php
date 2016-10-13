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
        return Models\User::where(Models\User::FIELD_USERNAME, $username)
            ->limit(1)
            ->get()
            ->first();
    }

}