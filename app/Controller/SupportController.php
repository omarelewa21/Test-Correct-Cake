<?php

App::uses('AppController', 'Controller');
App::uses('UsersService', 'Lib/Services');
App::uses('TestsService', 'Lib/Services');
App::uses('SchoolClassesService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('TestTakesService', 'Lib/Services');
App::uses('MessagesService', 'Lib/Services');
App::uses('SupportService', 'Lib/Services');

class SupportController extends AppController
{
    public function beforeFilter()
    {
        $this->UsersService = new UsersService();
        $this->SupportService = new SupportService();

        parent::beforeFilter();
    }

    public function index() {
        $this->isAuthorizedAs(['Administrator']);
    }

    public function load()
    {
        $this->isAuthorizedAs(['Administrator']);

        $params = $this->handleRequestOrderParameters($this->request->data);
        $logs = $this->SupportService->getTakeOverLogs($params);

        $this->set('logs', $logs['data']);
    }

    public function take_over_user_confirmation($userUuid)
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $result = $this->UsersService->verifyPasswordForUser(getUUID(AuthComponent::user(), 'get'), $this->request->data['User']);

            if (empty($result) || $result === 'refused') {
                $this->formResponse(false, ['Authentification failed.']);
                exit();
            }

            $requestedUser = $this->UsersService->getUser($userUuid, ['with' => ['sessionHash']]);

            if (!$requestedUser) {
                $this->formResponse(false, ['Requested user unavailable.']);
                exit();
            }

            if(!$this->SupportService->registerTakeOverForUser($userUuid, ['ip' => $_SERVER['REMOTE_ADDR']])) {
                $this->formResponse(false, ['Something went wrong with logging the current action.']);
                exit();
            }

            if(!$this->Auth->login($requestedUser)) {
                $this->formResponse(false, ['Could not login with the selected user.']);
                exit();
            }

            $supportUsername = sprintf(
                '%s%s %s',
                $result['name_first'],
                !empty($result['name_suffix']) ? ' ' . $result['name_suffix'] : '',
                $result['name']
            );

            CakeSession::write('Support', ['name' => $supportUsername, 'id' => getUUID($result, 'get')]);
            $this->setUserLanguage();
            $this->formResponse(true);
        }
    }

    public function return_as_support_user($userUuid)
    {
        $this->autoRender = false;
        $requestedUser = $this->UsersService->getUser($userUuid, ['with' => ['sessionHash']]);

        if ($requestedUser) {
            CakeSession::destroy();
            $this->Auth->login($requestedUser);
            return true;
        }

        return false;
    }
}