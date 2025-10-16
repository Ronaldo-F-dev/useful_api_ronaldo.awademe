<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\UserModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ModuleController extends Controller
{

    public function index(Request $request)
    {
        $modules = Module::all();
        return response()->json($modules);
    }

    public function activate(Request $request,int $id)
    {
        if(Auth::check()){
            try {
                $user = Auth::user();
                $module = Module::find($id);
                if(!$module){
                    return response([],404);
                }
                $user_module = UserModule::where("user_id",$user->id)->where("module_id",$id)->get();
                if(count($user_module) > 0){
                    $user_module[0]->active = true;
                    $user_module[0]->save();
                }else{

                    $user_module = UserModule::create([
                        "user_id" => $user->id,
                        "module_id" => $id,
                        "active" => true
                    ]);
                }
                return response()->json([
                    "message" => "Module activated"
                ]);
            } catch (ValidationException $e) {
                return response([],404);
            }
        }else{
            return response()->json([],401);
        }

    }
    public function deactivate(Request $request,int $id)
    {
        if(Auth::check()){
        try {
            $user = Auth::user();
            $module = Module::find($id);
            if(!$module){
                return response([],404);
            }
            $user_module = UserModule::where("user_id",$user->id)->where("module_id",$id)->get();
            if(count($user_module) > 0){
                $user_module[0]->active = false;
                $user_module[0]->update();
            }else{

                $user_module = UserModule::create([
                    "user_id" => $user->id,
                    "module_id" => $id,
                    "active" => false
                ]);
            }
            return response()->json([
                "message" => "Module deactivated"
            ]);
        } catch (ValidationException $e) {
            return response([],404);
        }
    }else{
        return response()->json([],401);
    }

    }
}
