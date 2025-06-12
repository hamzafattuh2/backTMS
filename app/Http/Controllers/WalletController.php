<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
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

    public function withdraw(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:0.01'
        ]);

        return DB::transaction(function () use ($request) {
            $wallet = Wallet::lockForUpdate()->find($request->id);

            if ($wallet->balance < $request->amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance'
                ], 400);
            }

            $wallet->balance -= $request->amount;
            $wallet->save();

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal completed successfully',
                'new_balance' => $wallet->balance
            ]);
        });
    }
}
