<?php

namespace App\Http\Controllers;

use App\Backends;
use App\tmp\UserInfo;
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

    public function account()
    {
        // Check login status
        if ($_SESSION['logged'] !== true) {
            return redirect('/');
        }

        return $this->renderOnCanvas($this->renderAccount(), '/login');
    }

    /*
     * ACTIONS
     */

    public function loginCallback(Request $request)
    {
        $googleProvider = $this->getGoogleProvider($request);
        $googleProvider->stateless();
        $user = $googleProvider->user();
        $_SESSION['logged'] = true;
        $_SESSION['email'] = $user->getEmail();
        return redirect('/account');
    }

    public function loginAction()
    {
        $_SESSION['logged'] = true;
        $_SESSION['email'] = 'aaron@tuenti.com';
        return redirect('/account');
    }

    public function logoutAction()
    {
        session_unset();
        return redirect('/');
    }

    public function sellAction(Request $request)
    {
        // Check login status
        if ($_SESSION['logged'] !== true) {
            return redirect('/');
        }

        $stockId = $request->input('stockId');
        $amount = $request->input('amount');

        $playerBackend = new Backends\Player();
        $player = $playerBackend->getByName($stockId);

        $userBackend = new Backends\User();
        $user = $userBackend->getCurrentUser();

        $shareBackend = new Backends\Share();
        $share = $shareBackend->findByUserAndPlayer($user, $player);

        $api = new \App\Api\Share();
        $api->sell($share, $amount);

        return redirect('/account');
    }

    public function buyAction(Request $request)
    {
        // Check login status
        if ($_SESSION['logged'] !== true) {
            return redirect('/');
        }

        $stockId = $request->input('stockId');
        $amount = $request->input('amount');

        $playerBackend = new Backends\Player();
        $player = $playerBackend->getByName($stockId);

        $api = new \App\Api\Share();
        $api->buy($player, $amount);

        return redirect('/account');
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
        foreach ($allUsers->all() as $user) {
            $users[] = UserInfo::create($user->getUserName());
        }
        usort($users, function(UserInfo $a, UserInfo $b) {
            return ($b->capital + $b->wallet->getTotalValue()) - ($a->capital + $a->wallet->getTotalValue());
        });
        return view('ranking', ['users' => $users, 'userInfo' => $userInfo]);
    }

    private function renderAccount()
    {
        $userInfo = $this->getUserInfo();
        $playerBackend = new Backends\Player();
        $players = $playerBackend->getAll();
        $shareValueCalculator = new \App\Calculators\ShareValue();

        return view('account', ['userInfo' => $userInfo, 'players' => $players, 'shareValueCalculator' => $shareValueCalculator]);
    }

    private function getGoogleProvider($request) {
        return new \Laravel\Socialite\Two\GoogleProvider(
            $request,
            env('OAUTH_CLIENT_ID'),
            env('OAUTH_CLIENT_SECRET'),
            env('OAUTH_CLIENT_REDIRECT')
        );
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
}
