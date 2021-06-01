<?php

App::uses('AppController', 'Controller');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('SchoolsService', 'Lib/Services');
App::uses('ContactsService', 'Lib/Services');
App::uses('UmbrellaOrganisationsService', 'Lib/Services');
App::uses('UsersService', 'Lib/Services');
App::uses('TestsService', 'Lib/Services');

/**
 * SchoolLocations controller
 *
 */
class SchoolLocationsController extends AppController
{
    public function beforeFilter()
    {
        $this->SchoolLocationsService = new SchoolLocationsService();
        $this->SchoolsService = new SchoolsService();
        $this->ContactsService = new ContactsService();
        $this->UmbrellaOrganisationsService = new UmbrellaOrganisationsService();
        $this->UsersService = new UsersService();
        $this->TestsService = new TestsService();
        parent::beforeFilter();
    }

    public function change_allow_inbrowser_testing($locationId, $allow) {
        $this->isAuthorizedAs(['Administrator','Account manager']);
        if($this->request->is('post') || $this->request->is('put')) {
            $this->SchoolLocationsService->change_allow_inbrowser_testing($locationId, $allow);
        }
        $this->formResponse(
            true,
            []
        );
    }
    public function change_allow_new_player_access($locationId, $allow) {
        $this->isAuthorizedAs(['Administrator','Account manager']);
        if($this->request->is('post') || $this->request->is('put')) {
            $this->SchoolLocationsService->change_allow_new_player_access($locationId, $allow);
        }
        $this->formResponse(
            true,
            []
        );
    }

    public function index() {
        $this->isAuthorizedAs(['Administrator', 'Account manager']);

        $this->set('isAdministrator', $this->hasRole('Administrator'));
    }

    public function view($id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager']);

        $school_location = $this->SchoolLocationsService->getSchoolLocation($id);

        $params['filter'] = [
            'school_location_id' => $school_location['id'],
            'role' => [6]
        ];

        $this->set('managers', $this->UsersService->getUsers($params));
        $this->set('school_location', $school_location);
        $this->set('ips', $this->SchoolLocationsService->getIps(getUUID($school_location, 'get')));
        $this->set('isAdministrator', $this->hasRole('Administrator'));
    }

    public function load() {
        $this->isAuthorizedAs(['Administrator', 'Account manager']);

        $params = $this->request->data;
        $schools = $this->SchoolLocationsService->getSchoolLocations($params);

        $this->set('school_locations', $schools);
        $this->set('isAdministrator', $this->hasRole('Administrator'));
    }

    public function add_ip($location_id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager']);

        if($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data['Ip'];

            $result = $this->SchoolLocationsService->addIp($location_id, $data);

            $this->formResponse(
                $result ? true : false,
                []
            );
        }

        $this->set('location_id', $location_id);
    }

    public function delete_ip($location_id, $ip_id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager']);

        if($this->request->is('delete')) {
            $result = $this->SchoolLocationsService->deleteIp($location_id, $ip_id);

            $this->formResponse(
                $result ? true : false,
                []
            );
        }
    }

    public function edit($school_id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager']);

        if($this->request->is('post') || $this->request->is('put')) {

            $errors = array();
            $data = $this->request->data['SchoolLocation'];

            if($data['school_id'] != 0) {
                $school = $this->SchoolsService->getSchool($data['school_id']);
//                $data['external_main_code'] = $school['external_main_code'];
            }

            if(!empty($data['external_main_code'])){
                $toIgnore = array();
                $toIgnore[$school_id] = $data['external_main_code'].$data['external_sub_code'];
                $schoolLocationList = $this->SchoolLocationsService->getSchoolLocationListWithUUID();

                if (strlen($data['external_sub_code']) > 2) {
                    $this->formResponse(false, ['De Locatie BRIN code mag maximaal 2 karakters zijn']);exit();
                }
                if (strlen($data['external_main_code']) > 0 && strlen($data['external_main_code']) < 4) {
                    $this->formResponse(false, ['De BRIN code moet uit 4 karakters bestaan']);exit();
                }

                foreach ($schoolLocationList as $id => $schoolLocationInList) {
                    if(in_array($id, array_keys($toIgnore))) continue;
                    foreach ($toIgnore as $matchAgainst) {
                        $schoolLocationListItem = $this->SchoolLocationsService->getSchoolLocation(getUUID($schoolLocationInList, 'get'));
                        if($matchAgainst == ($schoolLocationListItem['external_main_code'].$schoolLocationListItem['external_sub_code'])) {
                            $this->formResponse(
                                false,
                                ['Combinatie brin/locatie code bestaat reeds op andere school']
                            ); exit();
                        }
                    }
                }
            }

            if($data['activated'] != 1) {
                $data['activated'] = 0;
            }

            $result = $this->SchoolLocationsService->updateSchoolLocation($school_id, $data);

            if(!$result) {
                $errors = $this->SchoolLocationsService->getErrors();
                if(count($errors) < 1) {
                    $errors[] = 'School kon niet worden aangepast';
                }
            }

            $this->formResponse(
                $result ? true : false,
                $errors
            );

            die;
        }

        $school_location = $this->SchoolLocationsService->getSchoolLocation($school_id);

        $levels = [];

        foreach($school_location['education_levels'] as $level) {
            $levels[] = $level['id'];
        }

        $school_location['education_levels'] = $levels;

        $schools[''] = 'Geen';
        $schools += $this->SchoolsService->getSchoolList();

        $this->set('schools', $schools);

        $this->request->data['SchoolLocation'] = $school_location;

        $params['filter'] = ['role' => [5]];
        $users = $this->UsersService->getUsers($params);

        $accountmanagers = [];

        foreach($users as $user) {
            $accountmanagers[getUUID($user, 'get')] = $user['name_first'] . ' ' . $user['name_suffix'] . ' ' . $user['name'];
        }

        $this->set('eduction_levels', $this->TestsService->getEducationLevels(true, false));
        $this->set('grading_scales', $this->SchoolLocationsService->getGradingScales());
        $this->set('accountmanagers', $accountmanagers);
        $this->set('school_location', $school_location);

    }

