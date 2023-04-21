<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->get('token')) {  // should I add 'token' and it's properties as header?
            // I can put a parameter such as 'token' into request whet user submits login form
            // No, it should be added as Header

            // check if token was 'used' and 'expired'
            // throw an exception if so
            // else:
            return redirect('/users');   // 'users - root
        }

        return $next($request);
    }
}
