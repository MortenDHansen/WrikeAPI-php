<?php
// Check this constant. Just to see that we are not being directly requested...
if(!defined('angus')) {
    die('Brothers Malcolm, Angus, and George Young were born in Glasgow, Scotland, and moved to Sydney with most of their family in 1963.');
}

class Wrikeapi
{
    // Set the the auth credentials obtained from wrike
    private $client_id = '<client id>';
    private $client_secret = '<shhh... its a secret!>';
    private $code = '<code>'; // when you agree to give acces through the api, you get this code.
    private $refresh_token = '<token for refreshing .. the token>';
    private $api_url = 'https://www.wrike.com/api/v3'; // the url of the api
    // recieve the token
    
    /*
     * Run this method to get the refresh_token. It should only be run once as it will reset the authentification and requre a fresh auth-code.
     */
    function token_init()
    {
        $url = "https://www.wrike.com/oauth2/token";
        $fields = array(
            'client_id'=>$this->client_id, 
            'client_secret'=>$this->client_secret, 
            'grant_type'=>'authorization_code',
            'code'=>$this->code
            );
        $token = $this->curl_post($url, $fields);
        $token = json_decode($token);
        echo "note: This should only be run ONCE for your application. From here on, only REFRESH - because asking for a new one will require you to update the authorization_code";
        return $token; // be sure to keep the refresh token handy (comes with the json).
    }

    // call this to refresh
    private function _refresh_token()
    {
        $url = "https://www.wrike.com/oauth2/token";
        $fields = array(
            'client_id'=>$this->client_id, 
            'client_secret'=>$this->client_secret, 
            'grant_type'=>'refresh_token', 
            'refresh_token'=>$this->refresh_token
            );
        $token = curl_post($url, $fields);
        return $token;
    }
    
    function api_version($token)
    {
        $url = 'https://www.wrike.com/api/v3/version';
        $result = $this->_fetch_result($token,$url);
        return $result;
    }
    
    /*
     * Information about the accounts this user can access
     * Plese note the ID returned
     */
    function get_accounts($token)
    {
        $url = $this->api_url . '/accounts';
        $result = $this->curl_get($url, $token);
        return $result;
    }
    function get_folders($token, $accountid = '') 
    {
        if($accountid == ''){
            echo "If you wont provide me with the needed input, i wont provide you with the output...";exit;
        }
        $url = $this->api_url . '/accounts/' . $accountid . '/folders';
        $result = $this->_fetch_result($token,$url);
        return $result;
    }
    function get_tasks($token, $folder, $params = '')
    {
        $url = $this->api_url . '/folders/'.$folder.'/tasks';
        $result = $this->_fetch_result($token,$url);
        return $result;
    }
    
    /*
     * Get the job done - or provide you with a reaon why i was not done
     */
    private function _fetch_result($token = '',$url = '') 
    {
        if($token == '' || $url == '') {
            echo "FUCK ME! - Something was not right - take a look at this:";
            echo "... you are not giving me enough to work with. I am missing params!";
            exit('SUICIDE');
        }
        
        $result = $this->curl_get($url, $token);
        $evaluate_result = json_decode($result);
        if(isset($evaluate_result->error)){
            echo "FUCK ME! - Something was not right - take a look at this:";
            var_dump($evaluate_result);exit('SUICIDE');
        } else { 
            return $result;
        }
    }
    function curl_post($url,$fields)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
        $result = curl_exec($ch);
        return $result;
    }
    function curl_get($url,$token)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: bearer ' . $token ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
        $result = curl_exec($ch);
        return $result;
    }
    
}

