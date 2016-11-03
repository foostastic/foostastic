<?php
namespace App\Api;

use App\Backends\User;
use App\Services\FlashMessages\FlashService;
use App\tmp\UserInfo;

class UserInfoApi
{
    /**
     * @var User
     */
    private $userBackend;
    /**
     * @var FlashService
     */
    private $flashService;

    /**
     * @param User $userBackend
     * @param FlashService $flashService
     */
    public function __construct(User $userBackend, FlashService $flashService)
    {
        $this->userBackend = $userBackend;
        $this->flashService = $flashService;
    }


    /**
     * @return bool
     */
    public function isLogged()
    {
        return isset($_SESSION['logged']) && $_SESSION['logged'] === true;
    }

    /**
     * @return UserInfo
     */
    public function getUserInfo()
    {
        if ($this->isLogged()) {
            return UserInfo::create($_SESSION['email']);
        }
        return UserInfo::unknown();
    }

    /**
     * @param string $email
     * @return bool
     */
    public function login($email)
    {
        $loginOrCreate = $this->userBackend->getByUsername($email);
        if ($loginOrCreate !== null) {
            $_SESSION['logged'] = true;
            $_SESSION['email'] = $email;
            return true;
        } else {
            \Log::warn("Tried to create account for $email");
            $this->flashService->flash('Account creation is restricted to @tuenti.com domain. Sorry!', 'danger');
            return false;
        }
    }
}