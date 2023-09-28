<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSingleDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Cek apakah pengguna memiliki lebih dari satu perangkat yang terhubung
        $devicesCount = Device::where('user_id', $user->id)->count();

        if ($devicesCount > 1) {
            // Hapus semua token otentikasi pengguna
            $user->tokens->each(function ($token) {
                $token->delete();
            });

            // Logout pengguna dari semua perangkat
            Device::where('user_id', $user->id)->delete();

            return response()->json(['message' => 'Anda hanya diizinkan login dari satu perangkat. Anda telah logout dari semua perangkat.'], 401);
        }
        return $next($request);
    }
}
