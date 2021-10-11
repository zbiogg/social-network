<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/user','Api\AuthController@userInfo')->middleware('jwtAuth');

Route::apiResource('posts','Api\postcontroller')->middleware('jwtAuth');

Route::post('posts','Api\postcontroller@addPost')->middleware('jwtAuth');

Route::post('editPost','Api\postcontroller@editPost')->middleware('jwtAuth');

Route::post('deletePost','Api\postcontroller@deletePost')->middleware('jwtAuth');

Route::apiResource('users','Api\usercontroller')->middleware('jwtAuth');

Route::apiResource('likes','Api\likecontroller')->middleware('jwtAuth');

Route::apiResource('comments','Api\commentcontroller')->middleware('jwtAuth');

Route::apiResource('notifications','Api\notificationcontroller')->middleware('jwtAuth');

Route::apiResource('relationship','Api\relationshipcontroller')->middleware('jwtAuth');

Route::apiResource('replycomments','Api\replycommentcontroller')->middleware('jwtAuth');

Route::get('/postcmts','Api\commentcontroller@postcmts')->middleware('jwtAuth');

Route::post('/viewMoreCmts','Api\commentcontroller@viewMoreCmts')->middleware('jwtAuth');

Route::post('login','Api\AuthController@login');

Route::get('checklogin','Api\AuthController@checklogin')->middleware('jwtAuth');

Route::post('register','Api\AuthController@register');

Route::get('logout','Api\AuthController@logout');

Route::get('myposts','Api\postcontroller@myPosts')->middleware('jwtAuth');

Route::get('userprofile/{id}','Api\otherscontroller@getUserData')->middleware('jwtAuth');

Route::get('useravts/{id}','Api\otherscontroller@getUserAvts')->middleware('jwtAuth');

Route::get('userphotos/{id}','Api\otherscontroller@getUserPhotos')->middleware('jwtAuth');

Route::post('comments','Api\commentcontroller@addCmt')->middleware('jwtAuth');

Route::post('repcmt','Api\commentcontroller@addRepCmt')->middleware('jwtAuth');

Route::get('replycomments','Api\commentcontroller@replycomments')->middleware('jwtAuth');

Route::get('requestFriends','Api\friendcontroller@requestFriends')->middleware('jwtAuth');

Route::get('suggestFriends','Api\friendcontroller@suggestFriends')->middleware('jwtAuth');

Route::post('addFriend','Api\friendcontroller@addFriend')->middleware('jwtAuth');

Route::post('acceptFriend','Api\friendcontroller@acceptFriend')->middleware('jwtAuth');

Route::post('cancelAddFriend','Api\friendcontroller@cancelAddFriend')->middleware('jwtAuth');

Route::post('deleteRequest','Api\friendcontroller@deleteRequest')->middleware('jwtAuth');

Route::get('search','Api\searchcontroller@search')->middleware('jwtAuth');

Route::get('notification','Api\notificationcontroller@getNotification')->middleware('jwtAuth');

Route::get('showNoti','Api\notificationcontroller@showNoti')->middleware('jwtAuth');

Route::post('readNoti','Api\notificationcontroller@readNoti')->middleware('jwtAuth');

Route::post('upAvt','Api\AuthController@upAvt')->middleware('jwtAuth');

Route::get('getNotiUnReader','Api\notificationcontroller@getNotiUnReader')->middleware('jwtAuth');

Route::get("messages",'Api\messagecontroller@getAllMessage')->middleware('jwtAuth');

Route::get('messages/{id}','Api\messagecontroller@getMessage')->middleware('jwtAuth');

Route::post('sendMessage','Api\messagecontroller@sendMessage')->middleware('jwtAuth');

Route::get('detailmess/{id}','Api\messagecontroller@getDetailMessage')->middleware('jwtAuth');

Route::get('friends','Api\friendcontroller@getListFriends')->middleware('jwtAuth');

//
Route::post('loginfb','Api\AuthController@loginFB');
//