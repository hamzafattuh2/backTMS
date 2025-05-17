<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class WalletController extends Controller
{
    // إيداع أموال لمستخدم معين
    public function deposit(Request $request)
    {
        $user = Auth::user()->id;
        $wallet = Wallet::where('user_id',$user)->first();
        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'type' => 'deposit',
        ]);

        return response()->json([
            'message' => 'تم طلب الإيداع بنجاح',
        ]);
    }

}
