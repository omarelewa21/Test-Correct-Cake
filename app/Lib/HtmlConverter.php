<?php

class HtmlConverter extends BaseService {

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new HtmlConverter();
        }
        return self::$instance;
    }

    public function htmlToPdf($string)
    {
        $response = $this->Connector->postRequest('/convert/html/pdf', [] ,[
            'html' => $string
        ], false);

        if($response === false){
            return $this->Connector->getLastResponse();
        }
        return $response;
    }
}