<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Module;
use App\Models\UserModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$module_id): Response
    {
        if(Auth::check()){
            $user = Auth::user();
            $module = Module::find($module_id);
            if(!$module){
                return response([],404);
            }
            $user_module = UserModule::where("user_id",$user->id)->where("module_id",$module_id)->get();
            if(count($user_module) === 0 || (!$user_module[0]->active)){
                return response()->json([
                    "error" => "Module inactive. Please activate this module to use it."
                ],403);
            }
            return $next($request);
        }else if(str_starts_with($request->server()['REQUEST_URI'], "/api/s/")){
            return $next($request);
        }else{
            //dd($request->server()['REQUEST_URI']);
            return response()->json([
                "error" => "Not authenticated"
            ],401);
        }
    }
}
