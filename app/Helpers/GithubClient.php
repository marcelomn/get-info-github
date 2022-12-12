<?php

class GithubClient
{

    private $username;
    private $client;

    public function __construct()
    {
        $this->client = $this->githubClient();
    }

    public function githubOAuthClient()
    {
        return $gitClient = new \GithubOAuthClient(array(
            'client_id' => config('github.client_id'),
            'client_secret' => config('github.client_secret'),
            'redirect_uri' => url('/')
        ));
    }

    public function isAuthentiqued()
    {

//        if(is_null(session()->get('userData')) || is_null(session()->get('userData')->username)){
//            session()->forget('access_token');
//        }

        if(!is_null(session()->get('access_token'))) {
            return true;
        }
        return false;
    }

    public function gitUserData($gitUser)
    {
        return [
            'oauth_uid' => !empty($gitUser->id) ? $gitUser->id : '',
            'name' => !empty($gitUser->name) ? $gitUser->name: '',
            'username' => !empty($gitUser->login) ? $gitUser->login : '',
            'email' => !empty($gitUser->email) ? $gitUser->email : '',
            'location' => !empty($gitUser->location) ? $gitUser->location : '',
            'picture' => !empty($gitUser->avatar_url) ? $gitUser->avatar_url : '',
            'link' => !empty($gitUser->html_url) ? $gitUser->html_url : '',
            'oauth_provider' => 'github',
        ];
    }

    public function githubClient()
    {
        return new \Github\Client();
    }

    public function repositories($username)
    {
        return $this->githubClient()->api('user')->repositories($username);
    }

    public function commits($username, $repository)
    {
        return $this->githubClient()->api('repo')->commits()->all($username, $repository, array('sha' => 'main'));
    }
}
