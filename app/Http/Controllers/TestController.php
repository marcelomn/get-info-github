<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    private $username;
    private $password;
    private $clientId;
    private $clientSecret;
    private $token;
    private $appID;
    private $client;

    public function __construct()
    {
        $this->username = config('github.username');
        $this->password = config('github.password');
        $this->clientId = config('github.client_id');
        $this->clientSecret = config('github.client_secret');
        $this->token = config('github.token');
        $this->appID = config('github.app_id');

        $this->client = new \Github\Client();
    }

    public function show()
    {
        echo '<h3>Test</h3>';

        $repositories = $this->client->api('user')->repositories($this->username);
        $followers = $this->client->api('user')->followers(1);

        dd([
            'repositories'=>$repositories,
            'followers'=>$followers
        ]);


    }

    public function followers()
    {
        // https://api.github.com/users/marcelomn/followers

        $followers = $this->client->api('user')->followers($this->username);
        dump($followers);
    }

    public function following()
    {
        // https://api.github.com/users/marcelomn/following

        $following = $this->client->api('user')->following($this->username);
        dump($following);
    }

    public function repositories()
    {
        // https://api.github.com/users/{username}/{repository}

        $repositories = $this->client->api('user')->repositories($this->username);
        dump($repositories);
    }

    public function commits()
    {
        // https://api.github.com/users/{username}/{repository}/commits

        $repository = 'get-info-github';
        $commits = $this->client->api('repo')->commits()->all($this->username, $repository, array('sha' => 'main'));
        dump($commits);
    }
    public function commit()
    {
        // https://api.github.com/users/{username}/{repository}/commits/{sha}

        $repository = 'get-info-github';
        $commit = $this->client->api('repo')->commits()->show($this->username, $repository, 'main');
//        $commit = $this->client->api('repo')->commits()->show($this->username, $repository, 'b27dd156c12f13a397daa401774b449e3679e312');
//        $commit = $this->client->api('gitData')->commits()->show($this->username, $repository, 'b27dd156c12f13a397daa401774b449e3679e312');
        dump($commit);
    }

    public function getuser()
    {
        // https://api.github.com/users/marcelomn

        $user = $this->client->api('user')->show($this->username);

        dump($user);
    }

    public function authorizeGit()
    {
        return view('test.authorize', ['clientId'=>$this->clientId]);
    }

    public function chart()
    {
        return view('test.chart');
    }

}
