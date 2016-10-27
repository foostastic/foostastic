<?php

namespace App\Http\Controllers;

use App\Backends;
use App\Backends\User;
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
        if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
            return $this->ranking();
        } else {
            return $this->renderOnCanvas($this->renderAccount(), '/account');
        }
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
        if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
            return redirect('/');
        }

        return $this->renderOnCanvas($this->renderAccount(), '/account');
    }

    public function ranking()
    {
        return $this->renderOnCanvas($this->renderRanking(), '/ranking');
    }

    /*
     * ACTIONS
     */

    public function loginCallback(Request $request)
    {
        $googleProvider = $this->getGoogleProvider($request);
        $googleProvider->stateless();
        $user = $googleProvider->user();
        $email = strtolower($user->getEmail());

        return $this->doLogin($email);
    }

    public function fakeLoginAction()
    {
        if (env('APP_DEBUG') == true) {
            $this->doLogin('xxx@tuenti.com');
        } else {
            \Log::error("Tried to use /fakeLogin on production.");
            $this->flash('Fake login is only allowed on dev environment! Don\'t be evil! }8-)', 'danger');
        }
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
        if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
            return redirect('/');
        }

        $shareId = $request->input('shareId');
        $amount = $request->input('amount');

        $shareBackend = new Backends\Share();
        $share = $shareBackend->getById($shareId);
        if ($share == null) {
            \Log::error("Tried to sell invalid player.", ['shareId' => $shareId, 'user' => $_SESSION['email']]);
            $this->flash('Invalid operation on player.', 'danger');
            return redirect('/');
        }

        if ($share->getUser() !== $_SESSION['email']) {
            \Log::error("Hacking attempt! {$_SESSION['email']} trying to sell a share from {$share->getUser()}.", ['shareId' => $shareId, 'user' => $_SESSION['email']]);
            $this->flash('Hack @dpaneda detected. Don\'t be evil }8-) !<br/> <img src="https://dn3pm25xmtlyu.cloudfront.net/photos/large/726251546.gif?1359835175&Expires=1477640716&Signature=sWYZifXAal7I5fO4fHJ6mk-yc0tGYShhA0Lc5vKedKzOk2aYnLJ3HhwRn5vv5T6zRJprtZo0Rx0Ce-hhGLzP4j-NhG4Xoi-GbIFiF6bCAbvrVA7fXNtzVOGqFngOFCkcd1BXwxsdLufIAT2BZS4s8sKDYnKVSNS1rJlPZ~-Rfyw_&Key-Pair-Id=APKAIYVGSUJFNRFZBBTA">', 'danger');
            return redirect('/');
        }

        $api = new \App\Api\Share();
        $success = $api->sell($share, $amount);
        if ($success) {
            $this->flash('Sell operation completed successfully.', 'success');
        } else {
            $this->flash('Sell operation could not complete.', 'danger');
        }

        return redirect('/account');
    }

    public function buyAction(Request $request)
    {
        // Check login status
        if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
            return redirect('/');
        }

        $stockId = $request->input('stockId');
        $amount = $request->input('amount');

        $playerBackend = new Backends\Player();
        $player = $playerBackend->getByName($stockId);

        if ($player === null) {
            \Log::error("Tried to buy invalid player.", ['playerId' => $stockId, 'user' => $_SESSION['email']]);
            $this->flash('Invalid operation on player.', 'danger');
            return redirect('/');
        }

        $api = new \App\Api\Share();
        $success = $api->buy($player, $amount);
        if ($success) {
            $this->flash('Buy operation completed successfully.', 'success');
        } else {
            $this->flash('Buy operation could not complete. Check player availability and your credit.', 'danger');
        }

        return redirect('/account');
    }

    /*
     * PRIVATE METHODS
     */

    private function renderOnCanvas($content, $page)
    {
        return view('base',
            [
                'flashMessage' => $this->renderFlashMessage(),
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

    private function renderFlashMessage()
    {
        $message = null;
        $level = null;
        if (isset($_SESSION['flash_notification.message'])) {
            $message = $_SESSION['flash_notification.message'];
            $level = $_SESSION['flash_notification.level'];
            unset($_SESSION['flash_notification.message']);
        }
        return view('flashMessage', ['message' => $message, 'level' => $level]);
    }

    private function getGoogleProvider($request) {
        return new \Laravel\Socialite\Two\GoogleProvider(
            $request,
            env('OAUTH_CLIENT_ID'),
            env('OAUTH_CLIENT_SECRET'),
            env('OAUTH_CLIENT_REDIRECT')
        );
    }

    public function doLogin($email)
    {
        $userBackend = new User();
        $loginOrCreate = $userBackend->getByUsername($email);
        if ($loginOrCreate !== null) {
            $_SESSION['logged'] = true;
            $_SESSION['email'] = $email;
        } else {
            \Log::warn("Tried to create account for $email");
            $this->flash('Account creation is restricted to @tuenti.com domain. Sorry!', 'danger');
        }
        return redirect('/account');
    }

    private function flash($message, $level='info') {
        $_SESSION['flash_notification.message'] = $message;
        $_SESSION['flash_notification.level'] = $level;
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
