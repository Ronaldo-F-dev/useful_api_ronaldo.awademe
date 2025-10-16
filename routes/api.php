<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\UrlController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::post("/modules/{id}/activate",[ModuleController::class, "activate"]);
    Route::post("/modules/{id}/deactivate",[ModuleController::class, "deactivate"]);
});


Route::middleware("auth:sanctum")->group(
    function(){
        Route::post("/shorten",[UrlController::class,"add"]);

    }
);
