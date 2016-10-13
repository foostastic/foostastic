<?php

namespace App\Http\Controllers;

use App\tmp\Market;
use App\tmp\Stock;
use App\tmp\UserInfo;

class HomeController extends Controller
{
    public function __construct() {}

    /*
     * VIEWS
     */

    public function index()
    {
        return $this->renderOnCanvas($this->renderRanking(), '/home');
    }

    public function login()
    {
        return $this->renderOnCanvas($this->renderLoginForm(), '/login');
    }

    public function account()
    {
        return $this->renderOnCanvas($this->renderAccount(), '/login');
    }

    /*
     * ACTIONS
     */

    public function loginAction()
    {
        $_SESSION['logged'] = true;
        return redirect('/account');
        // Check data and redirect
    }

    public function logoutAction()
    {
        session_unset();
        return redirect('/');
    }

    public function sellAction()
    {
        // TODO Implement selling process
        return redirect('/');
    }

    public function buyAction()
    {
        // TODO Implement buying process
        return redirect('/');
    }

    /*
     * PRIVATE METHODS
     */

    private function renderOnCanvas($content, $page)
    {
        return view('base',
            [
                'navbar' => $this->renderNavbar($this->getUserInfo(), $page),
                'content' => $content
            ]
        );
    }

    /**
     * @param $userInfo
     * @return \Illuminate\View\View
     */
    private function renderNavbar($userInfo, $page)
    {
        $navbarInfo = [
            'page' => $page,
            'userInfo' => $userInfo,
        ];
        return view('navbar', $navbarInfo);
    }

    /**
     * @return \Illuminate\View\View
     */
    private function renderRanking()
    {
        $userInfo = $this->getUserInfo();
        $users = [
            UserInfo::create('edu'),
            UserInfo::create('aaron'),
            UserInfo::create('jaguerra'),
        ];
        return view('ranking', ['users' => $users, 'userInfo' => $userInfo]);
    }

    private function renderLoginForm()
    {
        return view('login');
    }

    private function renderAccount()
    {
        $userInfo = $this->getUserInfo();
        return view('account', ['userInfo' => $userInfo, 'market' => $this->getMarket()]);
    }

    /*
     * DATA COLLECTION
     */

    /**
     * @return UserInfo
     */
    private function getUserInfo()
    {
        if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
            return UserInfo::create('aaron');
        }
        return UserInfo::unknown();
    }

    private function getMarket()
    {
        return Market::random();
    }
}
