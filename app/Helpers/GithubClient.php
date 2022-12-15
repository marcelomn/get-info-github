<?php

class GithubClient
{

    private $client;

    public function __construct()
    {
        $this->client = $this->githubClient();
    }

    /**
     * Autenticação.
     *
     * @return GithubOAuthClient
     */
    public function githubOAuthClient()
    {
        return $gitClient = new \GithubOAuthClient(array(
            'client_id' => config('github.client_id'),
            'client_secret' => config('github.client_secret'),
            'redirect_uri' => url('/')
        ));
    }

    /**
     * Verifica se está autenticado.
     *
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function isAuthentiqued()
    {

        if(!is_null(session()->get('access_token'))) {
            return true;
        }
        return false;
    }

    /**
     * Pega dados do usuário autenticado e retorna em um array.
     *
     * @param $gitUser
     * @return array
     */
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

    /**
     * Retorna a instancia da classe Client do Github.
     *
     * @return \Github\Client
     */
    public function githubClient()
    {
        return new \Github\Client();
    }

    /**
     * Lista respositórios via API.
     *
     * @param $username
     * @return mixed
     */
    public function repositories($username)
    {
        return $this->githubClient()->api('user')->repositories($username);
    }

    /**
     * Lista commits via API.
     *
     * @param $username
     * @param $repository
     * @param array $params
     * @return null
     */
    public function commits($username, $repository, array $params = [])
    {
        try {
            $params = array_merge(['sha' => 'main'], $params);
            return $this->githubClient()->api('repo')->commits()->all($username, $repository, $params);
        }catch (RuntimeException){
            return null;
        }
    }

    /**
     * Retorma dados do commit.
     *
     * @param $username
     * @param $repository
     * @param $sha
     * @return mixed
     */
    public function commit($username, $repository, $sha)
    {
        return $this->githubClient()->api('repo')->commits()->show($username, $repository, $sha);
    }
}
