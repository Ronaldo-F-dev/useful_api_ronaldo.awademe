<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\ShortLink;
use Illuminate\Support\Str;
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
                "custom_code" => "nullable|string|max:10|unique:short_links,code|regex:/^[a-zA-Z0-9_-]+$/"
            ]);
            if(!isset($validated["custom_code"])){
                $validated["custom_code"] = Str::random(10);
            }
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
            //$user = Auth::user();
            $link = ShortLink::where("code",$code)->get();
            //dd($link);
            if(count($link) === 0){
                return response()->json([],401);
            }
            $link = $link[0];
            /*if($link->user_id != $user->id){
                return response()->json([],403);
            }*/
            $link->clicks++;
            $link->update();
            return response()->redirectTo($link->original_url,302);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()],401);
        }
    }

    public function links()
    {
        try {
            $user = Auth::user();
            $links = ShortLink::where("user_id",$user->id)->get();

            $linksFormated = [];
            foreach ($links as $l) {
                $linksFormated[] = [
                    "id" => $l->id,
                    "original_url" => $l->original_url,
                    "code" => $l['code'],
                    "clicks" => $l->clicks,
                    "created_at" =>$l->created_at
                ];
            }
            return response()->json($linksFormated);
        } catch (\Throwable $th) {

        }
    }

    public function deleteLink(Request $request,$id)
    {
        try {
            $link = ShortLink::find($id);
            if(!$link){
                return response([],404);
            }
            $link->delete();
            return response()->json([
                "message" => "Link deleted successfully"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ]);
        }
    }
}
