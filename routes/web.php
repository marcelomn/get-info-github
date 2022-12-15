<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\TestController;
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

//Route::get('/', function () {
//    return view('welcome');
//});

/**
 * URL's de testes
 */
Route::controller(TestController::class)->group(function (){
    Route::get('test/show', 'show');
    Route::get('test/followers', 'followers');
    Route::get('test/repositories', 'repositories');
    Route::get('test/commits', 'commits');
    Route::get('test/commit', 'commit');
    Route::get('test/repositories2', 'repositories2');
    Route::get('test/repositories3', 'repositories3');
    Route::get('test/repositories4', 'repositories4');
    Route::get('test/authorize', 'authorizeGit');
    Route::get('test/getuser', 'getuser');
    Route::get('test/chart', 'chart');

});

Route::get('/', [AuthController::class, 'authentication'])->name('authentication');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::group(['middleware' => ['github']], function (){
    Route::controller(AuthController::class)->group(function (){
        Route::get('authentication', 'authentication');
    });

    Route::get('github/data-commit/{repository}/{date}', [GithubController::class, 'showDataCommit'])->name('github.data-commit');
    Route::resource('github', GithubController::class);
});
