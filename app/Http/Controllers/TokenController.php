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

    public function generateToken (Request $request): JsonResponse {
        // dumb tests:
        // dump($newToken);

        // echo('hashed token');
        // $str = 'hashed token';
        // $response = new Response();
        // $response->header('str', $str);
        // dump($response);

        // null:
        // dump($request);
        // $hashedToken = Hash::make($request);
        // dump($hashedToken);
        
        // $test = Hash::make('');
        // dump(Hash::info($hashedToken));
        // $request->newPassword = $test;

        $token = bin2hex(random_bytes(137));
        $expiresAt = Carbon::now()->addMinutes(40);

        $newToken = Token::create([
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);
        
        return new JsonResponse([
            'success' => true,
            'token' => $newToken->token,
            // 'hashed token' => $hashedToken,  // hashed token from null
        ]);
    }

    public function testStatic()
    {
        static $count;
        
        if(!is_int($count)) $count = 0;
        $count++;
    }
}
