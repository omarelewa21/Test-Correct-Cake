<?php

App::uses('AppController', 'Controller');
App::uses('SchoolYearsService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');

/**
 * SchoolYears controller
 *
 */
class SchoolYearsController extends AppController
{
    public function beforeFilter()
    {
        $this->SchoolYearsService = new SchoolYearsService();
        $this->SchoolLocationsService = new SchoolLocationsService();
        parent::beforeFilter();
    }


    public function index() {

    }

    public function view($id) {

        $this->Session->write('year_id', $id);

        $school_year = $this->SchoolYearsService->getSchoolYear($id);
        $this->set('school_year', $school_year);
    }

    public function load() {
        $params = $this->request->data;
        $sections = $this->SchoolYearsService->getSchoolYears($params);

        $this->set('school_years', $sections);
    }

    public function edit($section_id) {

        if($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data['SchoolYear'];

            $result = $this->SchoolYearsService->updateSchoolYear($section_id, $data);

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;
        }

        $section = $this->SchoolYearsService->getSchoolYear($section_id);
        $section['SchoolYear'] = $section;

        $this->request->data = $section;

        $this->set('locations', $this->SchoolLocationsService->getSchoolLocationList());
    }

    public function delete($id) {
        $result = $this->SchoolYearsService->deleteSchoolYear($id);

        $this->formResponse(
            $result ? true : false,
            []
        );
    }

    public function add() {
        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['SchoolYear'];

            $result = $this->SchoolYearsService->addSchoolYear($data);

            $this->formResponse(
                $result ? true : false,
                $result
            );
        }

        $locations = $this->SchoolLocationsService->getSchoolLocationList();

        $this->set('locations', $locations);
    }

    public function delete_period($id) {
        $result = $this->SchoolYearsService->deleteSchoolYearPeriod($id);

        $this->formResponse(
            $result ? true : false,
            []
        );
    }

    public function add_period($school_year_id) {

        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['Period'];

            $check = $this->SchoolYearsService->checkSchoolYearPeriod($school_year_id, $data);

            if($check) {
                $result = $this->SchoolYearsService->addSchoolYearPeriod($school_year_id, $data);

                $this->formResponse(
                    $result ? true : false,
                    []
                );
            }else{
                $this->formResponse(
                    false,
                    []
                );
            }

            die;
        }

        $this->set('school_year_id', $school_year_id);
    }

    public function edit_period($period_id) {

        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['Period'];

            $year_id = $this->Session->read('year_id');

            $check = $this->SchoolYearsService->checkSchoolYearPeriod($year_id, $data, $period_id);

            if($check) {

                $result = $this->SchoolYearsService->updateSchoolYearPeriod($period_id, $data);

                $this->formResponse(
                    $result ? true : false,
                    []
                );

                die;
            }else{
                $this->formResponse(
                    false,
                    []
                );
            }

            die;
        }

        $period = $this->SchoolYearsService->getSchoolYearPeriod($period_id);
        $this->request->data['Period'] = $period;
    }

}