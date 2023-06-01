<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'FrontController@index');
Route::get('/login', 'FrontController@login')->name('login');
Route::post('/login', 'LoginController@login');
Route::get('/logout', 'FrontController@logout');
Route::match(['get', 'post'], 'reset-password/{token}/{email}', 'FrontController@reset_password');
Route::get('/reset-password-success', 'FrontController@reset_password_success');

Route::get('/download', 'FrontController@download');
Route::get('/show-attachment/{file}/{type?}', 'FrontController@show_attachment');
Route::get('/search', 'FrontController@search');
Route::get('/privacy_policy', 'FrontController@privacy_policy');
Route::match(['GET', "POST"], '/contact-us', 'FrontController@contact_us')->name('front.contact-us');
Route::get('/terms_conditions', 'FrontController@terms_conditions');
Route::get('/faq', 'FrontController@faq')->name('front.faq');
Route::get('/about-us', 'FrontController@about_us')->name('front.about_us');
Route::get('/u/{username}/{section?}', 'FrontController@vip_member_profile');
// Route::get('front/u/{username}/{section?}', 'HomeController@vip_member_profile');


Route::get('/cart', 'FrontController@cart');
Route::get('/checkout', 'FrontController@checkout');
Route::get('/order-placed', 'FrontController@order_placed');

Route::get('/social-login/facebook', 'LoginController@redirectToFacebook');
Route::get('/social-login/google', 'LoginController@redirectToGoogle');
Route::get('/social-login/facebook-callback', 'LoginController@facebookCallback');
Route::get('/social-login/google-callback', 'LoginController@GoogleCallback');
Route::get('/epoch_return', 'FrontController@epoch_return');
Route::post('/epoch_postback', 'FrontController@epoch_postback');
Route::post('/stripe_postback', 'FrontController@stripe_postback');
Route::get('/affiliate/{username}', 'FrontController@affiliate');

Route::post('/ajaxpost', 'AjaxController@ajaxpost');
Route::get('/ajaxget', 'AjaxController@ajaxget');

Route::redirect('dashboard/', 'dashboard/home', 301);
Route::redirect('admin/', 'admin/dashboard', 301);
Route::get('admin/login', 'FrontController@admin_login');

Route::group(['middleware' => 'auth'], function () {

    Route::group(['prefix' => 'dashboard', 'middleware' => 'user'], function () {
        //Route::get('home', 'UserController@home');
        Route::match(['get', 'post'], '{slug}', 'UserController@index');
        
    });
    Route::group(['middleware' => 'user'], function () {
        Route::get('/live-video-chat/{username}', 'FrontController@userLiveVideo')->name('user_live_video');
        
    });

    Route::group(['prefix' => 'admin'], function () {
        //Route::get('dashboard', 'AdminController@dashboard');
        Route::match(['get', 'post'], '{slug}', 'AdminController@index');
    });

    Route::post('adminajax/{slug}', 'AdminajaxController@slug');
    Route::get('export/money-transaction/{user}/{type}', 'AdminController@exportMoneyTransaction')->name('export.money-transaction');
    Route::get('export/coin-transaction/{model}/{follower}/{type}', 'AdminController@exportCoinTransaction')->name('export.coin-transaction');

    Route::post('export/sales-report', 'AdminController@exportSalesReport')->name('export.sales-report');

    Route::post('dynamic-content', 'AdminController@dynamic_content')->name('dyanmic-content');
});

Route::get('cron/once_at_day_start_75810753477002', 'FrontController@cron_once_at_day_start_75810753477002');
