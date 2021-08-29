<?php

namespace App\Http\Controllers\Admin;

use App\Post;
use Carbon\Carbon;
use App\Category;
use App\Events\PostUpdated;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Session;

class PostsController extends MainAdminController
{
    public function __construct()
    {
        $this->middleware('DemoAdmin', ['only' => ['approvePost', 'setForHomepage', 'setFeatured', 'deletePost', 'forceDeletePost']]);

        parent::__construct();
    }

    public function features()
    {
        $data_url = action("Admin\\PostsController@getdata", [
            'type' => 'features',
        ]);

        return view('_admin.pages.posts')->with(['title' => trans("admin.FeaturesPosts"), 'desc' => '', 'type' => 'features', 'data_url' => $data_url]);
    }

    public function unapprove()
    {
        $data_url = action("Admin\\PostsController@getdata", [
            'type' => 'all',
            'only' => 'unapprove',
        ]);

        return view('_admin.pages.posts')->with(['title' => trans("admin.Posts"), 'desc' => '', 'type' => 'all', 'data_url' => $data_url]);
    }

    public function all(Request $request)
    {
        $data_url = action("Admin\\PostsController@getdata", [
            'type' => 'all',
            'only' => $request->query('only'),
        ]);

        return view('_admin.pages.posts')->with(['title' => trans("admin.AllPosts"), 'desc' => '', 'type' => 'all', 'data_url' => $data_url]);
    }

    public function showcatposts(Request $request, $name)
    {
        $cats = Category::where("name_slug", $name)->first();

        if (!$cats) {
            return redirect()->back();
        }

        $data_url = action("Admin\\PostsController@getdata", [
            'type' => 'category',
            'category_id' => $cats->id,
            'only' => $request->query('only'),
        ]);

        return view('_admin.pages.posts')->with(['title' => $cats->name, 'desc' => $cats->name, 'type' => 'category', 'type' => 'category', 'data_url' => $data_url]);
    }

    public function approvePost(Request $request)
    {
        $ids = explode(',', $request->get('ids'));
        $action = $request->get('action');

        foreach ($ids as $id) {
            $post = Post::find($id);

            if ($post) {
                $post->approve = $action;
                $post->save();

                if ($post->approve == 'no') {
                    event(new PostUpdated($post, 'Approved'));
                }
            }
        }

        if ($request->wantsJson()) {
            return new Response('', 204);
        }

        Session::flash('success.message', "");

        return redirect()->back();
    }


    public function setForHomepage(Request $request)
    {
        $ids = explode(',', $request->get('ids'));
        $action = $request->get('action');

        foreach ($ids as $id) {
            $post = Post::find($id);

            if ($post) {
                $post->show_in_homepage = $action == 'no' ? null : 'yes';
                $post->save();
            }
        }
        if ($request->wantsJson()) {
            return new Response('', 204);
        }

        Session::flash('success.message', trans("admin.ChangesSaved"));

        return redirect()->back();
    }

    public function setFeatured(Request $request)
    {
        $ids = explode(',', $request->get('ids'));
        $action = $request->get('action');

        foreach ($ids as $id) {
            $post = Post::find($id);

            if ($post) {
                $post->featured_at = $action == 'no' ? null : Carbon::now();
                $post->save();
            }
        }
        if ($request->wantsJson()) {
            return new Response('', 204);
        }

        Session::flash('success.message', trans("admin.ChangesSaved"));

        return redirect()->back();
    }

    public function deletePost(Request $request)
    {
        $ids = explode(',', $request->get('ids'));
        $action = $request->get('action');

        foreach ($ids as $id) {
            $post = Post::withTrashed()->find($id);

            if ($post) {
                if ($action == 'remove') {
                    $post->approve = 'no';
                    $post->delete();
                    event(new PostUpdated($post, 'Trash'));
                } else {
                    $post->approve = 'yes';
                    $post->restore();
                }
            }
        }
        if ($request->wantsJson()) {
            return new Response('', 204);
        }

        Session::flash('success.message', trans("admin.ChangesSaved"));

        return redirect()->back();
    }

