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

    /**
     * @return Models\User|null
     */
    public function getCurrentUser()
    {
        if (!isset($_SESSION['email'])) {
            return null;
        }
        $username = $_SESSION['email'];
        return $this->getByUsername($username);
    }

}