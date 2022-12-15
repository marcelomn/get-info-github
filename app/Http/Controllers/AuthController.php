<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserORIGINAL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function authentication(Request $request)
    {

        $gitClient = new \GithubOAuthClient(array(
            'client_id' => config('github.client_id'),
            'client_secret' => config('github.client_secret'),
            'redirect_uri' => url('/')
        ));

        $githubClient = new \GithubClient();

        $authUrl = null;
        $userData = null;

        if(session()->get('access_token') !== null) {
//            dd(session()->get('access_token'));
            $gitUser = $gitClient->getAuthenticatedUser(session()->get('access_token'));
            if(!is_object($gitUser)) {
                $gitUserDecode = json_decode($gitUser);
                if (isset($gitUserDecode->error)) {
                    return view('authentication', ['error_description' => $gitUserDecode->error_description]);
                }
            }

            if(!empty($gitUser)){

                $gitUserData = $githubClient->gitUserData($gitUser);

                $userData = User::where($gitUserData)->first();
                if(!$userData){
                    $userData  = User::create($gitUserData);
                }

                session()->put('userData', $userData);

                return redirect()->route('github.index');

            }
        }elseif(isset($request->code)) {
            if(!$request->state || session()->get('state') != $request->state) {
                return redirect()->route('authentication');
            }

            // Exchange the auth code for a token
            $accessToken = $gitClient->getAccessToken($request->state, $request->code);

            session()->put('access_token', $accessToken);

            $gitUser = $gitClient->getAuthenticatedUser($accessToken);

            $gitUserData = $githubClient->gitUserData($gitUser);
            $userData = User::where($gitUserData)->first();

            session()->put('userData', $userData);

            return redirect()->route('github.index');
        }else{
            // Generate a random hash and store in the session for security
            $hash = hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']);

            session()->put('state', $hash);

            // Remove access token from the session
            session()->forget('access_token');

            // Get the URL to authorize
            $authUrl = $gitClient->getAuthorizeURL($hash);
        }
//        echo Hash::make('010203');
        return view('authentication', [
            'authUrl' => $authUrl,
            'userData' => $userData,
        ]);
    }

    public function logout(Request $request)
    {
        session()->forget('access_token');
        session()->forget('state');
        session()->forget('userData');

        return redirect()->route('authentication');
    }
}
