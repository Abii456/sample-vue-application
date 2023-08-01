<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PostController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//USER ENDPOINT
Route::post('users/register', [UserController::class, 'register'])->name('register');
Route::post('users/login', [UserController::class, 'login'])->name('login');
Route::put('users/update-user-details', [UserController::class, 'updateUserDetails']);

//POST ENDPOINT
Route::post('posts/create', [PostController::class, 'create'])->name('create');
Route::get('posts/index', [PostController::class, 'index'])->name('index');
Route::put('posts/update/{id}', [PostController::class, 'update'])->name('update');
Route::delete('posts/delete/{id}', [PostController::class, 'delete'])->name('delete');
