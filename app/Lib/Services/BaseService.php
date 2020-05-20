<?php

App::uses('CoreConnector', 'Lib');
App::uses('CoreBridge', 'Lib');

/**
 * Class BaseService
 *
 * All controllers will only use services that extend this class.
 *
 * @property CoreConnector $Connector
 * @property CoreBridge    $Bridge
 */
class BaseService {

    protected $Connector;
    protected $Bridge;

    protected $errors = [];

    public function __construct()
    {
        $this->Connector = CoreConnector::instance();
        $this->Bridge = CoreBridge::instance();
    }

    /**
     * Helper methods to stop repeating the same code. Often we just return the expected index from the JSON response.
     *
     * @param array  $response
     * @param string $expectedIndex
     *
     * @return bool
     */
    public function _returnResponse($response, $expectedIndex)
    {
        if ($response === false || !isset($response[$expectedIndex])) {
            return false;
        }

        return $response[$expectedIndex];
    }

    public function readableErrors($errors)
    {
        $messages = array();
        foreach($errors as $error){
            $messages[] = $error[0];
        }

        return implode(', ', $messages);
    }

    public function hasError(){
        return (bool) count($this->errors);
    }

    public function addError($error){
        $this->errors[] = $error;
    }
    public function addAssocError($key, $error) {
        $this->errors[$key] = $error;
    }

    public function getErrors(){
        return $this->errors;
    }

    public function clearErrors(){
        $this->errors = [];
    }

    public function isValidJson($string){
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}