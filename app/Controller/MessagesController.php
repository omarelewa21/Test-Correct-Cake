<?php

App::uses('AppController', 'Controller');
App::uses('MessagesService', 'Lib/Services');

class MessagesController extends AppController {

    public function beforeFilter()
    {
        $this->MessagesService = new MessagesService();

        parent::beforeFilter();
    }

    public function send($user_id) {
        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data;

            $this->MessagesService->send($user_id, $data);

            $this->formResponse(true, []);
            die;
        }

        $this->set('user_id', $user_id);
    }

    public function index() {

    }

    public function load() {
        $params = $this->request->data;

        $filters = array();
        parse_str($params['filters'], $filters);

        $messages = $this->MessagesService->getMessages($params);

        $this->set('messages', $messages['data']);
    }

    public function unread() {
        $this->autoRender = false;

        $user_id = AuthComponent::user('id');

        $unread = $this->MessagesService->getUnread($user_id);

        echo $unread;
    }

    public function messages() {

    }

    public function show($message_id) {
        $message = $this->MessagesService->getMessage($message_id);

        if($message['user_id'] != AuthComponent::user('id')) {
            $this->MessagesService->markRead($message_id);
        }
        $this->set('message', $message);
    }
}