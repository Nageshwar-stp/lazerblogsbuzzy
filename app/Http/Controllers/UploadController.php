<?php

namespace App\Http\Controllers;

use App\Managers\UploadManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    public function newUpload(Request $request)
    {
        $inputs = $request->all();

        $type = $request->query('type');
        $path_key = $request->query('path_key', 'path');

        $validator = $this->validator($inputs);

        if ($validator->fails()) {
            return array('status' => 'error', 'error' => $validator->errors()->first());
        }

        if ($request->hasFile('file')) {
            try {
                $image = new UploadManager();
                $image->file($request, 'file');
                $image->name(Auth::user()->id . '-' . md5(time()));
                $image->path('upload/tmp');
                $image->make();
                $image->mime('jpg');

                if ($type == 'entry') {
                    $image->acceptGif();
                    $image->save([
                        'resize_width' => config('buzzytheme_' . get_buzzy_config('CurrentTheme') . '.entry-image_big_width', 780),
                    ]);
                } elseif ($type == 'preview') {
                    $image->save([
                        'fit_width' => config('buzzytheme_' . get_buzzy_config('CurrentTheme') . '.preview-image_big_width', 780),
                        'fit_height' => config('buzzytheme_' . get_buzzy_config('CurrentTheme') . '.preview-image_big_height', 440),
                    ]);
                } elseif ($type == 'answer') {
                    $image->save([
                        'fit_width' => 250,
                        'fit_height' => 250,
                    ]);
                }

                return response()->json(array('status' => 'success', $path_key => $image->getFullUrl()), 200);
            } catch (\Exception $e) {
                return response()->json(array('status' => 'error', 'error' => $e->getMessage()),  200);
            }
        } else {
            return response()->json(array('status' => 'error', 'error' => 'Pick a image'),  200);
        }
    }

    /**
     * Validator of question posts
     *
     * @param $inputs
     * @return Illuminate\Validation\Validator
     */
    protected function validator(array $inputs)
    {

        $rules = [
            'type' => 'required',
            'file' => 'required|mimes:jpg,jpeg,gif,png,webp',
        ];

        return Validator::make($inputs, $rules);
    }
}
