<?php

namespace App\Http\Middleware;

use Closure;

use Carbon\Carbon;
use App\Models\Token;
use Illuminate\Http\JsonResponse;
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
        $requestToken = $request->header('Token');
        $sourceRecord = Token::where('token', $requestToken)->first();
        
        $sourceToken = $sourceRecord->token;
        $expires = $sourceRecord->expires_at;    // "2023-05-01 14:24:58"
        $used = $sourceRecord->used;

        // 'has been used' test:
        // $sourceRecord->used = 1;
        // $sourceRecord->save();

        $now = Carbon::now();   // "2023-05-01 14:34:01.145200"

        // perform the codes and responses check:
        if ($requestToken !== $sourceToken) {
            return new JsonResponse([
                "success" => false,
                "message" => 'Invalid access token',
            ], 401);
        } elseif ($now > $expires) {
            return new JsonResponse([
                "success" => false,
                "message" => "The token expired.",
            ], 401);
        } elseif (! is_null($used)) {
            return new JsonResponse([
                "success" => false,
                "message" => 'The token has already been used before.',
            ], 409);
        }

        return $next($request);
    }
}
