<?php

namespace App\Repositories\Interfaces;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface WalletRepositoryInterface
{
    /**
     * Create a wallet for a user.
     *
     * @param  int  $userId
     * @return Wallet
     */
    public function createWallet(int $userId): Wallet;

    /**
     * Transfer funds between two wallets.
     *
     * @param  int  $fromUserId
     * @param  int  $toUserId
     * @param  float  $amount
     * @throws ModelNotFoundException If one of the users does not have a wallet.
     * @throws \Exception If the balance is insufficient or transfer fails.
     * @return bool
     */
    public function transferFunds(int $fromUserId, int $toUserId, float $amount): bool;

    /**
     * Get the wallet balance for a user.
     *
     * @param  int  $userId
     * @throws ModelNotFoundException If the wallet is not found.
     * @return float
     */
    public function getWalletBalance(int $userId): float;
}
