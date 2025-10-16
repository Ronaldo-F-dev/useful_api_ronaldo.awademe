<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UrlController extends Controller
{
    public function add(Request $request)
    {
        try{
            $user = Auth::user();
            $validated = $request->validate([
                "original_url" => "required|url",
                "custom_code" => "required|string|max:10|unique:short_links,code"
            ]);
            $link = ShortLink::create([
                "original_url" => $validated["original_url"],
                "code" => $validated['custom_code'],
                "user_id" =>$user->id
            ]);
            return response()->json([
                "id" => $link->id,
                "user_id" => $user->id,
                "original_url" => $link->original_url,
                "code" => $link->code,
                "clicks" => 0,
                "created_at" => $link->created_at
            ],201);
        }catch(ValidationException $e){
            return response()->json( [
                "error" => $e->getMessage(),
            ],401);
        }

    }

    public function redirectToLink(Request $request,$code)
    {
        try {
            $link = ShortLink::where("code",$code)->get();
            if(count($link) === 0){
                return response()->json([],401);
            }
            $link = $link[0];
            $link->clicks++;
            $link->update();
            return response()->redirectTo($link->original_url,302);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()],401);
        }
    }
}
