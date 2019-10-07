<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($data  = NULL, $code = 200)
    {
        $response = $data;

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendValidationError($error, $code = 401) // , $errorMessages = []
    {
        $response = [
            'message' => $error
        ];

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendUnauthorizedError($code = 403) // , $errorMessages = []
    {
        $response = [
            'message' => "User unauthorized"
        ];

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendInternalError($error, $code = 500) // , $errorMessages = []
    {
        $response = [
            'message' => $error
        ];

        return response()->json($response, $code);
    }
}