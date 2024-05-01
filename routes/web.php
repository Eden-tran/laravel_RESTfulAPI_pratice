<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

// Route::get('/{id}', function ($id) {
//     return $id;
//     // return view('welcome');
// });
Route::get('check', function () {
    $user = User::find(3);
    dd($user->tokens()->accessToken);
});
Route::get('/setup', function () {
    $credentials = [
        'email' => 'admin123@gmail.com',
        'password' => '123456',
    ];
    $per = [
        'customer', 'invoice'
    ];
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('admin', $per)->accessToken;
        $user->token = $token;
        $user->save();
        return [
            $token
        ];
    } else {
        $user = new \App\Models\User();
        $user->name = 'admin';
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->save();
    }
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
