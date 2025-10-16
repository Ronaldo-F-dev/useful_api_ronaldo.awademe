<?php

namespace App\Http\Controllers;

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
            ]);
        }catch(ValidationException $e){
            return response()->json( [
                "error" => $e->getMessage(),
            ],401);
        }

    }
}
