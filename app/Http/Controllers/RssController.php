<?php

namespace App\Http\Controllers;

use App\Post;
use App\Category;
use Illuminate\Support\Facades\Session;

class RssController extends Controller
{

    public function index($type)
    {
        if ($type == 'googlenews') {
            $posts = Post::byPublished()->byLanguage(request()->get('lang'))->byApproved()->limit(500)->get();

            return response()->view('vendor.googlenews', compact('posts'))->header('Content-Type', 'application/xml');
        }

        if ($type == 'sitemap') {
            $posts = Post::byPublished()->byLanguage(request()->get('lang'))->byApproved()->limit(500)->get();
            $categories = Category::byLanguage(request()->query('lang'))->get();

            return  response()->view('vendor.sitemap', compact('posts', 'categories'))->header('Content-Type', 'application/xml');
        }

        $posts = $this->getdata($type);

        if (!$posts) {
            Session::flash('error.message',  trans('index.emptyplace'));
            return redirect()->back();
        }

        return  response()->view('vendor.rss', compact('posts'))->header('Content-Type', 'application/xml');
    }

    public function fbinstant()
    {
        $posts = Post::where('type', '!=', 'quiz')->byPublished()->byLanguage(request()->get('lang'))->byApproved()->limit(150)->get();

        return  response()->view('vendor.instant-rss', compact('posts'))->header('Content-Type', 'application/xml');
    }

    public function getdata($type)
    {
        if ($type == 'index') {
            $posts = Post::byPublished()->byLanguage(request()->get('lang'))->byApproved()->limit(50)->get();
        } elseif ($type == 'top-today') {
            $posts    = Post::forHome()->byActiveTypes()->getStats('one_day_stats', 'DESC', 10)->byPublished()->byLanguage(request()->get('lang'))->byApproved()->get();
        } else {
            $category = Category::where("name_slug", $type)->first();

            if (!isset($category)) {
                return redirect('/');
            }

            $posts = Post::byCategoryRecursively($category->id)
                ->byPublished()->byLanguage(request()->get('lang'))->byApproved()->take(50)->get();
        }

        return $posts;
    }

    public function json($type)
    {
        if (is_int(intval($type))) {
            $category_id = intval($type);
        } else {
            $category = Category::where("name_slug", $type)->first();

            if (!isset($category)) {
                return response()->json([]);
            }
            $category_id = $category->id;
        }

        $posts = Post::byCategoryRecursively($category_id)
            ->byPublished()->byLanguage(request()->get('lang'))->byApproved()->take(6)->get();

        if (!$posts) {
            return response()->json([]);
        }
        foreach ($posts as $key => $post) {
            $posts[] = array('slug' => generate_post_url($post), 'title' => $post->title, 'thumb' =>  makepreview($post->thumb, 's', 'posts'));
        }

        return response()->json($posts);
    }
}
