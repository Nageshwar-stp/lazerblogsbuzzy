<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends MainAdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('DemoAdmin', ['only' => ['delete', 'addnew']]);
    }

    public function index(Request $request)
    {
        $categories = Category::byMain()->byLanguage($request->get('lang'))->orderBy('order')->get();

        $category = null;

        if ($request->query('edit')) {
            $category = Category::findOrFail($request->query('edit'));
        }

        return view('_admin.pages.categories', compact('categories', 'category'));
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        Session::flash('success.message', trans("admin.Deleted"));

        return redirect()->back();
    }

    public function addnew(Request $request)
    {
        $inputs = $request->all();
        $v = Validator::make(
            $inputs,
            [
                'name' => 'required',
                'name_slug' => 'required',
                'description' => 'max:500'
            ]
        );

        if ($v->fails()) {
            return redirect()->back()->withInput($inputs)->withErrors($v);
        }

        $id = $inputs['id'];

        if ($id !== null) {
            $category = Category::findOrFail($id);
        } else {
            $category = new Category();
        }

        $category->order = isset($inputs['order']) ? $inputs['order'] : null;
        $category->name = $inputs['name'];
        $category->name_slug = str_slug($inputs['name_slug'], '-');
        $category->posturl_slug = isset($inputs['posturl_slug']) ? str_slug($inputs['posturl_slug'], '-') : null;
        $category->description = isset($inputs['description']) ? $inputs['description'] : null;
        $category->type = isset($inputs['type']) ? $inputs['type'] : null;
        $category->parent_id = isset($inputs['parent_id']) ? $inputs['parent_id'] : null;
        $category->disabled = isset($inputs['disabled']) ? $inputs['disabled'] : "0";
        $category->language = isset($inputs['language']) ? $inputs['language'] : get_buzzy_config('sitedefaultlanguage', 'en');
        $category->save();

        if (!empty($inputs['id'])) {
            Session::flash('success.message', trans("admin.ChangesSaved"));
            if (get_buzzy_config('p_multilanguage') == 'on') {
                return redirect('/admin/categories/?lang=' . $category->language);
            }
            return redirect('/admin/categories');
        } else {
            Session::flash('success.message', trans("admin.ChangesSaved"));
            return redirect()->back();
        }
    }
}
