<?php

use Illuminate\Support\Facades\Route;


Route::get("/",function(){
    return [
        "success" => true,
        "version" => "1.0.0",
        "description" => "Useful API"
    ];
});
