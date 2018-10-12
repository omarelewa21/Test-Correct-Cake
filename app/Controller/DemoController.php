<?php

App::uses('AppController', 'Controller');
App::uses('DemoService', 'Lib/Services');

/**
 * Demo controller
 *
 * @property DemoService $DemoService
 */
class DemoController extends AppController {

    /**
     * This is called before each action is parsed.
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->DemoService = new DemoService();

        parent::beforeFilter();
    }

    /**
     * This page shows an overview of all units in the database.
     *
     * @return void
     */
    public function index()
    {
        debug($this->DemoService->getTests());
        die;
    }
}