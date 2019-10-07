<?php
namespace App\Http\Controllers;

use Lang;
use App\Models\Story;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class FrontController extends Controller
{
    public $wallSize = 19;


    public function getVideosPerCategoryAndPage(Request $request,$locale,$category, $page)
    {
        return $this->getVideosPage($request,$locale,$page,$category);
    }

    public function getVideosPages(Request $request,$locale, $page){
        return $this->getVideosPage($request,$locale,$page);
    }

    public function getVideosPerCategory(Request $request,$locale,$categoryslug){
        return $this->getVideosPage($request,$locale,$page=0,$categoryslug);
    }

    

    public function getVideoPage(Request $request, $locale, $slug){

        // Language filter
        if($locale=='en'){
            $languageFilter="english";
        }else if($locale=='es'){
            $languageFilter="spanish";
        }else{
            $languageFilter="french";
        }

        $video = Video::where('slug', $slug)->where('video_language', '=', $languageFilter)->first();
        $suggestedVideos = Video::where('slug', '<>', $slug)->where('video_language', '=', $languageFilter)->orderBy('date', 'desc')->limit(6)->get();
        return view('player', compact('video', 'suggestedVideos'));

    }

    public function getVideosPage(Request $request,$locale,$page = 0,$categoryslug=null){



        if($locale=='en' || $locale=='fr' || $locale=='es'){
            Lang::setLocale($locale);
        }else{
            Lang::setLocale("fr");
        }

        // Language filter
        if($locale=='en'){
            $languageFilter="english";
        }else if($locale=='es'){
            $languageFilter="spanish";
        }else{
            $languageFilter="french";
        }

        $this->wallSize = 14; // no carousel on video template
        $skip = ($page > 0) ? $this->wallSize + ($page - 1) * $this->wallSize : 0;

        if (is_null($categoryslug)) {
            $videos = Video::orderBy('date', 'desc')->where('video_language', '=', $languageFilter)->skip($skip)->limit($this->wallSize)->get();
            $totalVideoCount = Video::where('video_language', '=', $languageFilter)->count();
        }else{
            // get category name and slug
            $category = VideoCategory::where('slug', $categoryslug)->first();
            if ($category) {
                // get videos within category
                $videos = Video::orderBy('date', 'desc')
                    ->where('video_category_id', '=', $category->id)
                    ->where('video_language', '=', $languageFilter)
                    ->skip($skip)
                    ->limit($this->wallSize)
                    ->get();
                $totalVideoCount = Video::orderBy('date', 'desc')
                    ->where('video_category_id', '=', $category->id)
                    ->where('video_language', '=', $languageFilter)
                    ->count();
            } else {
                abort(404);
            }
        }
        $hasMorePages = ($totalVideoCount > ($skip + $this->wallSize));
        $nbPages = ceil($totalVideoCount / $this->wallSize);
        if($nbPages==0){
            $nbPages=1;
        }
        $css = "index";
        if (isset($category)) {
            return view('videos', compact('videos', 'category', 'css', 'page', 'hasMorePages', 'nbPages'));
        } else {
            return view('videos', compact('videos', 'css', 'page', 'hasMorePages', 'nbPages'));
        }

    }

    
    public function getLegalNotesPage(Request $request,$locale){
        if($locale=='en' || $locale=='fr' || $locale=='es'){
            Lang::setLocale($locale);
        }else{
            Lang::setLocale("fr");
        }
        return view('mentions', ['css' => 'legal']);
    }

    public function getCorporatePage(Request $request,$locale){
        if($locale=='en' || $locale=='es'){
            Lang::setLocale($locale);
        }else{
            Lang::setLocale("fr");
        }
        return view('corporate', ['css' => 'corporate']);
    }

    public function index(Request $request,$locale, $page = 0)
    {

        if($locale=='en' || $locale=='fr' || $locale=='es'){
            Lang::setLocale($locale);
        }else{
            Lang::setLocale("fr");
        }

        // Language filter
        if($locale=='en'){
            $languageFilter="english";
        }else if($locale=='es'){
            $languageFilter="spanish";
        }else{
            $languageFilter="french";
        }


        $page = (int) $page;

        // Get published Stories
        $frontStories = Story::orderBy('updated_at', 'desc')
          ->where('status', '=', 'published')
          ->where('story_language', '=', $languageFilter)
          ->get();
        if (is_null($frontStories)) {
            $frontStories = [];
        }

        $totalVideoCount = Video::count();
        if ($page > 0) {
            $skip = $this->wallSize + ($page - 1) * $this->wallSize;
            $videos = Video::orderBy('date', 'desc')->where('video_language', '=', $languageFilter)->skip($skip)->take($this->wallSize)->get();
            $hasMorePages = ($totalVideoCount > ($skip + $this->wallSize));
        } else {
            $videos = Video::orderBy('date', 'desc')->where('video_language', '=', $languageFilter)->limit($this->wallSize)->get();
            $hasMorePages = ($totalVideoCount > $this->wallSize);
        }
        $css = "index";
        return view('index', compact('videos', 'css', 'page', 'hasMorePages', 'frontStories'));
    }
    public function getVideosPerPage($page)
    {
        return $this->getVideos(null, $page, "videos");
    }
    
    // public function getVideosPerCategory($category)
    // {
    //     return $this->getVideos($category, 0, "videos");
    // }



    // public function getVideosPerCategoryAndPage($category, $page)
    // {
    //     return $this->getVideos($category, $page, "videos");
    // }

    public function getCategory($category,$locale)
    {
        if($locale=='en' || $locale=='fr' || $locale=='es'){
            Lang::setLocale($locale);
        }else{
            Lang::setLocale("fr");
        }
        return $this->getVideos($category, 0, "category");
    }

    public function getVideos( $categorySlug = null, $page = 0, $template = "videos")
    {
        
        $locale = Lang::getLocale(); 
        // Language filter
        if($locale=='en'){
            $languageFilter="english";
        }else if($locale=='es'){
            $languageFilter="spanish";
        }else{
            $languageFilter="french";
        }
        
        if ($template == "videos") {
            $this->wallSize = 14; // no carousel on video template
        }
        $skip = ($page > 0) ? $this->wallSize + ($page - 1) * $this->wallSize : 0;
        if (is_null($categorySlug)) {
            $videos = Video::orderBy('date', 'desc')->where('video_language', '=', $languageFilter)->skip($skip)->limit($this->wallSize)->get();
            $totalVideoCount = Video::count();
        } else {
            // get category name and slug
            $category = VideoCategory::where('slug', $categorySlug)->first();
            if ($category) {
                // get videos within category
                $videos = Video::orderBy('date', 'desc')
                    ->where('video_category_id', '=', $category->id)
                    ->where('video_language', '=', $languageFilter)
                    ->skip($skip)
                    ->limit($this->wallSize)
                    ->get();
                $totalVideoCount = Video::orderBy('date', 'desc')
                    ->where('video_category_id', '=', $category->id)
                    ->where('video_language', '=', $languageFilter)
                    ->count();
            } else {
                abort(404);
            }
        }
        $hasMorePages = ($totalVideoCount > ($skip + $this->wallSize));
        $nbPages = ceil($totalVideoCount / $this->wallSize);
        $css = "index";
        if (isset($category)) {
            return view($template, compact('videos', 'category', 'css', 'page', 'hasMorePages', 'nbPages'));
        } else {
            return view($template, compact('videos', 'css', 'page', 'hasMorePages', 'nbPages'));
        }
    }
    public function getVideoWall(Request $request)
    {


        $locale = Lang::getLocale(); 
        // Language filter
        if($locale=='en'){
            $languageFilter="english";
        }else if($locale=='es'){
            $languageFilter="spanish";
        }else{
            $languageFilter="french";
        }


        $videos = Video::orderBy('date', 'desc')->where('video_language', '=', $languageFilter)->limit($this->wallSize)->get();
        $videos = json_encode($videos, JSON_FORCE_OBJECT);
        $result["items"] = [json_decode($videos)];
        $result["hasMorePages"] = true;
        return response($result)->header('AMP-Access-Control-Allow-Source-Origin', $request->getSchemeAndHttpHost());
    }
    public function player(Request $request, $slug)
    {

        $locale = Lang::getLocale(); 
        // Language filter
        if($locale=='en'){
            $languageFilter="english";
        }else if($locale=='es'){
            $languageFilter="spanish";
        }else{
            $languageFilter="french";
        }

        $video = Video::where('slug', $slug)->where('video_language', '=', $languageFilter)->first();
        $suggestedVideos = Video::where('slug', '<>', $slug)->where('video_language', '=', $languageFilter)->orderBy('date', 'desc')->limit(6)->get();
        return view('player', compact('video', 'suggestedVideos'));
    }

    public function getStoryBySlug(Request $request, $slug)
    {
        $dbStory = Story::where('slug', $slug)->first();
        if (is_null($dbStory)) {
            abort(404);
        }
        $story = [
            "title" => $dbStory["title"],
            "slug" => $dbStory["slug"],
            "date" => $dbStory->ISODate
        ];
        $story = array_merge($story, json_decode(json_encode($dbStory["data"]), true));
        return view('story', compact('story'));
    }

    public function getStoryById(Request $request, $id)
    {
        try {
            $dbStory = json_decode(Redis::get('story:id:' . $id), true);
            if (is_null($dbStory)) {
                abort(404);
            }
            $story = [
                "title" => $dbStory["title"],
                "slug" => $dbStory["slug"],
            ];
            $story = array_merge($story, json_decode(json_encode($dbStory["data"]), true));
            return view('story', compact('story'));
        } catch (\Exception $exception) {
            abort(404);
        }
    }
    public function getStoryPage(Request $request, $story_id, $page_id)
    {
        try {
            $dbStory = json_decode(Redis::get('story:id:' . $story_id), true);
            if (is_null($dbStory)) {
                abort(404);
            }
            $story = [
                "title" => $dbStory["title"],
                "slug" => $dbStory["slug"],
            ];
            $story = array_merge($story, json_decode(json_encode($dbStory["data"]), true));
            return view('story', compact('story', 'page_id'));
        } catch (\Exception $exception) {
            //abort(404);
            var_dump($exception);
            exit;
        }
    }
}
