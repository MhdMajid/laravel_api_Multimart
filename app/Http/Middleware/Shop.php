<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Shop as findShop;
use Illuminate\Support\Facades\Auth;

class Shop
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(findShop::where(
            'user_id',Auth::user()->id)->exists()
             ||auth()->user()->role == 'admin' ||
             auth()->user()->role == 'superadmin')
             {
                 return $next($request);
             }
        else{
            return  response()->json('not have a shop', 404);
        }
    }
}
