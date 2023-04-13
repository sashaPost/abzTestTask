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

        return new JsonResponse([
            'success' => true,
            'token' => $newToken->token,
        ]);
    }
}
