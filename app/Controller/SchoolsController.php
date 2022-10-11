<?php

App::uses('AppController', 'Controller');
App::uses('SchoolsService', 'Lib/Services');
App::uses('ContactsService', 'Lib/Services');
App::uses('UmbrellaOrganisationsService', 'Lib/Services');
App::uses('UsersService', 'Lib/Services');
App::uses('SchoolLocationsService','Lib/Services');
/**
 * Schools controller
 *
 */
class SchoolsController extends AppController
{
    public function beforeFilter()
    {
        $this->SchoolsService = new SchoolsService();
        $this->ContactsService = new ContactsService();
        $this->UmbrellaOrganisationsService = new UmbrellaOrganisationsService();
        $this->UsersService = new UsersService();
        $this->SchoolLocationsService = new SchoolLocationsService();
        parent::beforeFilter();
    }


    public function index() {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $this->set('isAdministrator', $this->hasRole('Administrator'));
    }

    public function view($id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $school = $this->SchoolsService->getSchool($id);
        $params['filter'] = [
            'school_id' => $school['id'],
            'role' => [6]
        ];

        $this->set('managers', $this->UsersService->getUsers($params));
        $this->set('school', $school);
        $this->set('isAdministrator', $this->hasRole('Administrator'));
        $route_prefix = $this->hasRole('Administrator') ? 'admin/' : 'account-manager/';
        $this->set('return_route', $route_prefix . 'schools/');
    }

    public function load() {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $params = $this->request->data;
        $schools = $this->SchoolsService->getSchools($params);

        $this->set('schools', $schools);
        $this->set('isAdministrator', $this->hasRole('Administrator'));
    }

    public function edit($school_id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if($this->request->is('post') || $this->request->is('put')) {

            $errors = array();

            $data = $this->request->data['School'];

            if($data['umbrella_organization_id'] != 0) {
                $organisation = $this->UmbrellaOrganisationsService->getOrganisation($data['umbrella_organization_id'], 'get');
                $data['external_main_code'] = $organisation['external_main_code'];
            }

            if(!empty($data['external_main_code'])) {
                $toIgnore = array();

                $schoolFetch = $this->SchoolsService->getSchool($school_id);
                foreach($schoolFetch['school_locations'] as $schoollocation) {
                    $toIgnore[getUUID($schoollocation, 'get')] = $data['external_main_code'].$schoollocation['external_sub_code'];
                }

                $schoolLocationList = $this->SchoolLocationsService->getSchoolLocationListWithUUID();

                foreach ($schoolLocationList as $id => $schoolLocationInList) {
                    if(in_array(getUUID($schoolLocationInList, 'get'), array_keys($toIgnore))) continue;
                    foreach ($toIgnore as $matchAgainst) {
                        $schoolLocationListItem = $this->SchoolLocationsService->getSchoolLocation(getUUID($schoolLocationInList, 'get'));
                        if($matchAgainst == ($schoolLocationListItem['external_main_code'].$schoolLocationListItem['external_sub_code'])) {
                            $this->formResponse(
                                false,
                                [__("Combinatie brin/locatie code bestaat reeds op andere school")]
                            ); exit();
                        }
                    }
                }

                $school = $this->SchoolsService->getSchool($school_id);
                foreach($school['school_locations'] as $schoollocation) {
                    $schoollocation['external_main_code'] = $data['external_main_code'];
                    $this->SchoolLocationsService->updateSchoolLocation(getUUID($schoollocation, 'get'), $schoollocation);
                }
            }

            $result = $this->SchoolsService->updateSchool($school_id, $data);

            if(!$result) {
                $errors[] = __("School kon niet worden aangemaakt");
            }

            $this->formResponse(
                $result ? true : false,
                $errors
            );

            die;
        }

        $school = $this->SchoolsService->getSchool($school_id);
        $school['School'] = $school;

        $organisations[0] = 'Geen';
        $organisations += $this->UmbrellaOrganisationsService->getOrganisationList();

        $params['filter'] = ['role' => [5]];
        $users = $this->UsersService->getUsers($params);

        $accountmanagers = [];

        foreach($users as $user) {
            $accountmanagers[getUUID($user, 'get')] = $user['name_first'] . ' ' . $user['name_suffix'] . ' ' . $user['name'];
        }

        $this->set('accountmanagers', $accountmanagers);
        $this->set('organisations', $organisations);
        $this->set('school', $school);
        $this->request->data = $school;
    }

    public function delete($id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('delete')) {
            $result = $this->SchoolsService->deleteSchool($id);

            $this->formResponse(
                $result ? true : false,
                []
            );
        }
    }

    public function add() {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['School'];

            if($data['umbrella_organization_id'] != 0) {
                $organisation = $this->UmbrellaOrganisationsService->getOrganisation($data['umbrella_organization_id']);
                $data['external_main_code'] = $organisation['external_main_code'];
            }

            $result = $this->SchoolsService->addSchool($data);

            $this->formResponse(
                $result ? true : false,
                []
            );
        }

        $organisations[0] = 'Geen';
        $organisations += $this->UmbrellaOrganisationsService->getOrganisationList();

        $params['filter'] = ['role' => [5]];
        $users = $this->UsersService->getUsers($params);

        $accountmanagers = [];

        foreach($users as $user) {
            $accountmanagers[getUUID($user, 'get')] = $user['name_first'] . ' ' . $user['name_suffix'] . ' ' . $user['name'];
        }

        $this->set('accountmanagers', $accountmanagers);
        $this->set('organisations', $organisations);

        $route_prefix = $this->hasRole('Administrator') ? 'admin/' : 'account-manager/';
        $this->set('return_route', $route_prefix . 'schools/');
    }
}