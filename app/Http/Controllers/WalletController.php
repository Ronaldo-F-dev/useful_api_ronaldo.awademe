<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
class WalletController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        $wallet = Wallet::where("user_id",$user->id)->get();
        $wallet = $wallet[0];
        return response()->json([
            "user_id" => $user->id,
            "balance" => $wallet->balance
        ],200);

    }
}
