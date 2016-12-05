<?php

namespace App\Http\Controllers;

use App\Api\UserInfoApi;
use App\Backends;
use App\Backends\User;
use App\Calculators\ShareValue;
use App\Services\FlashMessages\FlashService;
use App\Services\Rendering\RenderService;
use App\ViewModels\StockPurchase;
use App\ViewModels\UserInfo;
use App\ViewModels\UserShare;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var UserInfoApi
     */
    private $userInfoApi;
    /**
     * @var RenderService
     */
    private $renderService;
    /**
     * @var FlashService
     */
    private $flashService;
    /**
     * @var ShareValue
     */
    private $shareValueCalculator;

    /**
     * @param UserInfoApi $userInfoApi
     * @param RenderService $renderService
     * @param FlashService $flashService
     * @param ShareValue $shareValueCalculator
     */
    public function __construct(UserInfoApi $userInfoApi, RenderService $renderService, FlashService $flashService, ShareValue $shareValueCalculator)
    {
        $this->userInfoApi = $userInfoApi;
        $this->renderService = $renderService;
        $this->flashService = $flashService;
        $this->shareValueCalculator = $shareValueCalculator;
    }

    /*
     * VIEWS
     */

    public function index()
    {
        if (!$this->userInfoApi->isLogged()) {
            return $this->ranking();
        }
        return $this->account();
    }

    public function account()
    {
        // Check login status
        if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
            return redirect('/');
        }

        return $this->renderService->renderOnCanvas($this->getUserInfo(), $this->renderAccount(), '/account');
    }

    public function ranking()
    {
        return $this->renderService->renderOnCanvas($this->getUserInfo(), $this->renderRanking(), '/ranking');
    }

    /*
     * ACTIONS
     */

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
        $email = strtolower($user->getEmail());

        $this->userInfoApi->login($email);
        return redirect('/account');
    }

    public function fakeLoginAction()
    {
        if (env('APP_DEBUG') == true) {
            $this->userInfoApi->login('xxx@tuenti.com');
        } else {
            \Log::error("Tried to use /fakeLogin on production.");
            $this->flashService->flash('Fake login is only allowed on dev environment! Don\'t be evil! }8-)', 'danger');
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
            $this->flashService->flash('Invalid operation on player.', 'danger');
            return redirect('/');
        }

        if ($share->getUser() !== $_SESSION['email']) {
            \Log::error("Hacking attempt! {$_SESSION['email']} trying to sell a share from {$share->getUser()}.", ['shareId' => $shareId, 'user' => $_SESSION['email']]);
            $this->flashService->flash('Hack @dpaneda detected. Don\'t be evil }8-) !<br/> <img src="https://dn3pm25xmtlyu.cloudfront.net/photos/large/726251546.gif?1359835175&Expires=1477640716&Signature=sWYZifXAal7I5fO4fHJ6mk-yc0tGYShhA0Lc5vKedKzOk2aYnLJ3HhwRn5vv5T6zRJprtZo0Rx0Ce-hhGLzP4j-NhG4Xoi-GbIFiF6bCAbvrVA7fXNtzVOGqFngOFCkcd1BXwxsdLufIAT2BZS4s8sKDYnKVSNS1rJlPZ~-Rfyw_&Key-Pair-Id=APKAIYVGSUJFNRFZBBTA">', 'danger');
            return redirect('/');
        }

        $api = new \App\Api\Share();
        $success = $api->sell($share, $amount);
        if ($success) {
            $this->flashService->flash('Sell operation completed successfully.', 'success');
        } else {
            $this->flashService->flash('Sell operation could not complete.', 'danger');
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
            $this->flashService->flash('Invalid operation on player.', 'danger');
            return redirect('/');
        }

        $api = new \App\Api\Share();
        $success = $api->buy($player, $amount);
        if ($success) {
            $this->flashService->flash('Buy operation completed successfully.', 'success');
        } else {
            $this->flashService->flash('Buy operation could not complete. Check player availability and your credit.', 'danger');
        }

        return redirect('/account');
    }

    /*
     * PRIVATE METHODS
     */

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

        $userShares = $this->getUserShares($userInfo);

        return view('account', ['userShares' => $userShares, 'players' => $players, 'shareValueCalculator' => $this->shareValueCalculator]);
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
     * DATA COLLECTION / FORMAT
     */

    /**
     * @return UserInfo
     */
    private function getUserInfo()
    {
        // TODO This could be cached
        return $this->userInfoApi->getUserInfo();
    }

    /**
     * @param $userInfo
     * @return UserShare[]
     */
    private function getUserShares(UserInfo $userInfo)
    {
        $userShares = [];
        foreach ($userInfo->wallet->getAllByStock() as $stockId => $purchases) {
            /** @var StockPurchase $purchase */
            foreach ($purchases as $purchase) {
                $userShare = new UserShare();
                $userShare->playerName = $stockId;
                $userShare->currentPrice = $this->shareValueCalculator->getValueForPlayerName($stockId) * $purchase->purchaseAmount;
                $userShare->amount = $purchase->purchaseAmount;
                $userShare->buyPrice = $purchase->purchaseAmount * $purchase->purchaseValue;
                $userShare->difference = $userShare->currentPrice - $userShare->buyPrice;
                $userShare->percentage = $userShare->difference * 100 / $userShare->buyPrice;
                $userShare->shareId = $purchase->shareId;
                $userShares[] = $userShare;
            }
        }
        return $userShares;
    }
}
