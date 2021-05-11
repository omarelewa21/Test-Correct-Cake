<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class DemoService
 *
 *
 */
class DeploymentMaintenanceService extends BaseService {

    public function checkForDeploymentMaintenance()
    {
        return $this->Connector->getRequest('/check_for_deployment_maintenance',[]);
    }

}