<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;

class CheckIpLocation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = Http::get("http://ip-api.com/json");
        
        if ($response->successful()) {
            $data = $response->json();
            if ($data['country'] !== 'Nigeria') {
                return response()->json(['error' => 'Only users from Nigeria are allowed'], 403);
            }
        }
        
        return $next($request);
    }
}
