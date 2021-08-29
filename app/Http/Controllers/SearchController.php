<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Show search page
     *
     * @param  Request $req
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function index(Request $req)
    {
        $q = $req->query('q');

        $lastItems = Post::where(
            function ($query) use ($q) {
                $query->where("posts.title", "LIKE", "%$q%");

                $query->orWhereHas('entries', function ($query) use ($q) {
                    $query->where("entries.body", "LIKE", "%$q%");
                });
                $query->orWhereHas('tags', function ($query) use ($q) {
                    $query->where("tags.name", "LIKE", "%$q%");
                });
            }
        )
            ->byPublished()
            ->byLanguage()
            ->byApproved()
            ->paginate(10);

        if ($req->query('page')) {
            if ($req->ajax()) {
                return view('pages.catpostloadpage', compact('lastItems'));
            }
        }

        $search = trans('updates.searchfor', ['word' => $q]);

        return view('pages.showsearch', compact("lastItems", "search"));
    }

    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function searchUsers(Request $request)
    {
        $q = strip_tags($request->get('q'));
        $users = User::where('username', 'LIKE', "$q%")->take(10)->get();

        return response()->json($users);
    }
}
