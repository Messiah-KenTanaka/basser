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

// Route::get('/', function () {
//     return view('welcome');
// });

// ログイン機能
Auth::routes();

// scss読み込みルーティング
Route::get('scss', function () {
    return view('app');
});

// Googleログインボタン押下後のルーティング
Route::prefix('login')->name('login.')->group(function () {
    Route::get('/{provider}', 'Auth\LoginController@redirectToProvider')->name('{provider}');
    Route::get('/{provider}/callback','Auth\LoginController@handleProviderCallback')->name('{provider}.callback');
});
Route::prefix('register')->name('register.')->group(function () {
    Route::get('/{provider}', 'Auth\RegisterController@showProviderUserRegistrationForm')->name('{provider}');
    
    Route::post('/{provider}', 'Auth\RegisterController@registerProviderUser')->name('{provider}');
    
});

// 記事一覧画面(TOPページ)
Route::get('/', 'ArticleController@index')->name('articles.index');

/**
* よく使われる機能のルーティング
* store, index, create, destroy, update, show, edit
* ルーティングの確認 $ docker-compose exec workspace php artisan route:list (laradockディレクトリ内)
*/
Route::resource('/articles', 'ArticleController')->except(['index, show'])->middleware('auth');
Route::resource('/articles', 'ArticleController')->only(['show']);

// いいね機能のルーティング
Route::prefix('articles')->name('articles.')->group(function () {
    Route::put('/{article}/like', 'ArticleController@like')->name('like')->middleware('auth');
    Route::delete('/{article}/like', 'ArticleController@unlike')->name('unlike')->middleware('auth');
});

// タグ別記事一覧画面のルーティング
Route::get('/tags/{name}', 'TagController@show')->name('tags.show');

// ユーザーページ表示処理のルーティング
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/{name}', 'UserController@show')->name('show');
    // いいねタブが押された時のルーティング
    Route::get('/{name}/likes', 'UserController@likes')->name('likes');
    // フォロー中のユーザー一覧のルーティング
    Route::get('/{name}/followings', 'UserController@followings')->name('followings');
    // フォロワー一覧のツーティング
    Route::get('/{name}/followers', 'UserController@followers')->name('followers');
    // フォロー機能のルーティング
    Route::middleware('auth')->group(function () {
        Route::put('/{name}/follow', 'UserController@follow')->name('follow');
        Route::delete('/{name}/follow', 'UserController@unfollow')->name('unfollow');
    });
});