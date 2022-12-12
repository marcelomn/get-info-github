<?php
/*
 * Class Github_OAuth_Client
 *
 * Author: CodexWorld
 * Author URL: https://www.codexworld.com
 * Author Email: admin@codexworld.com
 *
 * The first PHP Library to support OAuth for GitHub REST API.
 */
class GithubOAuthClient
{
    public $authorizeURL = "https://github.com/login/oauth/authorize";
    public $tokenURL = "https://github.com/login/oauth/access_token";
    public $apiURLBase = "https://api.github.com";
    public $clientID;
    public $clientSecret;
    public $redirectUri;

    /**
     * Construct object
     */
    public function __construct(array $config = []){
        $this->clientID = isset($config['client_id']) ? $config['client_id'] : '';
        if(!$this->clientID){
            die('Required "client_id" key not supplied in config');
        }

        $this->clientSecret = isset($config['client_secret']) ? $config['client_secret'] : '';
        if(!$this->clientSecret){
            die('Required "client_secret" key not supplied in config');
        }

        $this->redirectUri = isset($config['redirect_uri']) ? $config['redirect_uri'] : '';
    }

    /**
     * Get the authorize URL
     *
     * @returns a string
     */
    public function getAuthorizeURL($state){
        return $this->authorizeURL . '?' . http_build_query([
                'client_id' => $this->clientID,
                'redirect_uri' => $this->redirectUri,
                'state' => $state,
                'scope' => 'user:email'
            ]);
    }

    /**
     * Exchange token and code for an access token
     */
    public function getAccessToken($state, $oauthCode){
        $token = self::apiRequest($this->tokenURL . '?' . http_build_query([
                'client_id' => $this->clientID,
                'client_secret' => $this->clientSecret,
                'state' => $state,
                'code' => $oauthCode
            ]));
        return $token->access_token ?? $token;
    }

    /**
     * Make an API request
     *
     * @return API results
     */
    public function apiRequest($accessTokenUrl){
        $apiURL = filter_var($accessTokenUrl, FILTER_VALIDATE_URL)?$accessTokenUrl:$this->apiURLBase.'user?access_token='.$accessTokenUrl;
        $context  = stream_context_create([
            'http' => [
                'user_agent' => 'GetInfoGitHub GitHub OAuth Login',
                'header' => 'Accept: application/json'
            ]
        ]);
        $response = file_get_contents($apiURL, false, $context);

        return $response ? json_decode($response) : $response;
    }

    /**
     * Get the authenticated user
     *
     * @returns object
     */
    public function getAuthenticatedUser($accessToken) {
        if(is_object($accessToken)){
            return json_encode($accessToken);
        }else {
            $apiURL = $this->apiURLBase . '/user';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: token ' . $accessToken));
            curl_setopt($ch, CURLOPT_USERAGENT, 'GetInfoGitHub GitHub OAuth Login');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            $apiResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode != 200) {
                if (curl_errno($ch)) {
                    $errorMessage = curl_error($ch);
                } else {
                    $errorMessage = $apiResponse;
                }
                throw new Exception('Error ' . $httpCode . ': ' . $errorMessage);
            } else {
                return json_decode($apiResponse);
            }
        }
    }
}