    public function delete_licence($location_id, $licence_id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager']);

        if($this->request->is('delete')) {
            $this->autoRender = false;
            $this->SchoolLocationsService->deleteLicence($location_id, $licence_id);
        }
    }

    public function edit_licence($location_id, $licence_id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager']);

        if($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data;

            $data['start'] = date('Y-m-d', strtotime($data['Licence']['start']));
            $data['end'] = date('Y-m-d', strtotime($data['Licence']['end']));
            $data['amount'] = $data['Licence']['amount'];

            $result = $this->SchoolLocationsService->updateLicence($location_id, $licence_id, $data);

            $this->formResponse(
                $result ? true : false,
                []
            );
            die;
        }else{
            $data['Licence'] = $this->SchoolLocationsService->getLicence($location_id, $licence_id);

            $data['Licence']['start'] = date('d-m-Y', strtotime($data['Licence']['start']));
            $data['Licence']['end'] = date('d-m-Y', strtotime($data['Licence']['end']));

            $this->request->data = $data;
        }
    }

    public function add_licence($location_id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager']);

        if($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data;

            if(!is_numeric($data['Licence']['amount'])) {
                $this->formResponse(false, ['errors' => 'Aantal niet numeriek']);
                die;
            }

            $data['school_location_id'] = $location_id;
            $data['start'] = date('Y-m-d', strtotime($data['Licence']['start']));
            $data['end'] = date('Y-m-d', strtotime($data['Licence']['end']));
            $data['amount'] = $data['Licence']['amount'];

            $result = $this->SchoolLocationsService->addLicence($data, $location_id);

            $this->formResponse(
                $result ? true : false,
                []
            );
            die;
        }
    }

    public function delete($id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager']);

        if($this->request->is('delete')) {
            $result = $this->SchoolLocationsService->deleteSchoolLocation($id);

            $this->formResponse(
                $result ? true : false,
                []
            );
        }
    }

    public function add() {
        $this->isAuthorizedAs(['Administrator', 'Account manager']);

        if($this->request->is('post') || $this->request->is('put')) {

            $errors = array();
            $data = $this->request->data['SchoolLocation'];

            if(!empty($data['external_main_code']) && !empty($data['external_sub_code'])){
                $toIgnore = array();
                $toIgnore[0] = $data['external_main_code'].$data['external_sub_code'];
                $schoolLocationList = $this->SchoolLocationsService->getSchoolLocationListWithUUID();

                foreach ($schoolLocationList as $id => $schoolLocationInList) {
                    // if(in_array($id, array_keys($toIgnore))) continue;
                    foreach ($toIgnore as $matchAgainst) {
                        $schoolLocationListItem = $this->SchoolLocationsService->getSchoolLocation(getUUID($schoolLocationInList, 'get'));
                        if($matchAgainst == ($schoolLocationListItem['external_main_code'].$schoolLocationListItem['external_sub_code'])) {
                            $this->formResponse(
                                false,
                                ['Combinatie brin/locatie code bestaat reeds op andere school']
                            ); exit();
                        }
                    }
                }
            }

            if($data['activated'] != 1) {
                $data['activated'] = 0;
            }

            if($data['school_id'] != 0) {
                $school = $this->SchoolsService->getSchool($data['school_id']);
                $data['external_main_code'] = $school['external_main_code'];
            }

            if(!empty($data['external_main_code'])){
                $toIgnore = $data['external_main_code'].$data['external_sub_code'];
                $schoolLocationList = $this->SchoolLocationsService->getSchoolLocationListWithUUID();

                foreach ($schoolLocationList as $id => $schoolLocationInList) {
                    $schoolLocationListItem = $this->SchoolLocationsService->getSchoolLocation(getUUID($schoolLocationInList, 'get'));
                    if($toIgnore == ($schoolLocationListItem['external_main_code'].$schoolLocationListItem['external_sub_code'])) {
                        $this->formResponse(
                            false,
                            ['Combinatie brin/locatie code bestaat reeds op andere school']
                        ); exit();
                    }
                }
            }

            $result = $this->SchoolLocationsService->addSchoolLocation($data);

            if(!$result){
                $errors = $this->SchoolLocationsService->getErrors();
                if(count($errors) < 1) {
                    $errors[] = 'School kon niet worden aangemaakt';
                }
            }

            $this->formResponse(
                $result ? true : false,
                $errors
            );
        }

        $schools[''] = 'Geen';
        $schools += $this->SchoolsService->getSchoolList();

        $params['filter'] = ['role' => [5]];
        $users = $this->UsersService->getUsers($params);

        $accountmanagers = [];

        foreach($users as $user) {
            $accountmanagers[getUUID($user, 'get')] = $user['name_first'] . ' ' . $user['name_suffix'] . ' ' . $user['name'];
        }

        $this->set('accountmanagers', $accountmanagers);
        $this->set('eduction_levels', $this->TestsService->getEducationLevels(true, false));
        $this->set('grading_scales', $this->SchoolLocationsService->getGradingScales());
        $this->set('schools', $schools);

    }
}