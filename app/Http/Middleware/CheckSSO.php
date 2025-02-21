<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSSO
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // auth()->loginUsingId(1);


        $secretKey = env('SHARED_SECRET_KEY');
        $cookie = $request->cookie('sso');
       // dd($secretKey);
        if ($cookie) {
            list($payload, $signature) = explode('::', base64_decode($cookie), 2);

            if (hash_hmac('sha256', $payload, $secretKey) === $signature) {
                $data = json_decode($payload, true);
                $userId = $data['user_id'] ?? null;

                if ($userId) {
                    // Optionally, authenticate the user in Laravel
                    auth()->loginUsingId($userId);
                }
            }
        } else {
            return redirect('https://simpol.hasta.my.id/simpol');
        }
        // auth()->loginUsingId(1);

        return $next($request);
    }
}
