<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class TestsService
 *
 *
 */
class MessagesService extends BaseService {
    public function send($user_id, $data) {
        $userservice = new UsersService;
        $response = $this->Connector->postRequest('/message', [] ,[
            'message' => $data['Message']['message'],
            'subject' => $data['Message']['subject'],
            'to' => [$userservice->getUser($user_id)['id']]
        ]);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getKinds() {
        $response = $this->Connector->getRequest('/test_kind/list', []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getUnread($user_id) {

        $params['mode'] = 'count';
        $params['filter'] = [
            'receiver_id' => [$user_id],
            'unread_receiver_id' => $user_id
        ];

        $response = $this->Connector->getRequest('/message', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getMessages($params) {

        $params['order'] = [
            'created_at' => 'desc'
        ];

        $response = $this->Connector->getRequest('/message', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getMessage($message_id) {

        $response = $this->Connector->getRequest('/message/' . $message_id, []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function markRead($message_id) {
        $response = $this->Connector->putRequest('/message/mark_read/' . $message_id, [], []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }
}