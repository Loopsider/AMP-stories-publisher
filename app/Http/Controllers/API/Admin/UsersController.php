<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\User;
use Validator;

class UsersController extends BaseController
{


    /**
     * Admin Controller Constructor
     */
    public function __construct()
    {

    }

    /**
     * User List
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {

        try {
            $result = User::with('role')->get();

            return $this->sendResponse($result);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

    /**
     * Update User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'role_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $input = $request->all();
            $user = User::where('id', $input["user_id"])->firstOrFail();
            $result = $user->update([
                "name" => $input["name"],
                "email" => $input["email"],
            ]);
            $user->roles()->sync($input['role_id']);

            return $this->sendResponse($result);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

    /**
     * Delete User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $user = User::where('id', $request->input("user_id"))->firstOrFail();
            $result = $user->delete();

            return $this->sendResponse($result);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }
}
