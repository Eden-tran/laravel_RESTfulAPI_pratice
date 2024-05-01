<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // nhớ validate
        $email = $request->email;
        $password = $request->password;
        $checkLogin = Auth::attempt([
            'email' => $email,
            'password' => $password
        ]);
        if ($checkLogin) {
            $user = Auth::user();
            //sanctum
            // $token = $user->createToken('auth_token')->plainTextToken;
            // thiết lập expires
            // $tokenResult = $user->createToken('auth_api');
            // $token = $tokenResult->token;
            // $token->expires_at = Carbon::now()->addMinutes(60);
            // trả về access token
            // $accessToken = $tokenResult->accessToken;
            // trả về expires
            // $expires = Carbon::parse($token->expires_at)->toDayDateTimeString();

            $client = Client::where('password_client', 1)->first();
            $scope = ['customer', 'invoice'];
            if ($client) {
                $clientSecret = $client->secret;
                $clientId = $client->id;
                $response = Http::asForm()->post('http://127.0.0.1:8001/oauth/token', [
                    'grant_type' => 'password',
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'username' => $email,
                    'password' =>  $password,
                    'scope' =>  ['customer', 'invoice'],
                ]);
                // $response = [
                //     'status' => 200,
                //     'token' => $accessToken,
                //     'expires' => $expires
                // ];
                return $response;
            }

            // return $client;
            // $response = Http::asForm()->post('oauth/token', [
            //     'grant_type' => 'password',
            //     'client_id' => 'client-id',
            //     'client_secret' => 'client-secret',
            //     'username' => 'taylor@laravel.com',
            //     'password' => 'my-password',
            //     'scope' => '',
            // ]);
            // return $response = [
            //     'status' => 200,
            //     'token' => $accessToken,
            //     'expire' => $expires
            // ];
        } else {
            $response = [
                'status' => 401,
                'title' => 'Unauthorized'
            ];
        }
        return $response;
    }
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $status = $user->token()->revoke();
            $response = [
                'status' => 200,
                'title' => 'logout',
            ];
        } else {
            $response = [
                'status' => 404,
                'title' => 'error',
            ];
        }

        return $response;
    }
    public function getToken(Request $request)
    {
        $user = User::find(1);
        // return $request->user()->tokens;
        return $request->user()->currentAccessToken()->delete();
        // return $request->user();
        // $user->tokens()->delete();
        // foreach ($user->tokens as $token) {
        //     echo $token->id . '-' . $token->token . "</br>";
        // }
        // $id = 6;
        // $user->tokens()->where('id', $id)->delete();
    }
    public function refreshToken(Request $request)
    {
        // if ($request->header('authorization')) {
        //     $hashToken = $request->header('authorization');
        //     $hashToken = trim(str_replace('Bearer', '', $hashToken));
        //     // return $hashToken;
        //     $token = PersonalAccessToken::findToken($hashToken);
        //     if ($token) {
        //         $tokenCreated = $token->created_at;
        //         $expire = Carbon::parse($tokenCreated)->addMinutes(config('sanctum.expiration'));
        //         if (Carbon::now() > $expire) {
        //             $user_id = $token->tokenable_id;
        //             $user = User::find($user_id);
        //             $user->tokens()->delete();
        //             $newToken = $user->createToken('auth_token')->plainTextToken;
        //             $response = [
        //                 'status' => 200,
        //                 'token' => $newToken
        //             ];
        //         } else {
        //             $response = [
        //                 'status' => 200,
        //                 'token' => 'Unexpired'
        //             ];
        //         }
        //     } else {
        //         $response = [
        //             'status' => 400,
        //             'token' => 'Unauthorized'
        //         ];
        //     }
        // }
        // return $response;
        $client = Client::where('password_client', 1)->first();
        if ($client) {
            $clientSecret = $client->secret;
            $clientId = $client->id;
            $refreshToken = $request->refresh;
            $response = Http::asForm()->post('http://127.0.0.1:8001/oauth/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => '',
            ]);
            return $response;
        }
    }
}
