<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModuleController;


Route::get("/",function(){
    return [
        "success" => true,
        "version" => "1.0.0",
        "description" => "Useful API"
    ];
});

Route::post("/register",[AuthController::class,"register"]);
Route::post("/login",[AuthController::class, "login"]);

Route::get("/modules",[ModuleController::class,"index"]);
