<?php

namespace App\Http\Controllers;

use App\Backends;
use Illuminate\Support\Facades\Artisan;

class ObserverController extends Controller
{
    public function __construct() {}

    /*
     * VIEWS
     */

    public function onMatchFinished($password)
    {
        $privatePassword = env('PRIVATE_PASSWORD', false);
        if ($privatePassword) {
            if ($password == $privatePassword) {
                Artisan::call('suso:refresh', ['--update' => true]);
            }
        }
        return redirect('/');
    }
}
