<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\AddressController;



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

//login route

Route::get('/', [LoginController::class, 'login'] )->name('/') ->middleware('guest');
Route::get('/logout', [LoginController::class, 'logout'] )->name('logout') ->middleware('auth');;

Route::get('/home', function () {
     
    Artisan::call('inspire');
    $citat = Artisan::output();
    return view('home', compact('citat'));
    
})->name('home');

Route::any('/login', [LoginController::class, 'authenticate'])->name('login')->middleware('guest');


//Coupon route


Route::group(['middleware' => 'auth'], function(){


    Route::controller(CouponController::class)->group(function () {
        Route::get('/coupon', 'index')->name('coupon');
        Route::get('/coupon/create', 'create')->name('coupon_create');
        Route::post('/coupon', 'store')->name('coupon_store');
        Route::get('/coupon/edit/{id}', 'edit')->name('coupon_edit');
        Route::put('/coupon/{id}', 'update')->name('coupon_update');
        Route::delete('/coupon/{id}', 'destroy')->name('coupon_delete');
        Route::get('/used', 'used')->name('used');
        Route::get('/active', 'active')->name('active');
        Route::get('/disable', 'disable')->name('disable');
        Route::get('/non_used', 'active')->name('non_used');
        Route::get('/search', 'search')->name('search');
        Route::get('/search_active', 'search')->name('search_active');
        Route::get('/search_used', 'search_used')->name('search_used');
        Route::get('/search_non_used', 'search')->name('search_non_used');
        Route::get('/disable_one/{id}', 'disable_one')->name('disable_one');
        
    });

});



//Address Route
Route::controller(AddressController::class)->group(function () {
    
    Route::get('/address', 'index')->name('address');
    Route::get('/search_address', 'search')->name('search_address');

   
});



