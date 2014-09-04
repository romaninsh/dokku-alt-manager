<?php
class DigitalOceanStrategy extends OpauthStrategy {
    public $expects = array('client_id', 'client_secret');
    public $defaults = array(
        'redirect_uri' => '{complete_url_to_strategy}callback'
    );

    public function request(){
        $url = 'https://cloud.digitalocean.com/v1/oauth/authorize';
        $params = array(
            'response_type' => 'code',
            'client_id' => $this->strategy['client_id'],
            'redirect_uri' => $this->strategy['redirect_uri']
        );
            //var_Dump($this->strategy['redirect_uri']);
            //exit;

        if (!empty($this->strategy['scope'])) $params['scope'] = $this->strategy['scope'];
        if (!empty($this->strategy['state'])) $params['state'] = $this->strategy['state'];

        $this->clientGet($url, $params);
    }
    public function callback(){
        if (array_key_exists('code', $_GET) && !empty($_GET['code'])){
            $url = 'https://cloud.digitalocean.com/v1/oauth/token';
            $params = array(
                'grant_type' => 'authorization_code',
                'client_id' =>$this->strategy['client_id'],
                'client_secret' => $this->strategy['client_secret'],
                'redirect_uri'=> $this->strategy['redirect_uri'],
                'code' => trim($_GET['code'])
            );
            $response = $this->serverPost($url, $params, null, $headers);

            parse_str($response, $results);

            if (!empty($results) && !empty($results['access_token'])){
                $me = $this->me($results['access_token']);

                $this->auth = array(
                    'provider' => 'DigitalOcean',
                    'info' => array(
                        'name' => $me->name,
                        'email' => $me->email
                    ),
                    'credentials' => array(
                        'token' => $results['access_token'],
                        'expires' => date('c', time() + $results['expires'])
                    ),
                    'raw' => $me
                );

                $this->callback();
            }
            else{
                $error = array(
                    'provider' => 'DigitalOcean',
                    'code' => 'access_token_error',
                    'message' => 'Failed when attempting to obtain access token',
                    'raw' => $headers
                );

                $this->errorCallback($error);
            }
        }
        else{
            $error = array(
                'provider' => 'DigitalOcean',
                'code' => $_GET['error'],
                'message' => $_GET['error_description'],
                'raw' => $_GET
            );

            $this->errorCallback($error);
        }
    }
}
