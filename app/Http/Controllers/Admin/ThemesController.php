<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class ThemesController extends MainAdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('DemoAdmin', ['only' => ['handleActivation']]);
    }

    public function show()
    {
        $themes = $this->product_api->getThemes();

        if (!$themes) {
            Session::flash('error.message', trans("admin.themesnotavailable"));
            return redirect('/admin');
        }

        $themes = Arr::sort(
            $themes,
            function ($theme) {
                return get_buzzy_config('CurrentTheme') !== $theme['code'];
            }
        );

        return view('_admin.pages.themes', compact('themes'));
    }

    public function settings($theme, Request $request)
    {
        $themes = $this->product_api->getThemes();

        $theme_id = $request->query("t");

        if (!$themes || !isset($themes[$theme_id])) {
            Session::flash('error.message', trans("admin.themesnotavailable"));
            return redirect('/admin');
        }

        $theme = $themes[$theme_id];

        return view('_admin.pages.themesettings', compact('theme', 'themes'));
    }

    public function handleActivation(Request $request)
    {
        $item_id = $request->input('item_id');
        $item_code = $request->input('item_code');

        if (!$item_code) {
            return ['status' => 'error', 'message' => 'Not valid Theme'];
        }

        if ($item_id) {
            $response = $this->product_api->checkItemPurchase($item_id);
            if ($response['status'] == 'error') {
                return array_merge($response, ['reload' => true]);
            }
        }

        set_buzzy_config('CurrentTheme', $item_code);

        Cookie::queue(Cookie::forget('buzzy_theme'));

        return ['status' => 'success', 'message' => ''];
    }
}
