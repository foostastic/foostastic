<?php

namespace App\Services\Rendering;

use App\Services\FlashMessages\FlashService;
use App\ViewModels\UserInfo;

class RenderService
{
    /**
     * @var FlashService
     */
    private $flashService;


    /**
     * @param FlashService $flashService
     */
    public function __construct(FlashService $flashService)
    {
        $this->flashService = $flashService;
    }

    public function renderOnCanvas(UserInfo $userInfo, $content, $page)
    {
        return view('base',
            [
                'flashMessage' => $this->renderFlashMessage(),
                'motd' => $this->renderMotd(),
                'navbar' => $this->renderNavbar($userInfo, $page),
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

    private function renderMotd()
    {
        $message = env('MOTD', null);
        $level = env('MOTD_LEVEL', 'info');
        return view('motd', ['message' => $message, 'level' => $level]);
    }

    private function renderFlashMessage()
    {
        list($message, $level) = $this->flashService->popMessage();
        return view('flashMessage', ['message' => $message, 'level' => $level]);
    }
}