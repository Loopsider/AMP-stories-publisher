<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\Style;
use Validator;

class StoriesController extends BaseController
{

    /**
     * Save Style
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveStyle(Request $request)
    {
        try {
            foreach ($request->all() as $slug => $data) {
                $style = Style::where('slug', $slug)->first();
                if (is_null($style)) {
                    Style::create(
                        [
                            'slug' => $slug,
                            'data' => $data
                        ]
                    );
                } else {
                    $style->update([
                        'data' => $data,
                    ]);
                }
            }
            return $this->sendResponse(Style::all());
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }
}
