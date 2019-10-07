<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/getvideo/{slug}', 'FrontController@getVideo')->name('getVideo');
Route::get('api/videowall', 'FrontController@getVideoWall');
Route::get('/backoffice/{vue_capture?}', ['as' => 'backoffice', 'uses' => 'BackController@index'])->where('vue_capture', '[\/\w\.-]*');
Route::get('/auth/{vue_capture?}', ['as' => 'auth', 'uses' => 'BackController@index'])->where('vue_capture', '[\/\w\.-]*');


Route::get('/story/{slug}', 'FrontController@getStoryBySlug');
Route::get('/embed/story/{id}', 'FrontController@getStoryById');
Route::get('/embed/story/{story_id}/page/{page_id}', 'FrontController@getStoryPage')->where(['story_id' => '[0-9]+', 'page_id' => '[0-9]+']);;



Route::redirect('/', '/fr');
Route::get('/{locale}', 'FrontController@index')->name('home');
Route::get('/{locale}/video/{slug}', 'FrontController@getVideoPage')->name('videoplayer');
Route::get('/{locale}/videos', 'FrontController@getVideosPage')->name('videos');


Route::get('/{locale}/videos', 'FrontController@getVideosPage')->name('videos');
Route::get('/{locale}/videos/{categoryslug}', 'FrontController@getVideosPerCategory')->name('category-videos')->where('category', '[A-Za-z]+');
Route::get('/{locale}/videos/{categoryslug}/page/{page}', 'FrontController@getVideosPerCategoryAndPage')->name('category-videospages')->where(['categoryslug' => '[A-Za-z]+', 'page' => '[0-9]+']);
Route::get('/{locale}/videos/page/{page}', 'FrontController@getVideosPages')->where('page', '[0-9]+')->name('videosperpage');
Route::get('/{category}/{locale}', 'FrontController@getCategory')->where('category', '[A-Za-z]+')->name('category');

