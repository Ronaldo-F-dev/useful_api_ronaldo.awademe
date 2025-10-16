<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use Illuminate\Validation\ValidationException;

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

    public function transfert(Request $request)
    {
        try {
            $user = Auth::user();
            $validated = $request->validate([
                "receiver_id" => "required|int|exists:users,id",
                "amount" => "required|numeric|min:1|max:10000"
            ]);
        $wallet = Wallet::where("user_id",$user->id)->get();
        $wallet = $wallet[0];

        $wallet1 = Wallet::where("user_id",$validated["receiver_id"])->get();
        $wallet1 = $wallet1[0];

        if($wallet->balance >= $validated["amount"]){
                $transaction = Transaction::create([
                    "sender_id" => $user->id,
                    "receiver_id" =>$validated["receiver_id"],
                    "amount" =>$validated["amount"],
                    "status" => "success"
                ]);
                if($user->id != $validated["receiver_id"]){
                    $wallet->balance -= $validated["amount"];
                    $wallet1->balance += $validated["amount"];
                    $wallet->save();
                    $wallet1->save();
                }

                return response()->json([
                    "transaction_id" => $transaction->id,
                    "sender_id" => $user->id,
                    "receiver_id" =>$validated["receiver_id"],
                    "amount" =>$validated["amount"],
                    "status" => "success",
                    "created_at" => $transaction->created_at
                ],201);
            }else{
                return response()->json([
                    "error" => "Insuffisant amount"
                ],400);
            }



        } catch (ValidationException $e) {
            return response()->json([
                "error" => $e->getMessage()
            ],401);
        }

    }

    public function topup(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            "amount" => "required|numeric|min:1|max:10000"
        ]);
        $wallet = Wallet::where("user_id",$user->id)->get();
        $wallet = $wallet[0];
        $wallet->balance += $validated['amount'];
        $wallet->save();
        return response()->json([
            "user_id" => $user->id,
            "balance" => $wallet->balance,
            "topup_amount" => $validated['amount'],
            "created_at" => $wallet->balance->created_at
        ],201);
    }

    public function transactions(Request $request)
    {
        $user = Auth::user();
    }
}
