<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItineararyController;
use App\Http\Controllers\MenuController;
use UniSharp\LaravelFilemanager\Lfm;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\MediaController;

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

Route::get('/',[MenuController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::get('/add/itinearary', [ItineararyController::class, 'create'])->name('itinearary.create');
    Route::post('/add/itinearary', [ItineararyController::class, 'store'])->name('itinearary.store');
    Route::get('/list/itinearary', [ItineararyController::class, 'list'])->name('itinearary.list');
    Route::get('/edit/itinearary/{id}', [ItineararyController::class, 'edit'])->name('itinearary.edit');
    Route::post('/edit/itinearary/{id}', [ItineararyController::class, 'update'])->name('itinearary.update');
    Route::get('/delete/itinearary/{id}', [ItineararyController::class, 'delete'])->name('itinearary.delete');



     Route::get('/add/menu', [MenuController::class, 'create'])->name('menu.create');
     Route::post('/add/menu', [MenuController::class, 'store'])->name('menu.store');
     Route::get('/list/menu', [MenuController::class, 'list'])->name('menu.list');
     Route::get('/edit/menu/{id}', [MenuController::class, 'edit'])->name('menu.edit');
     Route::get('/delete/menu/{id}', [MenuController::class, 'delete'])->name('menu.delete');
     Route::post('/menu/update-order', [MenuController::class, 'updateOrder'])->name('menu.updateOrder');
     Route::post('/edit/menu/{id}', [MenuController::class, 'update'])->name('menu.update');




     //for file manager use blog

     Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web']], function () {
    Lfm::routes();
});

     Route::get('/add/blog', [BlogController::class, 'create'])->name('blog.create');
     Route::post('/add/blog', [BlogController::class, 'store'])->name('blog.store');
     Route::get('/list/blog', [BlogController::class, 'list'])->name('blog.list');
     Route::get('/edit/{id}', [BlogController::class, 'edit'])->name('blog.edit');
     Route::post('/edit/{id}', [BlogController::class, 'update'])->name('blog.update');
     Route::get('/delete/{id}', [BlogController::class, 'delete'])->name('blog.delete');




     //for media

     Route::get('/media/modal', [MediaController::class, 'modal'])->name('media.modal');
     Route::post('/media/upload', [MediaController::class, 'upload'])->name('media.upload');
     Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
     Route::put('/media/{id}', [MediaController::class, 'update'])->name('media.update');

});

require __DIR__.'/auth.php';
