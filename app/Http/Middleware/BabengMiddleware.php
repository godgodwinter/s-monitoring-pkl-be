<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BabengMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if ($role === 'adminOwner') {
            if (Auth::guard()->user() || Auth::guard('pembimbinglapangan')->user() || Auth::guard('pembimbingsekolah')->user()) {

                return $next($request);
            } else {
                return response()->json([
                    'success'    => false,
                    'message'    => 'Silahkan Login Terlebih Dahulu',
                ], 401);
            }
        } else {
            return response()->json([
                'success'    => false,
                'message'    => 'Role tidak ditemukan',
            ], 401);
        }
    }
}
