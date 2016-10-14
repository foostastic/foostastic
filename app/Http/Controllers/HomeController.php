<?php

namespace App\Http\Controllers;

use App\tmp\Market;
use App\tmp\Stock;
use App\tmp\UserInfo;
use App\Backends;
use Illuminate\Http\Request;
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

    public function login(Request $request)
    {
        $googleProvider = $this->getGoogleProvider($request);
        $googleProvider->scopes(["profile", "email", "openid", "https://www.googleapis.com/auth/plus.login", "https://www.googleapis.com/auth/plus.me", ]);
        $googleProvider->stateless();
        return $googleProvider->redirect();
    }

    public function loginCallback(Request $request)
    {
        $googleProvider = $this->getGoogleProvider($request);
        $googleProvider->stateless();
        $user = $googleProvider->user();
        $_SESSION['logged'] = true;
        $_SESSION['email'] = $user->getEmail();
        return redirect('/account');
    }

    private function getGoogleProvider($request) {
        return new \Laravel\Socialite\Two\GoogleProvider(
            $request,
            env('OAUTH_CLIENT_ID'),
            env('OAUTH_CLIENT_SECRET'),
            env('OAUTH_CLIENT_REDIRECT')
        );
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
        $_SESSION['email'] = 'aaron@tuenti.com';
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
        $backend = new Backends\User();
        $allUsers = $backend->getAll();
        $users = [];
        foreach ($allUsers as $user) {
            $users[] = UserInfo::create($user->getName());
        }
        usort($users, function(UserInfo $a, UserInfo $b) {
            return ($b->capital + $b->wallet->getTotalValue()) - ($a->capital + $a->wallet->getTotalValue());
        });
        return view('ranking', ['users' => $users, 'userInfo' => $userInfo]);
    }

    private function renderLoginForm()
    {
        return view('login');
    }

    private function renderAccount()
    {
        $userInfo = $this->getUserInfo();
        $playerBackend = new Backends\Player();
        $players = $playerBackend->getAll();
        $shareValueCalculator = new \App\Calculators\ShareValue();

        return view('account', ['userInfo' => $userInfo, 'players' => $players, 'shareValueCalculator' => $shareValueCalculator]);
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
            return UserInfo::create($_SESSION['email']);
        }
        return UserInfo::unknown();
    }

    private function getMarket()
    {
        return Market::random();
    }
}
