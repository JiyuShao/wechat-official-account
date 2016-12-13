<?php

class PixivAPI
{

    protected $oauth_url = 'https://oauth.secure.pixiv.net/auth/token';

    protected $oauth_client_id = 'bYGKuGVw91e0NMfPGp44euvGt59s';

    protected $oauth_client_secret = 'HP3RmkgAmEGro0gn1x9ioawQE8WMfvLXDz3ZqxpK';

    protected $api_host = 'Host: public-api.secure.pixiv.net';

    protected $api_referer = 'http://spapi.pixiv.net/';

    protected $api_authorization = 'Authorization: Bearer 8mMXXWT9iuwdJvsVIvQsFYDwuZpRCMePeyagSh30ZdU';

    protected $api_useragent = 'PixivIOSApp/5.6.0';

    protected $api_content_type = 'Content-Type: application/x-www-form-urlencoded';

    protected $access_token = '';

    public function __construct()
    {
        if (! in_array('curl', get_loaded_extensions())) {
            throw new Exception('You need to install cURL, see: http://curl.haxx.se/docs/install.html');
        }
    }

    public function login($user, $pwd)
    {
        // create cookie file
        $fp = fopen("cookie.txt", "w");
        fclose($fp);
        // enable cookie
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
        // setup curl
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        // set header
        curl_setopt($ch, CURLOPT_URL, $this->oauth_url);
        curl_setopt($ch, CURLOPT_REFERER, $this->api_referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->api_useragent);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $this->api_content_type,
            $this->api_authorization
        ));
        // set post
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            'username' => $user,
            'password' => $pwd,
            'grant_type' => 'password',
            'client_id' => $this->oauth_client_id,
            'client_secret' => $this->oauth_client_secret
        )));
        // disable ssl verifypeer
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // get result
        $result = curl_exec($ch);
        curl_close($ch);
        $object = json_decode($result);
        if (isset($object->has_error)) {
            throw new Exception('Login error: ' . $object->errors->system->message);
        }
        // update authorization
        $this->access_token = $object->response->access_token;
        $this->api_authorization = 'Authorization: Bearer ' . $this->access_token;
        
        return $result;
    }

    public function grab_page($url)
    {
        // start curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        // set header
        curl_setopt($ch, CURLOPT_REFERER, $this->api_referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->api_useragent);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $this->api_content_type,
            $this->api_authorization
        ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
        // disable ssl verifypeer
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        ob_start();
        // get result
        $result = curl_exec($ch);
        ob_end_clean();
        curl_close($ch);
        
        return $result;
    }

    public function getRanking($mode)
    {
        $url = 'https://public-api.secure.pixiv.net/v1/ranking/all?image_sizes=px_128x128%2Cpx_480mw%2Clarge&include_stats=true&page=1&profile_image_sizes=px_170x170%2Cpx_50x50&mode='.$mode.'&include_sanity_level=true&per_page=10';
        return $this->grab_page($url);
    }
}
?>