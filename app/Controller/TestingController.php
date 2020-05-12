<?php

App::uses('AppController', 'Controller');
App::uses('TestingService', 'Lib/Services');

class TestingController extends AppController
{

    public function beforeFilter()
    {
        $this->TestingService = new TestingService();

        parent::beforeFilter();
    }

    public function database($flag)
    {
        if($this->request->is('post')) {
            if ($this->isAllowedFlag($flag)) {
                $result = $this->TestingService->handle($flag);
            }

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;
        }
    }

    private function isAllowedFlag($flag)
    {
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
        ];
    }
}