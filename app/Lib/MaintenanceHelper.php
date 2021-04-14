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
    protected $notificationFile = '../tmp/maintenance_notification.txt';
    protected $whitelistIpFile = '../tmp/whitelisted_ips.txt';
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

    public function doWeNeedToShowMaintenance()
    {
        return ($this->isInMaintenanceMode() && $this->isInMaintenanceModeForCurrentIp() && $this->isUrlLockedWhileInMaintenance());
    }

    public function isUrlLockedWhileInMaintenance(){
        return $_SERVER['REQUEST_URI'] !== '/deployment_maintenance/check_for_maintenance';
    }

    public function isInMaintenanceModeForCurrentIp(){
        $hasMaintenance = false;
        if($this->isInMaintenanceMode()){
            $hasMaintenance = true;
            $whitelistedIpContent = file_get_contents($this->whitelistIpFile);
            if(strlen($whitelistedIpContent) > 3){
                $whitelistedIps = unserialize($whitelistedIpContent);
            }
            if(is_array($whitelistedIps) && in_array($_SERVER['REMOTE_ADDR'],$whitelistedIps)){
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

    public function setInMaintenanceMode($message,$ips = [])
    {
        file_put_contents($this->maintenanceFile,$message);
        file_put_contents($this->whitelistIpFile,serialize($ips));
        $this->unsetNotificationForMaintenance();
    }

    public function unsetMaintenanceMode()
    {
        unlink($this->maintenanceFile);
        unlink($this->whitelistIpFile);
        $this->unsetNotificationForMaintenance();;
    }

    public function setNotificationForMaintenance($notification)
    {
        file_put_contents($this->notificationFile,$notification);
    }

    public function unsetNotificationForMaintenance()
    {
        unlink($this->notificationFile);
    }
}