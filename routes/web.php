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

use App\Post;

Route::get('/', function () {
    $posts = Post::orderBy('created_at', 'dec')->get();
    return view('welcome',['posts'=>$posts]);
});
Route::get('/user-{user_id}', function (int $userId) {
    $posts = DB::table('posts')
        ->where('user_id',$userId)
        ->orderBy('created_at', 'dec')
        ->get();
    return view('userPosts',['posts'=>$posts]);
})->name('user_posts');

Route::group(['middleware'=>'guest'],function(){
    Route::get('/register','Auth\RegisterController@showRegistrationForm')
    ->name('register');
    Route::post('/register','Auth\RegisterController@register');
    Route::get('/login','Auth\LoginController@showLoginForm')
        ->name('login');
    Route::post('/login','Auth\LoginController@login');
});

Route::group(['middleware'=>'auth'], function (){
    Route::get('/home', 'AccountController@index')->name('account');
    Route::get('/logout', function (){
        \Auth::logout();
        return redirect(route('login'));
    })->name('logout');

    Route::get('/create',function (){
        return view('account\create');
    })->name('create');
    Route::post('/create','PostController@create');
    Route::post('/edit-post-{post}','PostController@editRequest')
        ->where('id','\d+');
    Route::get('/edit-post-{post}','PostController@edit')
        ->where('id','\d+')
        ->name('edit');
    Route::get('delete/{id}','PostController@delete');

    Route::post('/post-{post}','CommentController@create')
        ->where('postId','\d+');
    Route::post('/edit-comment-{post}','CommentController@editRequest')
        ->where('id','\d+');
    Route::get('/edit-comment-{post}','CommentController@edit')
        ->where('id','\d+')
        ->name('editComment');
    Route::get('delete-comment-{id}','CommentController@delete');
});

Route::get('/post-{post}',function ($id){
    $post = DB::table('posts')->find($id);
    return view('post',compact('post'));

})->name('showPost');
Route::get('/post-{post}','PostController@showPost')
    ->where('id','\d+')
    ->name('showPost');