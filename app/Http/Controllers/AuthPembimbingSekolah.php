<?php

namespace App\Http\Controllers;

use App\Helpers\Fungsi;
use App\Models\jurusan;
use App\Models\pembimbingsekolah;
use App\Models\pendaftaranprakerin_proses;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthPembimbingSekolah extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:pembimbingsekolah', ['except' => ['login', 'register']]);
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

        $user = pembimbingsekolah::create([
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
        return $this->respondWithToken($this->guard()->refresh());
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());

        // return $dataRefresh;
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
        $periksaPenilai = false;
        $periksaKepalajurusan = false;
        $getJurusan = [];
        $me = $this->guard()->user();
        $periksa = jurusan::where('kepalajurusan_id', $me->id)
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->count();
        if ($periksa > 0) {
            $periksaKepalajurusan = true;
            $getJurusan = jurusan::where('kepalajurusan_id', $me->id)
                ->where('tapel_id', Fungsi::app_tapel_aktif())
                ->with('kelas')
                ->first();
        }
        $periksa = pendaftaranprakerin_proses::where('penilai_id', $me->id)
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->count();
        if ($periksa > 0) {
            $periksaPenilai = true;
        }
        return response()->json([
            'data' => (object)[
                'token' => $token,
                'me' => $this->guard()->user(),
                'newToken' => $token,
                'status' => true,
                'kepalajurusan' => $periksaKepalajurusan,
                'penilai' => $periksaPenilai,
                'jurusan' => $getJurusan,
            ],
            'message' => "Success",
            'code' => 200,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 1,  //auto logout after 1 hour (default)
        ]);
        // return response()->json([
        //     'token' => $token,
        //     'code' => 200,
        //     'token_type' => 'bearer',
        //     'expires_in' => $this->guard()->factory()->getTTL() * 1,  //auto logout after 1 hour (default)
        //     // 'tapel_aktif' => Fungsi::app_tapel_aktif(),
        // ]);
    }
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('pembimbingsekolah');
    }
}
