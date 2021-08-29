<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Show Tags
     *
     * @param  $catname
     * @param  Request $req
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function index($tagname)
    {
        $tag = Tag::byType('post_tag')->where("slug", $tagname)->firstOrFail();
        $lastItems = $tag->posts()->byPublished()->byLanguage()->byApproved()->paginate(15);
        $tagname = $tag->name;

        return view("pages.showtag", compact("lastItems", "tagname"));
    }

    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function search(Request $request)
    {
        $q = strip_tags($request->get('q'));
        $tags = Tag::byType('post_tag')->where('name', 'LIKE', "$q%")->take(10)->get();

        return response()->json($tags);
    }
}
