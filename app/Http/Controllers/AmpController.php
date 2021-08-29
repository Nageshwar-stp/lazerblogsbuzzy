<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Support\Facades\Auth;

class AmpController extends Controller
{
    /**
     * Show a Amp Post
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (get_buzzy_config('p_amp') !== 'on') {
            return redirect('/');
        }

        $lastFeaturestop = Post::forHome('Features')->where('type', '!=', 'quiz')->where('type', '!=', 'poll')->byActiveTypes()->byPublished()->byLanguage()->byApproved()->byFeatured()->take(10)->get();
        //colums 2
        $lastNews =   Post::forHome()->where('type', '!=', 'quiz')->where('type', '!=', 'poll')->byActiveTypes()->byPublished()->byLanguage()->byApproved()->paginate(10);

        return view('amp.index', compact('lastFeaturestop',  'lastNews'));
    }

    /**
     * Show a Amp Post
     *
     * @return \Illuminate\View\View
     */
    public function post($catname, $id)
    {
        $post = Post::where('id', $id)->byPublished()->first();

        if (!$post || $post->type == 'quiz' || $post->type == 'poll') {
            abort(404);
        }

        if (get_buzzy_config('p_amp') !== 'on') {
            return redirect(generate_post_url($post));
        }

        if ($post->approve !== 'yes') {
            if (!Auth::check() || Auth::user()->usertype != 'Admin' && Auth::user()->id != $post->user->id) {
                abort(404);
            }
        }

        $entries = $post->entries();
        $entries =  $entries->where('type', '!=', 'answer')->orderBy('order', $post->ordertype == 'desc' ? 'desc' : 'asc')->get();

        $lastFeatures = Post::byType($post->type)->byActiveTypes()->byPublished()->byLanguage()->byApproved()->getStats('one_day_stats', 'DESC', 6)->get();

        return view("amp/post", compact('post', 'entries', 'lastFeatures'));
    }
}
