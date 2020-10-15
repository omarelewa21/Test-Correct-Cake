<?php
/**
 * Created by PhpStorm.
 * User: erik
 * Date: 30/07/2019
 * Time: 14:23
 */

class MaintenanceHelper
{

    protected $maintenanceFile = '../tmp/maintenance.txt';
    protected $_isInMaintenanceMode = null;

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
            self::$instance = new MaintenanceHelper();
        }

        return self::$instance;
    }


    public function isInMaintenanceModeForCurrentIp(){
        $hasMaintenance = false;
        if($this->isInMaintenanceMode()){
            $hasMaintenance = true;
            $whitelistIps = Configure::read('maintenance-whitelist-ips');
            if(is_array($whitelistIps) && in_array($_SERVER['REMOTE_ADDR'],$whitelistIps)){
                $hasMaintenance = false;
            }
        }

        return $hasMaintenance;
    }

    public function getMaintenanceMessage(){
        $maintenanceMessage = '';
        if($this->isInMaintenanceMode()){
            $maintenanceMessage = file_get_contents($this->maintenanceFile);
        }
        return $maintenanceMessage;
    }

    public function isInMaintenanceMode(){
        if($this->_isInMaintenanceMode === null){
            $this->_isInMaintenanceMode = (bool) file_exists($this->maintenanceFile);
        }
        return $this->_isInMaintenanceMode;
    }


}