<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\WalletRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    protected UserRepositoryInterface $userRepo;
    protected WalletRepositoryInterface $walletRepo;

    /**
     * UserController constructor.
     *
     * @param  UserRepositoryInterface  $userRepo
     * @param  WalletRepositoryInterface  $walletRepo
     */
    public function __construct(UserRepositoryInterface $userRepo, WalletRepositoryInterface $walletRepo)
    {
        $this->userRepo = $userRepo;
        $this->walletRepo = $walletRepo;
    }

    /**
     * Register a new user and create a wallet for them.
     *
     * @param  Request  $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws Throwable
     */
    public function register(Request $request): JsonResponse
    {
        try{
            $validatedData = $request->validate([
                'username' => 'required|string|unique:users|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);

            // User registration logic
            $createdUser = $this->userRepo->createUser($validatedData);
            $token = $createdUser->createToken('Authentication Token for '. $createdUser->email)->plainTextToken;
            $this->walletRepo->createWallet($createdUser->id);

            return response()->json(['message' => 'User registered successfully', 'token' => $token], 201);
        }catch(Throwable $e){
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * Login a user with their username and password.
     *
     * @param  Request  $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws Throwable
     */
    public function login(Request $request): JsonResponse
    {
        try{
            $credentials = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string|min:8',
            ]);
    
            // Attempt to authenticate the user
            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid username or password'], 401);
            }
    
            // Get the authenticated user
            $user = Auth::user();
    
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status_code' => 401,
                    'message' => 'The provided credentials do not match.'
                ], 401);
            }
    
            $token = $user->createToken('Token')->plainTextToken;
            return response()->json([
                'user' => new UserResource($user),
                'message' => 'Login successful',
                'token' => $token
            ], 200);

        }catch(Throwable $e){
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * List users with pagination.
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function listUsers(): JsonResponse
    {
        try{
            $users = $this->userRepo->getAllUsersByPagination(10);
            return response()->json(new UserCollection($users));
        }catch(Throwable $e){
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            auth()->user()->tokens()->delete();
            return response()->json([
                'message' => 'Logged out successfully!'
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
