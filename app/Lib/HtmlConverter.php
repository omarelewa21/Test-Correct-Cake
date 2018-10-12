<?php

class HtmlConverter {

    const BASE_URL = 'http://utility.webbix.nl/';
    const APP = 'tripwatch';
    const API_KEY = 'mLViOPmhDw9gvtGhc3NKAzsVRUnmvDnzyUK0twVK3wGyYizhiUJEjpHFxmyO';

    public static function htmlToJpg($string, $destination = false)
    {
        $params = array(
            'app' => self::APP,
            'api_key' => self::API_KEY,
            'quality' => 100
        );
        $path = self::BASE_URL . "convert/html/jpg?" . http_build_query($params);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('html' => $string));
        $result = curl_exec($ch);

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode != 200) {
            return false;
        }

        if($destination === false){
            return $result;
        }

        if(file_put_contents($destination, $result) === false){
            return false;
        } else {
            return true;
        }
    }

    public static function htmlToPdf($string, $orientation, $destination = false)
    {
        $params = array(
            'app' => self::APP,
            'api_key' => self::API_KEY,
            'orientation' => $orientation
        );
        $path = self::BASE_URL . "convert/html/pdf?" . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('html' => $string));

        $result = curl_exec($ch);

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode != 200) {
            return false;
        }

        if($destination === false){
            return $result;
        }

        if(file_put_contents($destination, $result) === false){
            return false;
        } else {
            return true;
        }
    }
}