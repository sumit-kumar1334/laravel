<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['redirect'])->group(function () {



    Route::get('/login',[AuthController::class,'loginview'])->name('login');
    Route::post('/login',[AuthController::class,'login'])->name('login.store');
    Route::get('/register',[AuthController::class,'registerview'])->name('register');
    Route::post('/register',[AuthController::class,'register'])->name('register.store');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('index');
    Route::get('/logout',[AuthController::class,'logout'])->name('logout');
    Route::get('/users',[UserController::class,'index'])->name('user.index');
    Route::middleware(['role:SuperAdmin'])->group(function () {
        Route::get('/user/create',[UserController::class,'create'])->name('user.create');
        Route::post('/user/store',[UserController::class,'store'])->name('user.store');
        Route::get('/user/edit/{id}',[UserController::class,'edit'])->name('user.edit');
        Route::post('/user/update/{id}',[UserController::class,'update'])->name('user.update');
        Route::get('/user/delete/{id}',[UserController::class,'delete'])->name('user.delete');

        Route::get('/roles',[RoleController::class,'index'])->name('role.index');
        Route::get('/roles/create',[RoleController::class,'create'])->name('role.create');
        Route::post('/roles/store',[RoleController::class,'store'])->name('role.store');
        Route::get('/roles/edit/{id}',[RoleController::class,'edit'])->name('role.edit');
        Route::post('/roles/update/{id}',[RoleController::class,'update'])->name('role.update');
        Route::get('/roles/delete/{id}',[RoleController::class,'destroy'])->name('role.delete');

        Route::get('/upload', [UserController::class, 'uploadForm'])->name('upload.form');
        Route::post('/import', [UserController::class, 'import'])->name('import.excel');
        Route::get('/download-duplicates/{file}', [UserController::class, 'downloadDuplicates'])->name('download.duplicates');

    });
});
