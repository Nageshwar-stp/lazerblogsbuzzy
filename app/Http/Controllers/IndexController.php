<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Support\Facades\Cookie;

class IndexController extends Controller
{
    public function index()
    {
        $HomeColSec1Tit1 = null;
        $HomeColSec2Tit1 = null;
        $HomeColSec3Tit1 = null;
        $CurrentTheme = get_buzzy_config('CurrentTheme');
        $HomeColSec1Type1 = config('buzzytheme_' . $CurrentTheme . '.HomeColSec1Type1', '["list", "quiz"]');
        $HomeColSec2Type1 = config('buzzytheme_' . $CurrentTheme . '.HomeColSec2Type1', '["news"]');
        $HomeColSec3Type1 = config('buzzytheme_' . $CurrentTheme . '.HomeColSec3Type1', '["video"]');

        if (get_buzzy_config('p_homepagebuilder') == "on") {
            $HomeColSec1Tit1 = get_buzzy_config('HomeColSec1Tit1');
            $HomeColSec2Tit1 = get_buzzy_config('HomeColSec2Tit1');
            $HomeColSec3Tit1 = get_buzzy_config('HomeColSec3Tit1');
            $HomeColSec1Type1 = get_buzzy_config('HomeColSec1Type1', $HomeColSec1Type1);
            $HomeColSec2Type1 = get_buzzy_config('HomeColSec2Type1', $HomeColSec2Type1);
            $HomeColSec3Type1 = get_buzzy_config('HomeColSec3Type1', $HomeColSec3Type1);
        }

        // Colums 1
        $lastFeatures = Post::forHome()->byAcceptedTypes($HomeColSec1Type1)->byActiveTypes()->byPublished()->byLanguage()->byApproved()->paginate(10);

        // Colums 1 - Latest Videos
        $lastvideoscol1  = Post::forHome()->byType('video')->byActiveTypes()->byPublished()->byLanguage()->byApproved()->getStats('one_day_stats', 'DESC')->paginate(3);

        // Colums 1 - Latest Polls
        $lastpoll        = Post::forHome()->byType('poll')->byActiveTypes()->byPublished()->byLanguage()->byApproved()->paginate(2);

        // Colums 2
        $lastNews = Post::forHome()->byAcceptedTypes($HomeColSec2Type1)->byActiveTypes()->byPublished()->byLanguage()->byApproved()->paginate(config('buzzytheme_' . $CurrentTheme . '.homepage_news_limit'));

        if (request()->query('page')) {
            if (request()->ajax()) {
                if (request()->query("timeline") == "right") {
                    return view('pages.indexrightpostloadpage', compact('lastNews'));
                } else {
                    return view('pages.indexpostloadpage', compact('lastFeatures', 'lastvideoscol1', 'lastpoll'));
                }
            } else {
                return redirect('/');
            }
        } else {
            if (Post::count() < 1) {
                return view('errors.starting');
            }
        }

        // Featured Posts
        $lastFeaturestop = Post::forHome('Features')->byActiveTypes()->byPublished()->byLanguage()->byApproved()->byFeatured()->take(10)->get();

        // Colums 3
        $lastTrendingVideos = Post::forHome()->byAcceptedTypes($HomeColSec3Type1)->byActiveTypes()->byPublished()->byLanguage()->byApproved()->take(10)->get();

        // Trending Posts
        $lastTrending    = Post::forHome()->byActiveTypes()->getStats('one_day_stats', 'DESC', 10)->byPublished()->byLanguage()->byApproved()->getCached('home_trending', now()->addMinutes(5));

        return view(
            'pages.index',
            compact(
                'lastFeaturestop',
                'lastFeatures',
                'lastvideoscol1',
                'lastpoll',
                'lastNews',
                'lastTrending',
                'lastTrendingVideos',
                'HomeColSec1Tit1',
                'HomeColSec2Tit1',
                'HomeColSec3Tit1'
            )
        );
    }

    public function changeLanguage($locale)
    {
        if (array_key_exists($locale, get_active_languages())) {
            Cookie::queue('buzzy_locale', $locale, 9999999, '/');
            app()->setLocale($locale);
        }

        return redirect()->back();
    }
}