    public function forceDeletePost(Request $request)
    {
        $ids = explode(',', $request->get('ids'));

        try {
            foreach ($ids as $id) {
                $post = Post::withTrashed()->find($id);

                if ($post->deleted_at !== null) {
                    event(new PostUpdated($post, 'Trash'));
                }
                $post->forceDelete();
            }
        } catch (\Exception $e) {
        }
        if ($request->wantsJson()) {
            return new Response('', 204);
        }

        Session::flash('success.message', trans("admin.Deletedpermanently"));

        return redirect()->back();
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getdata(Request $request)
    {
        $typew = $request->query('type');
        $type = $typew;

        $only = $request->query('only');

        $post = Post::leftJoin('users', 'posts.user_id', '=', 'users.id');
        $post->select('posts.*');

        if ($typew == 'all') {
            //not set
        } elseif ($typew === 'category') {
            $category_id = $request->query('category_id');
            $post->byCategoryRecursively($category_id);
        } elseif ($typew !== 'features') {
            $post->where('type', $type);
        } else {
            $post->whereNotNull("featured_at");
        }

        if ($only == 'deleted') {
            $post->onlyTrashed();
        } else {
            $post->whereNull('deleted_at');
        }

        if ($only == 'unapprove') {
            $post->approve('no');
        } else {
            $post->byApproved();
        }

        return Datatables::of($post)
            ->addColumn('selection', function ($post) {
                return '<input type="checkbox" name="selection[]" value="' . $post->id . '">';
            })
            ->editColumn('thumb', function ($post) {
                return '<img src="' . makepreview($post->thumb, 's', 'posts') . '" width="125">';
            })
            ->editColumn('title', function ($post) {
                return '<a href="' . generate_post_url($post) . '" target=_blank style="font-size:16px;font-weight: 600">
                        ' . $post->title . '
                        </a>
                        <div class="product-meta"></div>
                    ';
            })
            ->editColumn('user', function ($post) {
                return $post->user ? '<div  style="font-weight: 400;color:#aaa">
                                        <a href="/profile/' . $post->user->username_slug . '" target="_blank"><img src="' . makepreview($post->user->icon, 's', 'members/avatar') . '" width="32" style="margin-right:6px">' . $post->user->username . '</a>
                                </div>' : '';
            })
            ->addColumn('approve', function ($post) {

                if ($post->deleted_at !== null) {
                    $fsdfd = '<div class="label label-danger">' . trans("admin.OnTrash") . '</div>';
                } elseif ($post->approve == 'draft') {
                    $fsdfd = '<div class="label label-info" style="background-color: #9c486c !important;">' . trans("admin.DraftPost") . '</div>';
                } elseif ($post->approve == 'no') {
                    $fsdfd = '<div class="label label-info" style="background-color: #9c6a11 !important;">' . trans("admin.AwaitingApproval") . '</div>';
                } elseif ($post->featured_at !== null) {
                    $fsdfd =  '<div class="clear"></div><div class="label label-warning" style="background-color: #9C5D54 !important;">' . trans("admin.FeaturedPost") . '</div>';
                } elseif ($post->approve == 'yes') {
                    $fsdfd = '<div class="label label-info">' . trans("admin.Active") . '</div>';
                }

                if ($post->show_in_homepage == 'yes') {
                    $fsdfd .= '<div class="clear"></div><div class="label label-success">' . trans("admin.Pickedforhomepage") . '</div>';
                }

                if ($post->published_at->getTimestamp() > Carbon::now()->getTimestamp()) {
                    $fsdfd .= '<div class="label bg-gray">' . trans('v3.scheduled_date', ['date' => $post->published_at->format('j M Y, h:i A')])  . '</div>';
                }

                return $fsdfd;
            })
            ->editColumn('language', function ($post) {
                if ($post->language) {
                    return get_language_list($post->language);
                }
                return "-";
            })
            ->editColumn('published_at', function ($post) {
                if ($post->published_at) {
                    return $post->published_at->format('Y-m-d H:i:s');
                }
                return "-";
            })
            ->editColumn('featured_at', function ($post) {
                if ($post->featured_at) {
                    return $post->featured_at->format('Y-m-d H:i:s');
                }
                return "-";
            })
            ->addColumn('action', function ($post) {
                $edion = '<div class="input-group-btn">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">' . trans("admin.actions") . ' <span class="fa fa-caret-down"></span></button>
                                  <ul class="dropdown-menu pull-left" style="left:-100px;  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);">';

                if ($post->deleted_at == null) {
                    if ($post->approve == 'no') {
                        $edion = $edion . '<li><a href="javascript:void(0);" class="do_post_action" data-url="' . action("Admin\PostsController@approvePost", ['ids' => $post->id, 'action' => 'yes']) . '"><i class="fa fa-check"></i>  ' . trans("admin.Approve") . '</a></li>';
                    } elseif ($post->approve == 'yes') {
                        $edion = $edion . '<li><a href="javascript:void(0);" class="do_post_action" data-url="' . action("Admin\PostsController@approvePost", ['ids' => $post->id, 'action' => 'no']) . '"><i class="fa fa-remove"></i> ' . trans("admin.UndoApprove") . '</a></li>';
                    }
                    if ($post->approve == 'yes') {
                        if ($post->featured_at == null) {
                            $edion = $edion .  '<li><a href="javascript:void(0);" class="do_post_action" data-url="' . action("Admin\PostsController@setFeatured", ['ids' => $post->id, 'action' => 'yes']) . '"><i class="fa fa-star"></i> ' . trans("admin.PickforFeatured") . '</a></li>';
                        } else {
                            $edion = $edion .  '<li><a href="javascript:void(0);" class="do_post_action" data-url="' . action("Admin\PostsController@setFeatured", ['ids' => $post->id, 'action' => 'no']) . '"><i class="fa fa-remove"></i> ' . trans("admin.UndoFeatured") . '</a></li>';
                        }

                        if ($post->show_in_homepage == null) {
                            $edion = $edion .  '<li><a href="javascript:void(0);" class="do_post_action" data-url="' . action("Admin\PostsController@setForHomepage", ['ids' => $post->id, 'action' => 'yes']) . '"><i class="fa fa-dashboard"></i> ' . trans("admin.PickforHomepage") . '</a></li>';
                        } elseif ($post->show_in_homepage == 'yes') {
                            $edion = $edion .  '<li><a href="javascript:void(0);" class="do_post_action" data-url="' . action("Admin\PostsController@setForHomepage", ['ids' => $post->id, 'action' => 'no']) . '"><i class="fa fa-remove"></i>   ' . trans("admin.UndofromHomepage") . '</a></li>';
                        }
                    }

                    $edion = $edion .  '<li class="divider"></li>';

                    $edion = $edion .  '<li><a target="_blank" href="/edit/' . $post->id . '"><i class="fa fa-edit"></i> ' . trans("admin.EditPost") . '</a></li>';

                    $edion = $edion .  '<li class="divider"></li>';
                }

                if ($post->deleted_at == null) {
                    $edion = $edion . '<li><a href="javascript:void(0);" class="do_post_action" data-url="' . action("Admin\PostsController@deletePost", ['ids' => $post->id, 'action' => 'remove']) . '"><i class="fa fa-trash"></i> ' . trans("admin.SendtoTrash") . '</a></li>';
                } else {
                    $edion = $edion . '<li><a href="javascript:void(0);" class="do_post_action" data-url="' . action("Admin\PostsController@deletePost", ['ids' => $post->id, 'action' => 'restore']) . '"><i class="fa fa-trash"></i> ' . trans("admin.RetrievefromTrash") . '</a></li>';
                }

                $edion = $edion .  '<li><a href="javascript:void(0);" class="do_post_action" data-url="' . action("Admin\PostsController@forceDeletePost", ['ids' => $post->id, 'action' => 'force']) . '"><i class="fa fa-remove"></i> ' . trans("admin.Deletepermanently") . '</a></li>';

                $edion = $edion .  '</ul>
                            </div>';

                return $edion;
            })
            ->escapeColumns(['*'])
            ->make(true);
    }
}
