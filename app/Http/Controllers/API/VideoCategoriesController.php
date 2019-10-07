<?php

namespace App\Http\Controllers\API;

use App\Models\VideoCategory;

class VideoCategoriesController extends BaseController
{

    /**
     * Category List
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        try {
            $categories = VideoCategory::all();

            return $this->sendResponse($categories);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }
}
