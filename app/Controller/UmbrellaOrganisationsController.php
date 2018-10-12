<?php

App::uses('AppController', 'Controller');
App::uses('UmbrellaOrganisationsService', 'Lib/Services');
App::uses('ContactsService', 'Lib/Services');
App::uses('UsersService', 'Lib/Services');
App::uses('SchoolsService','Lib/Services');
App::uses('SchoolLocationsService','Lib/Services');

/**
 * UmbrellaOrganisations controller
 *
 */
class UmbrellaOrganisationsController extends AppController
{

    /**
     * Called before each action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->UmbrellaOrganisationsService = new UmbrellaOrganisationsService();
        $this->ContactsService = new ContactsService();
        $this->UsersService = new UsersService();
        $this->SchoolsService = new SchoolsService();
        $this->SchoolLocationsService = new SchoolLocationsService();

        parent::beforeFilter();
    }


    public function index() {
        $this->set('isAdministrator', $this->hasRole('Administrator'));
    }

    public function view($id) {
        $organisation = $this->UmbrellaOrganisationsService->getOrganisation($id);

        $this->set('organisation', $organisation);
        $this->set('isAdministrator', $this->hasRole('Administrator'));
    }

    public function load() {
        $params = $this->request->data;
        $organisations = $this->UmbrellaOrganisationsService->getOrganisations($params);

        $this->set('organisations', $organisations);
        $this->set('isAdministrator', $this->hasRole('Administrator'));
    }

    public function edit($organisation_id) {

        if($this->request->is('post') || $this->request->is('put')) {

            $errors = array();
            $organisation = $this->UmbrellaOrganisationsService->getOrganisation($organisation_id);
            $data = $this->request->data['UmbrellaOrganisation'];
            
            if(!empty($data['external_main_code'])){

                $toIgnore = array();
                foreach ($organisation['schools'] as $school) {
                    $schoolFetch = $this->SchoolsService->getSchool($school['id']);
                    foreach($schoolFetch['school_locations'] as $schoollocation) {
                        $toIgnore[$schoollocation['id']] = $schoollocation['external_main_code'].$schoollocation['external_sub_code'];
                    }
                }

                $schoolLocationList = $this->SchoolLocationsService->getSchoolLocationList();

                foreach ($schoolLocationList as $id => $name) {
                    if(in_array($id, array_keys($toIgnore))) continue;
                    foreach ($toIgnore as $matchAgainst) {
                        $schoolLocationListItem = $this->SchoolLocationsService->getSchoolLocation($id);
                        if($matchAgainst == ($schoolLocationListItem['external_main_code'].$schoolLocationListItem['external_sub_code'])) {
                            $this->formResponse(
                                false,
                                ['Combinatie brin/locatie code bestaat reeds op andere school']
                            ); exit();
                        }
                    }
                }

                foreach ($organisation['schools'] as $school) {

                    $schoolFetch = $this->SchoolsService->getSchool($school['id']);

                    foreach($schoolFetch['school_locations'] as $schoollocation) {
                        $schoollocation['external_main_code'] = $data['external_main_code'];
                        $this->SchoolLocationsService->updateSchoolLocation($schoollocation['id'], $schoollocation);
                    }

                    $school['external_main_code'] = $data['external_main_code'];
                    $response = $this->SchoolsService->updateSchool($school['id'],$school);
                }
            }

            $result = $this->UmbrellaOrganisationsService->updateOrganisation($organisation_id, $data);

            if(!$result) {
                $errors[] = 'School kon niet worden aangemaakt';
            }

            $this->formResponse(
                $result ? true : false,
                $errors
            );

            die;
        }

        $params['filter'] = ['role' => [5]];
        $users = $this->UsersService->getUsers($params);

        $accountmanagers = [];

        foreach($users as $user) {
            $accountmanagers[$user['id']] = $user['name_first'] . ' ' . $user['name_suffix'] . ' ' . $user['name'];
        }

        $this->set('accountmanagers', $accountmanagers);

        $organisation = $this->UmbrellaOrganisationsService->getOrganisation($organisation_id);
        $organisation['UmbrellaOrganisation'] = $organisation;

        $this->request->data = $organisation;
    }

    public function delete($id) {
        $result = $this->UmbrellaOrganisationsService->deleteOrganisation($id);

        $this->formResponse(
            $result ? true : false,
            []
        );
    }

    public function add() {
        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['UmbrellaOrganisation'];

            $result = $this->UmbrellaOrganisationsService->addOrganisation($data);

            $this->formResponse(
                $result ? true : false,
                []
            );
        }

        $params['filter'] = ['role' => [5]];
        $users = $this->UsersService->getUsers($params);

        $accountmanagers = [];

        foreach($users as $user) {
            $accountmanagers[$user['id']] = $user['name_first'] . ' ' . $user['name_suffix'] . ' ' . $user['name'];
        }

        $this->set('accountmanagers', $accountmanagers);
    }
}