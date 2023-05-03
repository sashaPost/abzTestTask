<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Token;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{
    public function __construct (Request $request) {

    }

    // it renders the wrong time (three hours earlier), debugger shows the correct value;
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
            'expires_at' => $newToken->expires_at,
            'used' => $newToken->used,
        ]);
    }

    public function testStatic()
    {
        static $count;
        
        if(!is_int($count)) $count = 0;
        $count++;
    }
}
