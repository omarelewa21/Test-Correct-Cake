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
}