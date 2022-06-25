<?php
/**
 * Created by PhpStorm.
 * User: erik
 * Date: 30/07/2019
 * Time: 14:23
 */

class HelperFunctions
{

    // Hold the class instance.
    private static $instance = null;

    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct()
    {

    }

    // The object is created from within the class itself
    // only if the class has no instance.
    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function getCorrectUrlsInString($string)
    {
        if (strpos($_SERVER['HTTP_HOST'], 'portal2.test')){
            $string = str_replace('portal.test','portal2.test',$string);
        }
        return $string;
    }


    public function revertSpecialChars($var, $revertKeysIfArray = false)
    {
        $return = null;
        if(is_array($var)){
            $return = [];
            foreach($var as $key => $val) {
                if(is_array($val)){
                    $val = $this->revertSpecialChars($val,$revertKeysIfArray);
                } else {
                    if ($revertKeysIfArray) {
                        $return[$this->_revertSpecialChars($key)] = $this->_revertSpecialChars($val);
                    } else {
                        $return[$key] = $this->_revertSpecialChars($val);
                    }
                }
            }
        } else {
            $return = $this->_revertSpecialChars($var);
        }
        return $return;
    }

    protected function _revertSpecialChars($var)
    {
        return html_entity_decode($var);
    }

    public function getMaxFileUploadSize()
    {
        return $this->return_bytes(ini_get('upload_max_filesize'));
    }

    public function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last)
        {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
    function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
}