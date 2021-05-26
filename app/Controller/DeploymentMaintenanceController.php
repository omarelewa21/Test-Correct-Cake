<?php

App::uses('AppController', 'Controller');
App::uses('DeploymentMaintenanceService', 'Lib/Services');

/**
 * Demo controller
 *
 * @property DemoService $DemoService
 */
class DeploymentMaintenanceController extends AppController {

    /**
     * This is called before each action is parsed.
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->Auth->allowedActions = array('check_for_maintenance');
        $this->DeploymentMaintenanceService = new DeploymentMaintenanceService();

        parent::beforeFilter();
    }

    /**
     * This page shows an overview of all units in the database.
     *
     * @return void
     */
    public function check_for_maintenance()
    {
        App::uses('MaintenanceHelper','Lib');
        $helper = MaintenanceHelper::getInstance();
        $data = $this->DeploymentMaintenanceService->checkForDeploymentMaintenance();
        $helper->unsetNotificationForMaintenance();
        $helper->unsetMaintenanceMode();
        if($data){
            if($data['status'] === 'ACTIVE'){
                $helper->setInMaintenanceMode($data['message'], $data['whitelisted_ips']);
            } else if ($data['status'] === 'NOTIFY'){
                $helper->setNotificationForMaintenance($data['notification']);
            }
        }
        die();
    }
}