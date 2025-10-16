<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\WalletController;
use App\Http\Middleware\CheckModuleActive;

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

Route::get("/s/{code}",[UrlController::class, "redirectToLink"])->middleware(CheckModuleActive::class . ":1");

Route::middleware(CheckModuleActive::class . ":1")->group(
    function(){

        Route::middleware("auth:sanctum")->group(
            function(){
                Route::post("/shorten",[UrlController::class,"add"]);
                Route::get("/links",[UrlController::class,"links"]);
                Route::delete("/links/{id}",[UrlController::class, "deleteLink"]);
            }
        );
    }
);

Route::middleware("auth:sanctum")->group(
    function(){
Route::get("/wallet",[WalletController::class, "show"]);
Route::post("/wallet/transfer",[WalletController::class, "transfert"]);
Route::post("/wallet/topup",[WalletController::class,"topup"]);
Route::get('/wallet/transactions',[WalletController::class, "transactions"]);
    });
