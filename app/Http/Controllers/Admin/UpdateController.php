<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Installer\Helpers\DatabaseManager;

class UpdateController extends MainAdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('DemoAdmin');
    }

    public function handle(Request $request, DatabaseManager $manager)
    {
        $v = Validator::make($request->all(), [
            'item_id' => 'required',
            'item_code' => 'required',
            'item_version' => 'required',
        ]);

        if ($v->fails()) {
            return ['status' => 'error', 'message' => $request->all()];
        }

        $item_id = $request->input('item_id');
        $item_code = $request->input('item_code');
        $item_version = $request->input('item_version');

        try {
            // download
            $response = $this->product_api->downloadUpdates($item_id, $item_version);

            if (!$response) {
                return ['status' => 'error', 'message' => 'Error while downloading items'];
            }

            if ($item_id == config('buzzy.item_id')) {
                return ['status' => 'redirect', 'redirect' => url('/installer/update')];
            }

            // plugin downloads

            // here we can try to upgrade
            $response = $manager->updateDatabaseAndSeedTables();

            // upgrade
            if ($response['status'] == 'error') {
                return $response;
            }

            set_buzzy_config($item_code . '_INSTALLED', 'true', false);
            return ['status' => 'success', 'message' => trans('updates.success')];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
