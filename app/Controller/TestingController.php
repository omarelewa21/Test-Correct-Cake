<?php

App::uses('AppController', 'Controller');
App::uses('TestingService', 'Lib/Services');

class TestingController extends AppController
{
    protected $token = '0cd66712-d5b2-4fdd-9c15-872d4b0cb7b0';

    public function beforeFilter()
    {
        $this->Auth->allowedActions = array('selenium_state');
        $this->TestingService = new TestingService();

        parent::beforeFilter();
    }

    public function database($flag)
    {
        if($this->request->is('post')) {
            if ($this->isAllowed($flag)) {
                $result = $this->TestingService->handle($flag);
            }

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;
        }
    }

    protected function checkForValidToken()
    {
        if(!$this->request->header('token') || $this->request->header('token') !== $this->token){
            die();
        }
    }
    private function isAllowed($flag)
    {
        $this->checkForValidToken();

        return (in_array($flag, $this->allowedFlags()));
    }

    private function allowedFlags()
    {
        return [
            'create-test-start',
            'create-test-create-questions',
            'create-test-duplicate-test',
            'create-test-delete-test',
            'make-test-start',
            'make-test-plan-test',
            'make-test-start-test',
            'make-test-take-test',
            'make-test-close-test',
            'discuss-test-start',
            'discuss-test-start-discussion',
            'discuss-test-discuss-test',
            'discuss-test-close-test',
            'rate-test-start',
            'rate-test-rate-per-question',
            'rate-test-rate-per-student',
            'rate-test-normalize-rating',
            'rate-test-enable-glance',
            'glance-test-start'
        ];
    }

    public function selenium_toggle($flag)
    {
        $this->checkForValidToken();
        if(!$this->request->is('post')) {
            die;
        }

        $result = $this->TestingService->seleniumToggle($flag);

        $this->formResponse(
            $result ? true : false,
            []
        );
    }

    public function selenium_state()
    {
        $this->checkForValidToken();
        if(!$this->request->is('get')) {
            die;
        }

        $result = $this->TestingService->seleniumState();

        $this->formResponse(
            $result ? true : false,
            []
        );
    }
}