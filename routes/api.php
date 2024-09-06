<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/signup', [UserController::class, 'createAdmin']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/room', [RoomController::class, 'store']);
Route::get('/rooms', [RoomController::class, 'index']);
Route::delete('/room/{room}', [RoomController::class, 'destroy']);

Route::post('/register', [UserController::class, 'store']);
Route::patch('/userdata/{user}', [UserController::class, 'update']);
Route::delete('/userdata/{user}', [UserController::class, 'destroy']);
Route::get('/room/{new_room}/userdata/{user}', [UserController::class, 'changeRoom']);

Route::get('/usersinroom', [RoomController::class, 'getUsersInRooms']);

Route::post('/hotel', [HotelController::class, 'store']);
Route::get('/hotels', [HotelController::class, 'index']);
Route::delete('/hotel/{hotel}', [HotelController::class, 'destroy']);
Route::get('/hotel/{hotel}/room/{room}', [HotelController::class, 'addRoom']);
Route::get('/roomsinhotels', [HotelController::class, 'getUsersInRoomsInHotels']);