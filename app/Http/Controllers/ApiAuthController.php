<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\BadResponseException as BadException;
use GuzzleHttp\Client as GuzzleHttpClient;
class ApiAuthController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $http = new GuzzleHttpClient;

                $response = $http->post('http://localhost:8000/api/oauth/token', [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => 2,
                        'client_secret' => '9G81CVfVssrrFKtu6Ui1E2MdwWlyZkTAHKV7CK8y',
                        'username' => 'asdasd',
                        'password' => 'asdasd',
                        'scope' => '',
                    ],
                ]);

                return $response->getBody();
        } catch (BadException $e) {
            if(response()->json('error', $e->getCode() == 400)) {
                return response()->json(['ok'=>'0', 'error'=>'invalid Request. Please enter Username and Password']);
            } elseif (response()->json('error', $e->getCode() == 401)) {
                return response()->json('Your Credentials incorrect'. 'Please Try Again');
            } return  response()->json('Something went wrong on the server.', $e->getCode());
        }
    }

    public function register(storeUserRequest $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return new UserResource($user);
    }

    public function logout()
    {
        return auth()->user()->tokens->each(function($token){
            $token->delete();
        });

        return response()->json("You've been logged out", 200);
    }
}
