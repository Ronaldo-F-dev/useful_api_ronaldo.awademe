<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Module;
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
    public function handle(Request $request, $id,Closure $next): Response
    {
        if(Auth::check()){
            $module = Module::find($id);
            if(!$module){
                return response([],404);
            }
            if(!$module->activate){
                return response()->json([
                    "error" => "Module inactive. Please activate this module to use it."
                ],403);
            }
            return $next($request);
        }else{
            return response([],401);
        }
    }
}
