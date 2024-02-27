<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Admin\UserHasBeenTakenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignupRequest;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('JWT', ['except' => ['login', 'signup']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function signup(SignupRequest $request)
    {
        //MODEL JÁ VEM HASHED NA VERSÃO NOVA DO LARAVEL
            // $input['password'] = bcrypt($input['password']); 
        $input = $request->validated();
        if(User::query()->whereEmail($input['email'])->exists()) {
            throw new UserHasBeenTakenException();
        }
        $user = User::query()->create($input);

    return $this->login($request);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        // return response()->json(auth()->user());
        return response()->json([
            "id" => auth()->user()->id,
            "first_name" => auth()->user()->first_name,
            "last_name" => auth()->user()->last_name,
            "email" => auth()->user()->email,
            "created_at" => auth()->user()->created_at->diffForHumans(),
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 1,
            'user' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
            'created_at' => auth()->user()->created_at->diffForHumans(),

        ]);
    }
}
