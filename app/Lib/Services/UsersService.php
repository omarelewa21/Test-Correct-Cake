<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class UsersService
 *
 *
 */
class UsersService extends BaseService
{

    public function getRoles()
    {
        return AuthComponent::user('roles');
    }

    public function hasRole($rolesToCheck)
    {
        if (is_string($rolesToCheck)) $rolesToCheck = [$rolesToCheck];

        $roleExists = false;
        foreach ($this->getRoles() as $role) {
            foreach ($rolesToCheck as $roleToCheck) {
                if (strtolower($roleToCheck) == strtolower($role['name'])) {
                    $roleExists = true;
                    break 2;
                }
            }
        }
        return $roleExists;
    }

    public function storeOnboardingWizardStep($data)
    {
        $response = $this->Connector->postRequest('/onboarding/registeruserstep', [], $data);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function registerNewTeacher($data)
    {
        $response = $this->Connector->postRequest('/demo_account', [], $data);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function registrationNotCompletedForRegisteredNewTeacher($userId)
    {
        $response = $this->Connector->getRequest('/demo_account/'.$userId.'/registration_completed', []);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateRegisteredNewTeacher($data, $userId)
    {
        $response = $this->Connector->putRequest('/demo_account/' . $userId, [], $data);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getRegisteredNewTeacherByUserId($userId)
    {
        $response = $this->Connector->getRequest('/demo_account/' . $userId, []);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function notifySupportTeacherInDemoSchoolTriesToUpload($userId)
    {
        $response = $this->Connector->postRequest('/demo_account/notify_support_teacher_tries_to_upload', [], ['userId' => $userId]);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateOnboardingWizard($data)
    {
        $response = $this->Connector->putRequest('/onboarding', [], $data);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getOnboardingWizard($userId)
    {
        $response = $this->Connector->getRequest(sprintf('/onboarding/%d/steps', $userId), []);
        if ($response === false) {
            $this->addError($this->Connector->getLastResponse());
            return false;
        }

        return $response;
    }

    public function getAdminTeacherStats()
    {
        $params = [];
        $response = $this->Connector->getRequest('/admin/teacher_stats', $params);

        if ($response === false) {
            $this->addError($this->Connector->getLastResponse());
            return false;
        }

        return $response;
    }

    public function getUsers($params)
    {

        $response = $this->Connector->getRequest('/user', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        if (isset($response['data'])) {// of not set we need the complete response if empty. like [], then it's okay to return an empty list// && !empty($response['data'])) {
            return $response['data'];
        } else {
            return $response;
        }
    }

    public function getSalesOrganisations()
    {

        $params['mode'] = 'list';

        $response = $this->Connector->getRequest('/sales_organization', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function resetPasswordForm($user_id, $data)
    {
        $data = [
            'password'     => $data['password'],
            'old_password' => $data['password_old']
        ];

        $response = $this->Connector->putRequest('/user/' . $user_id, [], $data);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getUserList($params, $combind = false)
    {

        $params['mode'] = 'list';

        $response = $this->Connector->getRequest('/user', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        if ($combind) {
            $results = [];

            foreach ($response as $id => $user) {
                $results[$id] = $user['name_first'] . ' ' . $user['name_suffix'] . ' ' . $user['name'];
            }
            return $results;
        } else {
            return $response;
        }
    }

    public function switch_school_location($userId, $params)
    {
        $response = $this->Connector->putRequest('/user/switch_school_location/' . $userId, [], $params);

        if ($response === false) {
            $this->addError($this->Connector->getLastResponse());

            return false;
        }

        return $response;
    }

    public function move($user_id, $params)
    {
        $response = $this->Connector->putRequest('/user/' . $user_id, [], $params);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function notifyWelcome($role_id)
    {

        $params = [
            'filter' => [
                'send_welcome_email' => 0,
                'role'               => $role_id
            ]
        ];

        $response = $this->Connector->getRequest('/user/send_welcome_email', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function sendRequest($email)
    {

        $data = [
            'username' => $email,
            'url'      => Router::fullbaseUrl() . '/users/reset_password/%s'
        ];


        $response = $this->Connector->postRequest('/send_password_reset', [], $data);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getParents($user_id)
    {
        $data = [
            'filter' => [
                'student_parent_of_id' => $user_id
            ],
            'mode'   => 'list'
        ];

        $response = $this->Connector->getRequest('/user', $data);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function resetPassword($token, $data)
    {
        $data = [
            'username' => $data['email'],
            'token'    => $token,
            'password' => $data['password']
        ];

        $response = $this->Connector->postRequest('/password_reset', [], $data);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $this->Connector->getLastCode();
    }

    public function addUserWithTellATeacher($type, $data) {
        switch ($type) {
            case 'managers':
                $data['user_roles'] = [6];
                break;

            case 'accountmanagers':
                $data['user_roles'] = [5];
                break;
        }

        $response = $this->Connector->postRequest('/tell_a_teacher', [], $data);

        if ($response === false) {
            if ($this->Connector->getLastCode() == 422) {
                $response = $this->Connector->getLastResponse();

                if (strstr($response, 'external_id')) {
                    return 'external_code';
                } elseif (strstr($response, 'username')) {
                    return 'username';
                } else if (strstr($response, 'user_roles')) {
                    return 'user_roles';
                } else if (strstr($response, 'demo')) {
                    return 'demo';
                }
            }
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function addUser($type, $data)
    {

        switch ($type) {
            case 'managers':
                $data['user_roles'] = [6];
                break;

            case 'accountmanagers':
                $data['user_roles'] = [5];
                break;
        }

        $response = $this->Connector->postRequest('/user', [], $data);

        if ($response === false) {
            if ($this->Connector->getLastCode() == 422) {
                $response = $this->Connector->getLastResponse();

                if (strstr($response, 'external_id')) {
                    return 'external_code';
                } elseif (strstr($response, 'username')) {
                    return 'username';
                } else if (strstr($response, 'user_roles')) {
                    return 'user_roles';
                } else if (strstr($response, 'demo')) {
                    return 'demo';
                }
            }
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updatePasswordForUser($user_id, $data)
    {

        $response = $this->Connector->putRequest('/user/update_password_for_user/' . $user_id, [], $data);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateUser($user_id, $data)
    {

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $response = $this->Connector->putRequest('/user/' . $user_id, [], $data);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateProfilePicture($user_id, $file)
    {
        $data = [
            'profile_image' => new CURLFile($file)
        ];

        return $this->Connector->putRequestFile('/user/' . $user_id, [], $data);
    }

    public function getProfilePicture($user_id)
    {
        $response = $this->Connector->getDownloadRequest('/user/' . $user_id . '/profile_image', []);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteUser($user_id)
    {
        $response = $this->Connector->deleteRequest('/user/' . $user_id, []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getUser($user_id, $params = [])
    {
        $response = $this->Connector->getRequest('/user/' . $user_id, $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function doImport($data)
    {
        $response = $this->Connector->postRequest('/school_location/import/', [], $data);

        if ($response === false) {
            $error = $this->Connector->getLastResponse();
            if ($this->isValidJson($error)) {
                $err = json_decode($error);
                foreach ($err as $k => $e) {
                    if (is_array($e)) {
                        foreach ($e as $a) {
                            $this->addError($a);
                        }
                    } else {
                        $this->addError($e);
                    }
                }
            } else {
                $this->addError($response);
            }

            return false;
        }

        return $response;
    }

    public function createOnboardingWizardReport($data)
    {
        $response = $this->Connector->postRequest('/onboarding_wizard_report', [], $data);
        if ($response) {
            return $this->Connector->getDownloadRequest('/onboarding_wizard_report', [], $data);
        }
    }


    public function doImportTeacher($data)
    {
        $response = $this->Connector->postRequest('/teacher/import/schoollocation', [], $data);


        if ($response === false) {
            $error = $this->Connector->getLastResponse();
            if ($this->isValidJson($error)) {
                $err = json_decode($error);

                foreach ($err->errors as $k => $e) {
                    if (is_array($e)) {
                        foreach ($e as $b => $a) {
                            $this->addAssocError($k, $a);
                        }
                    } else {
                        $this->addAssocError($k, $e);
                    }
                }
            } else {
                $this->addError($response);
            }

            return false;
        }

        return $response;
    }

    public function registerEduIx($ean, $edurouteSessieID, $signature)
    {
        $response = $this->Connector->getRequest(
            sprintf('/edu-ix/%s/%s/%s/', $ean, $edurouteSessieID, $signature),
            []
        );

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function addUserEduIx($ean, $edurouteSessieID, $signature, $data)
    {
        $response = $this->Connector->postRequest(
            sprintf('/edu-ix/%s/%s/%s', $ean, $edurouteSessieID, $signature),
            [],
            $data
        );



        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

}

