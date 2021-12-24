<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/',[TaskController::class,'index']);

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/addtask',[TaskController::class,'store'])->name('/');
Route::get('/tasklist',[TaskController::class,'tasklist']);
Route::get('/alltasklist',[TaskController::class,'alltasklist']);
Route::get('/editTask/{id}',[TaskController::class,'editTask']);
Route::PUT('/updatetask/{id}',[TaskController::class,'update']);
Route::delete('/delete/{id}',[TaskController::class,'destroy']);

