<?php

namespace App\Http\Controllers;

use App\Followers;
use App\Post;
use App\User;
use App\Managers\UploadManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBaseController extends Controller
{
    public $user;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('DemoAdmin', ['only' => ['updatesettings']]);

        $user_slug = request()->segment('2');

        $userinfo = User::findByUsernameOrFail($user_slug);

        if (!$userinfo) {
            abort('404');
        }

        $this->user = $userinfo;

        $newscount = $this->user->posts()->byType('news')->byApproved()->count();
        $listscount = $this->user->posts()->byType('list')->byApproved()->count();
        $quizzescount = $this->user->posts()->byType('quiz')->byApproved()->count();
        $pollscount = $this->user->posts()->byType('poll')->byApproved()->count();
        $videoscount = $this->user->posts()->byType('video')->byApproved()->count();

        \View::share(compact('userinfo', 'newscount', 'listscount', 'quizzescount', 'pollscount', 'videoscount'));
    }
}
