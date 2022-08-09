<?php

class CakeToLaravelException extends Exception {
    //$message is now not optional, just for the extension.
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class StudentFraudDetectionException extends Exception {
    //$message is now not optional, just for the extension.
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class CoreConnector {

    /**
     * Singleton pattern.
     *
     * @var CoreConnector
     */
    private static $instance;

    private $user;
    private $apiKey;
    private $baseUrl;
    private $sessionHash;

    private $lastResponse;
    private $lastCode;
    private $url;
    private $params;
    private $method;

    public function __construct()
    {
        $this->baseUrl = Configure::read('core_url');
    }

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setApiKey($key)
    {
        $this->apiKey = $key;
    }

    public function setUser($key)
    {
        $this->user = $key;
    }

    public function hasUser()
    {
        return (bool) $this->user;
    }

    public function setSessionHash($hash) {
        $this->sessionHash = $hash;
    }

    public function hasSessionHash()
    {
        return (bool) $this->sessionHash;
    }

    public function fetchKeys($email, $password, $captcha = false, $ip = false)
    {
        $handle = $this->_getHandle('/auth', "POST");
        $body = array('user' => $email, 'password' => $password, 'captcha' => $captcha,'ip' => $ip);
        curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($body));

        return $this->_execute($handle);
    }

    /**
     * Execute a get request to the core.
     *
     * @param string $path
     * @param array  $params
     *
     * @return mixed
     */
    public function getRequest($path, $params)
    {

        $params['session_hash'] = $this->sessionHash;
        $params['user'] = $this->user;

        $url = $path . "?" . http_build_query($params);
        $validationHash = $this->_generateHash($url);
        $params['signature'] = $validationHash;

        // Include signature
        $finalUrl = $path . "?" . http_build_query($params);
        //$response = file_get_contents($this->baseUrl .$finalUrl);
        $this->params = $params;
        $this->url = $finalUrl;
        $this->method = 'GET';
<<<<<<< HEAD

        $handle = $this->_getHandle($finalUrl, "GET");
        $this->_execute($handle);
        

=======
>>>>>>> @{-1}
        return $this->_execute($this->_getHandle($finalUrl, "GET"));
    }

    public function getJsonRequest($path, $params)
    {

        $params['session_hash'] = $this->sessionHash;
        $params['user'] = $this->user;

        $url = $path . "?" . http_build_query($params);
        $validationHash = $this->_generateHash($url);
        $params['signature'] = $validationHash;

        // Include signature
        $finalUrl = $path . "?" . http_build_query($params);
        //$response = file_get_contents($this->baseUrl .$finalUrl);

        $this->params = $params;
        $this->url = $finalUrl;
        $this->method = 'JSON';


        return $this->_execute($this->_getHandle($finalUrl, "GET"), false);
    }

    public function getDownloadRequest($path, $params)
    {
        $params['session_hash'] = $this->sessionHash;
        $params['user'] = $this->user;
        $url = $path . "?" . http_build_query($params);
        $validationHash = $this->_generateHash($url);
        $params['signature'] = $validationHash;

        // Include signature
        $finalUrl = $path . "?" . http_build_query($params);

        $this->params = $params;
        $this->url = $finalUrl;
        $this->method = 'DOWNLOAD';

        return $this->_execute($this->_getHandle($finalUrl, "GET"), false);
    }

    public function postRequest($path, $params, $body, $decode = true)
    {
        $params['session_hash'] = $this->sessionHash;
        $params['user'] = $this->user;
        $url = $path . "?" . http_build_query($params);

        $validationHash = $this->_generateHash($url);
        $params['signature'] = $validationHash;

        // Include signature
        $finalUrl = $path . "?" . http_build_query($params);

        $this->params = $params;
        $this->url = $finalUrl;
        $this->method = 'POST';

        $handle = $this->_getHandle($finalUrl, "POST");
        $body = json_encode($body);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $body);
        $headers = [
            "Content-Type" => "application/json"
        ];

        return $this->_execute($handle,$decode,$headers);
    }

    public function postRequestFile($path, $params, $body)
    {
        $params['session_hash'] = $this->sessionHash;
        $params['user'] = $this->user;
        $url = $path . "?" . http_build_query($params);
        $validationHash = $this->_generateHash($url);
        $params['signature'] = $validationHash;

        // Include signature
        $finalUrl = $path . "?" . http_build_query($params);

        $this->params = $params;
        $this->url = $finalUrl;
        $this->method = 'POSTFILE';

        $handle = $this->_getHandle($finalUrl, "POST");
        curl_setopt($handle, CURLOPT_POST,1);
        $headers = [
            "Content-Type" => "multipart/form-data",
        ];
        curl_setopt($handle, CURLOPT_POSTFIELDS, $body);

        return $this->_execute($handle,true,$headers);
    }

