<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Token;

class TokenController extends Controller
{
    public function __construct(Request $request) {

    }

    public function generateToken (Request $request): JsonResponse {
        $token = bin2hex(random_bytes(137));
        $expiresAt = Carbon::now()->addMinutes(40);

        $newToken = Token::create([
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        $headers = [
            'success' => true,
            'message' => 'The token is valid for 40 minutes and can be used for only one request.'
        ];

        return new JsonResponse(
            $newToken,
            200,
            $headers,
            JSON_PRETTY_PRINT
        );
    }
}
