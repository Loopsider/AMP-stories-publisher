<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Video;
use Validator;

class VideosController extends BaseController
{
    /**
     * Videos List
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        try {
            $videos = Video::with(['category:id,name', 'user:id,name'])->get();
            return $this->sendResponse($videos);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

    /**
     * Get Video by Id
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVideoById(Request $request)
    {
        try {
            $video_id = $request->route('id');

            $result = Video::find($video_id)
                ->load(['user:id,name', 'category:id,name']);

            return $this->sendResponse($result);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

    /**
     * Create Video
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required',
            'date' => 'required|date',
            'video_id' => 'required',
            'video_url' => 'required',
            'cover_id' => 'required',
            'cover_url' => 'required',
            'video_category_id' => 'required',
            'video_language' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $user = Auth::user();
        try {
            $input = $request->all();
            $insert = [
                'title' => $input["title"],
                'description' => $input["description"],
                'user_id' => $user->id,
                'video_id' => $input["video_id"],
                'video_url' => $input["video_url"],
                'cover_id' => $input["cover_id"],
                'cover_url' => $input["cover_url"],
                'date' => new \DateTime($input["date"]),
                'video_category_id' => $input["video_category_id"],
                'video_language' => $input["video_language"],
                'slug' => str_slug($input["title"], "-"),
            ];
            $video = Video::create($insert);

            return $this->sendResponse($video);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }

    }

    /**
     * Update Video
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'required|max:255',
            'description' => 'required',
            'date' => 'required|date',
            'video_id' => 'required',
            'video_url' => 'required',
            'cover_id' => 'required',
            'cover_url' => 'required',
            'video_category_id' => 'required',
            'video_language' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $input = $request->all();
            $video = Video::where('id', $input["id"])
                ->firstOrFail();

            $video->update([
                "title" => $input["title"],
                "description" => $input["description"],
                "video_id" => $input["video_id"],
                "video_url" => $input["video_url"],
                "cover_id" => $input["cover_id"],
                "cover_url" => $input["cover_url"],
                "video_category_id" => $input["video_category_id"],
                "video_language" => $input["video_language"],
                "date" => new \DateTime($input["date"]),
                "slug" => str_slug($video->title, "-")
            ]);

            return $this->sendResponse($video);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

    /**
     * Delete Video
     *
     * @param Request $request
     * @param Video $video
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }
        try {
            $input = $request->all();
            $video = Video::where('id', $input["id"])
                ->where('user_id', Auth::user()->id)
                ->firstOrFail();

            $result = $video->delete();
            return $this->sendResponse($result);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

    /**
     *  Get Cloudinary Information
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCloudinaryInformation () {
        try {
            $string = "cloud_name=" . env('CLOUDINARY_CLOUD_NAME') . "&timestamp=" . time() . "&username=" . env('CLOUDINARY_USER_NAME') . env('CLOUDINARY_API_SECRET');
            $result = [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'timestamp' => time(),
                'user_name' => env('CLOUDINARY_USER_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'signature' => hash('sha256', $string),

            ];
            return $this->sendResponse($result);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }
}
