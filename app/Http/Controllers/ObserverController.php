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

    public function onMatchFinished()
    {
        Artisan::call('crawl:foos');
        return redirect('/');
    }
}
