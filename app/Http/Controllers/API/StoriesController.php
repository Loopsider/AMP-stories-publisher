<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Models\Story;
use App\Models\Style;
use Validator;

class StoriesController extends BaseController
{

    /**
     * Get Style
     */
    public function getStyles()
    {
        try {
            $result = Style::all();

            return $this->sendResponse($result);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }


    /**
     * Stories List
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        try {
            $stories = Story::with(['user:id,name'])->get();
            return $this->sendResponse($stories);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

    /**
     * Get Story by Id
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStoryById(Request $request)
    {
        try {
            $story_id = $request->route('id');

            $result = Story::find($story_id)
                ->load(['user:id,name']);

            // Redis SET story:id:{story_id}
            Redis::set('story:id:' . $story_id, json_encode($result));

            return $this->sendResponse($result);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

    /**
     * Create Story
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'data' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }
        try {
            $input = $request->all();
            $insert = [
                'title' => $input["title"],
                'slug' => str_slug($input["title"], "-"),
                'status' => $input["storyStatus"],
                'story_language' => $input["story_language"],
                'user_id' => Auth::user()->id,
                'start_publication_date' => new \DateTime(),
                'end_publication_date' => new \DateTime(),
                'data' => json_encode($input["data"])
            ];
            $story = Story::create($insert);
            // Redis SET story:id:{story_id}
            Redis::set('story:id:' . $story['id'], json_encode($story));
            return $this->sendResponse($story);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

    /**
     * Update Story
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'required|max:255',
            'data' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $input = $request->all();
            $story = Story::where('id', $input["id"])
                ->firstOrFail();

            $story->update([
                "title" => $input["title"],
                "slug" => str_slug($input["title"], "-"),
                'story_language' => $input["story_language"],
                'end_publication_date' => new \DateTime(),
                "data" => json_encode($input["data"]),
            ]);

            return $this->sendResponse($story);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

    /**
     * Delete Story
     *
     * @param Request $request
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
            $story = Story::where('id', $input["id"])
                ->firstOrFail();

            $result = $story->delete();
            return $this->sendResponse($result);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }


    public function publish(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }
        try {
            $input = $request->all();
            $story = Story::where('id', $input["id"])
                ->firstOrFail();

        $story->update([
            "status" => "published",
        ]);

        return $this->sendResponse($story);



        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

    /**
     * Get Story From Redis
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStoryFromRedis(Request $request)
    {
        $story_id = $request->route('id');
        try {
            $story = Redis::get('story:id:' . $story_id);

            return $this->sendResponse(json_decode($story));
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

    /**
     * Get Story From Redis
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setStoryToRedis(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'data' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }
        try {
            $input = $request->all();
            Redis::set('story:id:' . $input["id"], json_encode($input));
            return $this->sendResponse($input);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

    /**
     * Delete Story From Redis
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteStoryFromRedis(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }
        try {
            $input = $request->all();
            Redis::del('story:id:' . $input["id"]);

            return $this->sendResponse(['message' => 'success']);
        } catch (\Exception $exception) {
            return $this->sendInternalError($exception->getMessage());
        }
    }

}
