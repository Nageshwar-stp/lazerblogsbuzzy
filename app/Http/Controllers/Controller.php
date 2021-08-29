<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Managers\ThemeManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $theme = get_buzzy_theme();

        $this->setTheme($theme);
    }

    private function setTheme($theme)
    {
        app(ThemeManager::class)->init($theme);
    }
}
