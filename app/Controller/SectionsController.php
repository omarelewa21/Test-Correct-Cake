<?php

App::uses('AppController', 'Controller');
App::uses('SectionsService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');

/**
 * Sections controller
 *
 */
class SectionsController extends AppController
{
    public function beforeFilter()
    {
        $this->SectionsService = new SectionsService();
        $this->SchoolLocationsService = new SchoolLocationsService();
        parent::beforeFilter();
    }


    public function index() {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);
    }

    public function view($id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $section = $this->SectionsService->getSection($id);
        $this->set('section', $section);
    }

    public function load() {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        $params = $this->request->data;
        $sections = $this->SectionsService->getSections($params);

        $this->set('sections', $sections);
    }

    public function edit($section_id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data['Section'];

            $result = $this->SectionsService->updateSection($section_id, $data);

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;
        }

        $section = $this->SectionsService->getSection($section_id);
        $section['Section'] = $section;
        
        $this->request->data = $section;

        $this->set('locations', $this->SchoolLocationsService->getSchoolLocationList());
    }

    public function delete($id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if ($this->request->is('delete')) {
            $result = $this->SectionsService->deleteSection($id);

            $this->formResponse(
                $result ? true : false,
                []
            );
        }
    }

    public function delete_subject($id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if($this->request->is('delete')) {
            $result = $this->SectionsService->deleteSectionSubject($id);

            $this->formResponse(
                $result ? true : false,
                []
            );
        }
    }

    public function add() {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['Section'];

            $result = $this->SectionsService->addSection($data);

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;

        }

        $this->set('locations', $this->SchoolLocationsService->getSchoolLocationList());
    }

    public function add_subject($section_id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['Subject'];

            $result = $this->SectionsService->addSectionSubject($section_id, $data);

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;

        }

        $this->set('base_subjects', $this->SectionsService->getBaseSubjects());
        $this->set('section_id', $section_id);
    }

    public function edit_subject($subject_id) {
        $this->isAuthorizedAs(['Administrator', 'Account manager', 'School manager', 'School management']);

        if($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data['Subject'];

            $result = $this->SectionsService->updateSectionSubject($subject_id, $data);

            $this->formResponse(
                $result ? true : false,
                []
            );

            die;
        }

        $subject = $this->SectionsService->getSectionSubject($subject_id);

        $this->set('base_subjects', $this->SectionsService->getBaseSubjects());

        $this->request->data['Subject'] = $subject;
    }
}