<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\WalletRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    protected WalletRepositoryInterface $walletRepo;

    /**
     * WalletController constructor.
     *
     * @param  WalletRepositoryInterface  $walletRepo
     */
    public function __construct(WalletRepositoryInterface $walletRepo)
    {
        $this->walletRepo = $walletRepo;
    }

    /**
     * Transfer funds between two wallets.
     *
     * @param  Request  $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function transfer(Request $request): JsonResponse
    {
    
        try{
             // Validate request parameters
            $request->validate([
                'from_user_id' => 'required|integer',
                'to_user_id' => 'required|integer',
                'amount' => 'required|numeric|min:0.01',
            ]);

            // Call repository method to perform the fund transfer
            if(!$this->walletRepo->transferFunds($request->from_user_id,$request->to_user_id,$request->amount))
            {
                return response()->json(['error' => 'Failed due to insufficient balance!'], 400);
            };

            return response()->json(['message' => 'Transfer successful!'], 200);

        }catch(Throwable $e){
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    
    }

    /**
     * Get the balance of a user's wallet.
     *
     * @param  int  $userId
     * @return JsonResponse
     */
    public function getBalance(int $userId): JsonResponse
    {
        try{
            // Retrieve the wallet balance for the given user
            $balance = $this->walletRepo->getWalletBalance($userId);
            
            return response()->json(['balance' => $balance]);

        }catch(Throwable $e){
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }   
    }
}
