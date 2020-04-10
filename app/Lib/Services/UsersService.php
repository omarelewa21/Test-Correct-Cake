<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class UsersService
 *
 *
 */
class UsersService extends BaseService {

    public function getRoles(){
        return AuthComponent::user('roles');
    }

    public function hasRole($rolesToCheck){
        if(is_string($rolesToCheck)) $rolesToCheck = [$rolesToCheck];

        $roleExists = false;
        foreach($this->getRoles() as $role){
            foreach($rolesToCheck as $roleToCheck){
                if(strtolower($roleToCheck) == strtolower($role['name'])){
                    $roleExists = true;
                    break 2;
                }
            }
        }
        return $roleExists;
    }

    public function getAdminTeacherStats(){
        $params = [];
        $response = $this->Connector->getRequest('/admin/teacher_stats', $params);

        if($response === false){
            $this->addError($this->Connector->getLastResponse());
            return false;
        }

        return $response;
    }

   public function getUsers($params) {

        $response = $this->Connector->getRequest('/user', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

       if(isset($response['data'])){// of not set we need the complete response if empty. like [], then it's okay to return an empty list// && !empty($response['data'])) {
           return $response['data'];
       }else{
           return $response;
       }
    }

    public function getSalesOrganisations() {

        $params['mode'] = 'list';

        $response = $this->Connector->getRequest('/sales_organization', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function resetPasswordForm($user_id, $data) {
        $data = [
            'password' => $data['password'],
            'old_password' => $data['password_old']
        ];

        $response = $this->Connector->putRequest('/user/' . $user_id, [], $data);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getUserList($params, $combind = false) {

        $params['mode'] = 'list';

        $response = $this->Connector->getRequest('/user', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        if($combind) {
            $results = [];

            foreach($response as $id => $user) {
                $results[$id] = $user['name_first'] . ' ' . $user['name_suffix'] . ' ' . $user['name'];
            }
            return $results;
        }else{
            return $response;
        }
    }

    public function move($user_id, $params) {
        $response = $this->Connector->putRequest('/user/' . $user_id, [], $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function notifyWelcome($role_id) {

        $params = [
            'filter' => [
                'send_welcome_email' => 0,
                'role' => $role_id
            ]
        ];

        $response = $this->Connector->getRequest('/user/send_welcome_email', $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function sendRequest($email) {

        $data = [
            'username' => $email,
            'url' => Router::fullbaseUrl() . '/users/reset_password/%s'
        ];


        $response = $this->Connector->postRequest('/send_password_reset', [], $data);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getParents($user_id) {
        $data = [
            'filter' => [
                'student_parent_of_id' => $user_id
            ],
            'mode' => 'list'
        ];

        $response = $this->Connector->getRequest('/user', $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function resetPassword($token, $data) {
        $data = [
            'username' => $data['email'],
            'token' => $token,
            'password' => $data['password']
        ];

        $response = $this->Connector->postRequest('/password_reset', [], $data);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $this->Connector->getLastCode();
    }

    public function addUser($type, $data) {

        switch($type) {
            case 'managers':
                $data['user_roles'] = [6];
                break;

            case 'accountmanagers':
                $data['user_roles'] = [5];
                break;
        }

        $response = $this->Connector->postRequest('/user', [], $data);

        if($response === false){
            if($this->Connector->getLastCode() == 422) {
                $response = $this->Connector->getLastResponse();

                if(strstr($response, 'external_id')) {
                    return 'external_code';
                }elseif(strstr($response, 'username')) {
                    return 'username';
                }
            }
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updatePasswordForUser($user_id, $data) {

        $response = $this->Connector->putRequest('/user/update_password_for_user/' . $user_id, [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateUser($user_id, $data) {

        if(empty($data['password'])) {
            unset($data['password']);
        }

        $response = $this->Connector->putRequest('/user/' . $user_id, [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateProfilePicture($user_id, $file) {
        $data = [
            'profile_image' => new CURLFile($file)
        ];

        return $this->Connector->putRequestFile('/user/' . $user_id, [], $data);
    }

    public function getProfilePicture($user_id) {
        $response = $this->Connector->getDownloadRequest('/user/' . $user_id . '/profile_image', []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteUser($user_id) {
        $response = $this->Connector->deleteRequest('/user/' . $user_id, []);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getUser($user_id, $params = []) {
        $response = $this->Connector->getRequest('/user/' . $user_id, $params);
        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }
}