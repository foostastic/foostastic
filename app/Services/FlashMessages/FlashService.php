<?php

namespace App\Services\FlashMessages;

class FlashService
{
    public function flash($message, $level='info') {
        $_SESSION['flash_notification.message'] = $message;
        $_SESSION['flash_notification.level'] = $level;
    }

    public function popMessage()
    {
        $message = null;
        $level = null;
        if (isset($_SESSION['flash_notification.message'])) {
            $message = $_SESSION['flash_notification.message'];
            $level = $_SESSION['flash_notification.level'];
            unset($_SESSION['flash_notification.message']);
        }
        return [$message, $level];
    }
}