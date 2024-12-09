<?php

namespace App\Repositories;

use App\Models\Wallet;
use App\Repositories\Interfaces\WalletRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Exception;
use Throwable;

class WalletRepository implements WalletRepositoryInterface
{
    /**
     * Create a wallet for a user.
     *
     * @param  int  $userId
     * @return Wallet
     */
    public function createWallet(int $userId): Wallet
    {
        return Wallet::create([
            'user_id' => $userId,
            'balance' => 0.0,
        ]);
    }

    /**
     * Transfer funds between two wallets.
     *
     * @param  int  $fromUserId
     * @param  int  $toUserId
     * @param  float  $amount
     * @throws ModelNotFoundException
     * @throws Throwable
     * @return bool
     */
    public function transferFunds(int $fromUserId, int $toUserId, float $amount): bool
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Lock the wallets for both sender and receiver
            $fromWallet = Wallet::where('user_id', $fromUserId)->lockForUpdate()->firstOrFail();
            $toWallet = Wallet::where('user_id', $toUserId)->lockForUpdate()->firstOrFail();

            if ($fromWallet->balance < $amount) {
                return false;
            }

            $fromWallet->decrement('balance', $amount);
            $toWallet->increment('balance', $amount);

            
            DB::commit();

            return true;

        } catch (ModelNotFoundException $e) {
            DB::rollback();
            throw new ModelNotFoundException('Wallet not found for the given user.');
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get the wallet balance for a user.
     *
     * @param  int  $userId
     * @throws ModelNotFoundException
     * @return float
     */
    public function getWalletBalance(int $userId): float
    {
        try{
            $wallet = Wallet::where('user_id', $userId)->firstOrFail();
            return $wallet->balance;
        }catch(Throwable $e){
            return response()->json([
                'message' => $e->getMassage()
            ]);
        }
    }
}