    public function putRequest($path, $params, $body)
    {
        $params['session_hash'] = $this->sessionHash;
        $params['user'] = $this->user;
        $url = $path . "?" . http_build_query($params);
        $validationHash = $this->_generateHash($url);
        $params['signature'] = $validationHash;

        // Include signature
        $finalUrl = $path . "?" . http_build_query($params);

        $this->params = $params;
        $this->url = $finalUrl;
        $this->method = 'PUT';

        $handle = $this->_getHandle($finalUrl, "PUT");
        $body = json_encode($body);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $body);
        $headers = [
            "Content-Type" => "application/json",
            "Content-Length" => strlen($body)
        ];
        return $this->_execute($handle,true,$headers);
    }

    public function putRequestFile($path, $params, $body)
    {

        $params['session_hash'] = $this->sessionHash;
        $params['user'] = $this->user;
        $url = $path . "?" . http_build_query($params);
        $validationHash = $this->_generateHash($url);
        $params['signature'] = $validationHash;

        // Include signature
        $finalUrl = $path . "?" . http_build_query($params);

        $this->params = $params;
        $this->url = $finalUrl;
        $this->method = 'PUTFILE';

        $handle = $this->_getHandle($finalUrl, "POST");
        curl_setopt($handle, CURLOPT_POST,1);
        $headers = [
            "Content-Type" => "multipart/form-data",
        ];

        $body['_method'] = 'PUT';

        $newBody = [];

        foreach($body as $key => $value) {
            if(is_array($value)) {

                if(empty($value)) {
                    $newBody[$key] = null;
                }else {
                    foreach ($value as $keyItem => $valueItem) {
                        $newBody[$key . '[' . $keyItem . ']'] = $valueItem;
                    }
                }
            }else{
                $newBody[$key] = $value;
            }
        }



        curl_setopt($handle, CURLOPT_POSTFIELDS, $newBody);

        return $this->_execute($handle,true,$headers);
    }

    public function deleteRequest($path, $params)
    {
        $params['session_hash'] = $this->sessionHash;
        $params['user'] = $this->user;
        $url = $path . "?" . http_build_query($params);
        $validationHash = $this->_generateHash($url);
        $params['signature'] = $validationHash;

        // Include signature
        $finalUrl = $path . "?" . http_build_query($params);

        $this->params = $params;
        $this->url = $finalUrl;
        $this->method = 'DELETE';

        return $this->_execute($this->_getHandle($finalUrl, "DELETE"));
    }

    private function _execute($handle, $decode = true, $headers = [])
    {
        curl_setopt($handle, CURLINFO_HEADER_OUT, true);
        curl_setopt($handle, CURLOPT_VERBOSE, true);
        if (substr(Router::fullBaseUrl(), -5) === '.test' || substr(Router::fullBaseUrl(), -7) === '.test/#') {
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        }
        $headers['cakeLaravelFilterKey'] = Configure::read('cake_laravel_filter_key');
        $headers['cakeRealIP'] = $_SERVER["REMOTE_ADDR"];
        $headers['cakeUrlPath'] = strtok($_SERVER["REQUEST_URI"], '?');

        foreach($headers as $key => $value){
            $_headers[] = sprintf('%s: %s',$key,$value);
        }
        curl_setopt($handle, CURLOPT_HTTPHEADER, $_headers);
        $response = curl_exec($handle);
        $this->lastCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $headers = curl_getinfo($handle, CURLINFO_HEADER_OUT);
        curl_close($handle);


        App::uses('SobitLogger','Lib');
        SobitLogger::getInstance()->endSub();

        if($this->getLastCode() == 440 || ($this->getLastCode() == 500 && $response == 'Session expired.')) {
            die('logout');
        }


        if(($this->getLastCode() == 500 || $this->getLastCode() == 404) && (! $this->responseMarkedAsDontReport($response) && Configure::read('bugsnag-key-cake') != null)){
            App::uses('BugsnagLogger','Lib');
            BugsnagLogger::getInstance()->setMetaData([
                'response' => $response,
                'headers' => $headers,
                'passthrough' => [
                    'url' => $this->url,
                    'params' => $this->params,
                    'method' => $this->method,
                ]
            ])->notifyException(
                new CakeToLaravelException("Cake => Laravel 500 error (". $this->getLastCode() .")", $this->getLastCode())
            );
        }

// error handler introduced for 422 but we don't know if 422 is not resolved as !200 so I changed the status code on the laravel side.
        if($this->getLastCode() === 425) {
            return $response;
        }

        if($this->getLastCode() === 201) {
            if($decode) {
                return json_decode($response, true);
            }
            return $response;
        }

        if($this->getLastCode() != 200){
            $this->lastResponse = $response;
            return false;
        }

        if($decode) {
            return json_decode($response, true);
        }
        return $response;

    }

    private function _getHandle($url, $method)
    {
        App::uses('SobitLogger','Lib');
        SobitLogger::getInstance()->startSub($url, $method);
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $this->baseUrl . $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
<<<<<<< HEAD
        if (substr(Router::fullBaseUrl(), -5) === '.test' || substr(Router::fullBaseUrl(), -7) === '.test/#') {
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        }
=======
>>>>>>> @{-1}
        switch ($method) {
            case "POST":
                curl_setopt($handle, CURLOPT_POST, 1);
                break;
            case "DELETE":
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            case "PUT":
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
        }

        return $handle;
    }

    private function _generateHash($url)
    {
        return hash_hmac("sha256", $url, $this->apiKey);
    }

    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    public function getLastCode()
    {
        return $this->lastCode;
    }

    private function responseMarkedAsDontReport($response)
    {
        $object = json_decode($response);
        if ($object !== null && property_exists($object, 'error_status') && $object->error_status == 'handled') {
            return true;
        }

        return false;
    }
}
