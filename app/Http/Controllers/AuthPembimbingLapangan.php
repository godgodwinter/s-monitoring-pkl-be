<?php

namespace App\Http\Controllers;

use App\Models\pembimbinglapangan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthPembimbingLapangan extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:pembimbinglapangan', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|',
            'password' => 'required|string',
        ]);
        $email = $request->email;
        // $credentials = $request->only('email', 'password');
        $credentials = [
            'email' => $email,
            'password' => $request->password,
        ];
        // dd($credentials);
        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }
        $credentials = [
            'username' => $email,
            'password' => $request->password,
        ];
        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }
        $credentials = [
            'nomeridentitas' => $email,
            'password' => $request->password,
        ];
        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }


        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3',
        ]);

        $user = pembimbinglapangan::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'nomeridentitas' => $request->nomeridentitas,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {

        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->pembimbinglapangan());
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $dataRefresh = $this->respondWithToken($this->guard()->refresh());

        return $dataRefresh;
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
            'success' => true,
            'data' => (object)['token' => $token],
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 1  //auto logout after 1 hour (default)
        ], 200);
    }
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('pembimbinglapangan');
    }
}
