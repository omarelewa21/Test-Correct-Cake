<?php

App::uses('AppController', 'Controller');
App::uses('TestsService', 'Lib/Services');
App::uses('QuestionsService', 'Lib/Services');
App::uses('AnswersService', 'Lib/Services');
App::uses('AttachmentsService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('HelperFunctions', 'Lib');
App::uses('CarouselMethods', 'Trait');

class TestsController extends AppController
{
    use CarouselMethods;

    public $uses = array('Test', 'Question');

    public $carouselGroupQuestionNotifyMsg = '';

    public function beforeFilter()
    {
        $this->TestsService = new TestsService();
        $this->QuestionsService = new QuestionsService();
        $this->AnswersService = new AnswersService();
        $this->AttachmentsService = new AttachmentsService();
        $this->SchoolLocationsService = new SchoolLocationsService();

        parent::beforeFilter();
    }

    public function test()
    {
    }

    public function index()
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $education_level_years = [
//            0 => __("Alle"),
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6
        ];

        $education_levels = $this->TestsService->getEducationLevels();


        $periods = $this->TestsService->getPeriods();
        $subjects = HelperFunctions::getInstance()->revertSpecialChars(['' => __("Alle")] + $this->TestsService->getSubjects(false));

        $kinds = $this->TestsService->getKinds();
//        for($i = 1; $i < count($kinds)+1; $i++){
//            $kinds[$i] = __($kinds[$i]);
//        }
        foreach($kinds as $key => $kind) {
            $kinds[$key] = __($kind);
        }

        //$education_levels = [0 => __("Alle")] + $education_levels;
        $periods = [0 => __("Alle")] + $periods;
        //$subjects = [0 => __("Alle")] + $subjects;
        $kinds = [0 => __("Alle")] + $kinds;

        $this->set('education_levels', $education_levels);
        $this->set('education_level_years', $education_level_years);
        $this->set('kinds', $kinds);
        $this->set('periods', $periods);
        $this->set('subjects', $subjects);
    }

    public function get_authors()
    {
        $authors = $this->TestsService->getAuthors();
        $this->formResponse(
            !empty($authors), $authors
        );
    }

    public function delete($test_id)
    {
        $this->isAuthorizedAs(["Teacher"]);

        if ($this->request->is('delete')) {
            $result = $this->TestsService->deleteTest($test_id);
            $this->formResponse(!empty($result));
        }
    }

    public function duplicate($test_id)
    {
        $this->isAuthorizedAs(["Teacher"]);

        if ($this->request->is('put')) {
            $response = $this->TestsService->duplicate($test_id);

            $this->formResponse(
                !empty($response), $response
            );
        }
    }

    public function setQuestionsOpenSource($questions, $owner_id, $owner = 'test')
    {
        $this->isAuthorizedAs(["Teacher"]);

        foreach ($questions as $question) {
            // In case the type of questions is a group question we want to only apply this logic
            // To questions that are shown in the test, and not questions that are not physical questions.
            if (
                isset($question['question']['group_question_questions']) &&
                $question['question']['type'] == 'GroupQuestion'
            ) {
                $this->setQuestionsOpenSource(
                    $question['question']['group_question_questions'],
                    $open_sourced,
                    getUUID($question, 'get'),
                    'group'
                );
            }
            // We change the value we want to update.
            $question['question']['is_open_source_content'] = $open_sourced;

            $r = $this->requestAction(
                ['controller' => 'Questions', 'action' => 'editPost'],
                [
                    // 'pass' => [ $owner, $owner_id, $question['question']['type'], $question['id']],
                    'data' => [$owner, $owner_id, $question['question']['type'], getUUID($question, 'get'), false, true]
                ]
            );
        }

        return true;
    }

    public function add()
    {
        $this->isAuthorizedAs(["Teacher"]);

        if ($this->request->is('post')) {
            $test = $this->request->data['Test'];
            $result = $this->TestsService->add($test);

            if ($result == 'unique_name') {
                $this->formResponse(false, 'unique_name');
            } else {
                $this->formResponse(!empty($result), $result);
            }
        }

        $school_location_id = $this->Session->read('Auth.User.school_location.uuid');
        $school_location = $this->SchoolLocationsService->getSchoolLocation($school_location_id);

        $params['filter'] = ['current_school_year' => 1];

        $kinds = $this->TestsService->getKinds();
//        for($i = 1; $i < count($kinds)+1; $i++){
//            $kinds[$i] = __($kinds[$i]);
//        }
        foreach ($kinds as $key => $kind) {
            $kinds[$key] = __($kind);
        }
        $periods = $this->TestsService->getPeriods(false, $params);
        $subjects = HelperFunctions::getInstance()->revertSpecialChars($this->TestsService->getCurrentSubjectsForTeacher());
        $education_levels = $this->TestsService->getEducationLevels(false);

        if(array_key_exists('content_creation_step',$this->params['url']) && $this->params['url']['content_creation_step'] == 2) {
            $this->set('opened_from_content', true);
        } else {
            $this->set('opened_from_content', false);
        }

        $this->set('kinds', $kinds);
        $this->set('periods', $periods);
        $this->set('subjects', $subjects);
        $this->set('education_levels', $education_levels);
        $this->set('is_open_source_content_creator', (bool)$school_location['is_open_source_content_creator']);

    }

    public function edit($test_id)
    {
        $this->isAuthorizedAs(["Teacher"]);

        if ($this->request->is('post') || $this->request->is('put')) {

            $test = $this->request->data['Test'];
            $questions = $this->TestsService->getQuestions($test_id);

            // todo: Deze methode liep door elkaar qua test_id en owner_id, dit was heel raar. Moeten we uitzoeken
            //$this->setQuestionsOpenSource($questions, $test_id);

            $result = $this->TestsService->edit($test_id, $test);

            if ($result == 'unique_name') {
                $this->formResponse(false, 'unique_name');
            } else {
                $this->formResponse(!empty($result), $result);
            }
        }

        $school_location_id = $this->Session->read('Auth.User.school_location.uuid');
        $school_location = $this->SchoolLocationsService->getSchoolLocation($school_location_id);

        $kinds = $this->TestsService->getKinds();
//        for($i = 1; $i < count($kinds)+1; $i++){
//            $kinds[$i] = __("$kinds[$i]");
//        }
        foreach($kinds as $key => $kind) {
            $kinds[$key] = __($kind);
        }

        $periods = $this->TestsService->getPeriods();
        $subjects = $this->TestsService->getSubjects(true);
        $education_levels = $this->TestsService->getEducationLevels(false);

        $this->request->data['Test'] = $this->TestsService->getTest($test_id);

        $currentEducationlevelUuid = '';
        foreach ($education_levels as $id => $level) {
            if ($level['id'] == $this->request->data['Test']['education_level_id']) {
                $currentEducationlevelUuid = $level['uuid'];
            }
        }
        $this->set('education_level_year',$this->request->data['Test']['education_level_year']);
        $this->set('test_id',$test_id);
        $this->set('current_education_level_uuid', $currentEducationlevelUuid);
        $this->set('kinds', $kinds);
        $this->set('periods', $periods);
        $this->set('subjects', $subjects);
        $this->set('education_levels', $education_levels);
        $this->set('is_open_source_content_creator', (bool)$school_location['is_open_source_content_creator']);
    }

    public function create_copy_and_update($test_id)
    {
        $response = $this->TestsService->duplicate($test_id);
        if ($this->request->is('post') || $this->request->is('put')) {

            $test = $this->request->data['Test'];
            if(stristr($response['name'],$test['name'])){
                $test['name'] = $response['name'];
            }

            $questions = $this->TestsService->getQuestions($response['uuid']);

            $result = $this->TestsService->edit($response['uuid'], $test);

            if ($result == 'unique_name') {
                $this->formResponse(false, 'unique_name');
            } else {
                $this->formResponse(!empty($result), $result);
            }
        }
    }

    public function load()
    {
//        http_response_code(404);
//        exit;
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $education_levels = $this->TestsService->getEducationLevels(true, false);

        $periods = $this->TestsService->getPeriods();
        $subjects = $this->TestsService->getSubjects();
        $kinds = $this->TestsService->getKinds();
//        for($i = 1; $i < count($kinds)+1; $i++){
//            $kinds[$i] = __("$kinds[$i]");
//        }
        foreach($kinds as $key => $kind) {
            $kinds[$key] = __($kind);
        }

        $this->set('education_levels', $education_levels);
        $this->set('kinds', $kinds);
        $this->set('periods', $periods);
        $this->set('subjects', $subjects);

        $params = $this->request->data;

        $filters = array();
        parse_str($params['filters'], $filters);
        $filters = $filters['data']['Test'];

        unset($params['filters']);

        $params['filter'] = [];

        if (!empty($filters['name'])) {
            $params['filter']['name'] = $filters['name'];
        }

        if (!empty($filters['kind'])) {
            $params['filter']['test_kind_id'] = $filters['kind'];
        }

        if ($this->hasValidFilterValue($filters['subject'])) {
            $params['filter']['subject_id'] = $filters['subject'];
        }

        if (!empty($filters['period'])) {
            $params['filter']['period_id'] = $filters['period'];
        }

        if (!empty($filters['education_levels'])) {
            $params['filter']['education_level_id'] = $filters['education_levels'];
        }

        if (!empty($filters['education_level_years'])) {
            $params['filter']['education_level_year'] = $filters['education_level_years'];
        }

        if (!empty($filters['created_at_start'])) {
            $params['filter']['created_at_start'] = date('Y-m-d 00:00:00', strtotime($filters['created_at_start']));
        }

        if (!empty($filters['created_at_end'])) {
            $params['filter']['created_at_end'] = date('Y-m-d 00:00:00', strtotime($filters['created_at_end']));
        }

        if (!empty($filters['author_id'])) {
            $params['filter']['author_id'] = $filters['author_id'];
        }

        if (!empty($filters['is_open_sourced_content']) && $filters['is_open_sourced_content'] != 0) {
            $params['filter']['is_open_sourced_content'] = ($filters['is_open_sourced_content'] == 2) ? 1 : 0;
        }

        $params = $this->handleRequestOrderParameters($params);
        $tests = $this->TestsService->getTests($params);

        $this->set('tests', $tests['data']);
    }

    public function view($test_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $test = $this->TestsService->getTest($test_id);


        $this->Session->write('active_test', $test);

        $questions = $this->TestsService->getQuestions($test_id);

        $questionsArray = array();
        $totalScore = $this->TestsService->getTestScore($test_id,[]);

        $this->set('carouselGroupQuestionNotify', false);

        foreach ($questions as $question) {

            $question['question'] = $this->QuestionsService->decodeCompletionTags($question['question']);

            if ($question['question']['type'] == 'CompletionQuestion') {
                $question['question']['question'] = $this->stripTagsWithoutMath($question['question']['question']);
            }

            if ($question['question']['type'] == 'GroupQuestion') {
                for ($i = 0; $i < count($question['question']['group_question_questions']); $i++) {

                    //fix for TC-80 / Selenium tests. The selection options were empty for group questions
                    $question['question']['group_question_questions'][$i]['question'] = $this->QuestionsService->decodeCompletionTags($question['question']['group_question_questions'][$i]['question']);

                    //$totalScore += $question['question']['group_question_questions'][$i]['question']['score'];
                    $question['question']['group_question_questions'][$i]['question']['question'] = strip_tags($question['question']['group_question_questions'][$i]['question']['question']);
                }
                $this->setNotificationsForViewGroup($question['question']);
            }
            array_push($questionsArray, $question);
        }

        $education_levels = $this->TestsService->getEducationLevels();
        $periods = $this->TestsService->getPeriods();
        $subjects = $this->TestsService->getSubjects();
        $kinds = $this->TestsService->getKinds();
//        for($i = 1; $i < count($kinds)+1; $i++){
//            $kinds[$i] = __("$kinds[$i]");
//        }
        foreach($kinds as $key => $kind) {
            $kinds[$key] = __($kind);
        }

        $this->set('totalScore', $totalScore);
        if($msg != ''){
            $this->set('totalScore', '<i class="fa fa-exclamation-triangle" title="'.$msg.'"></i>');
        }

        $this->set('education_levels', $education_levels);
        $this->set('kinds', $kinds);
        $this->set('periods', $periods);
        $this->set('subjects', $subjects);
        $this->set('test', $test);
        $this->set('canEdit', $test['author']['id'] == AuthComponent::user()['id'] && $test['status'] != 1);
        $this->set('questions', $questionsArray);
        $this->set('test_id', $test_id);

        $newPlayerAccess = in_array($test['owner']['allow_new_player_access'], [1,2]);
        $oldPlayerAccess = in_array($test['owner']['allow_new_player_access'], [0,1]);
        $this->set('newPlayerAccess', $newPlayerAccess);
        $this->set('oldPlayerAccess', $oldPlayerAccess);
        $this->set('startWithEdit',false);
        $this->set('newEditor', AuthComponent::user('school_location.allow_new_question_editor') ?? 0);
    }

    /**
     * Show Regular PDF with attachments.
     */
    public function pdf_preview($test_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $this->set('test_id', $test_id);
        $this->render('pdf_preview', 'ajax');
    }

    /**
     * Check if the PDF has attachment and load a different view that shows
     * an overview of all different attachment for seperate download functionality.
     */
    public function pdf_showPDFAttachment($test_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $this->set('test_id', $test_id);
        $questions = $this->TestsService->getQuestions($test_id);
        $questionsArray = [];

        foreach ($questions as $question) {
            if ($question['question']['type'] != 'GroupQuestion') {
                $question = $this->QuestionsService->getQuestion('test', $test_id, getUUID($question, 'get'));

                $questionsArray[] = $this->Question->printVersion($question['question']);
            } else {

                foreach ($question['question']['group_question_questions'] as $groupQuestionsQuestion) {

                    $groupQuestion = $this->QuestionsService->getSingleQuestion(getUUID($groupQuestionsQuestion['question'], 'get'));

                    $groupQuestion['question'] = $question['question']['question'] . '<br />' . $groupQuestion['question'];
                    $groupQuestion['attachments'] = $question['question']['attachments'];

                    $questionsArray[] = $this->Question->printVersion($groupQuestion);
                }
            }
        }

        $attachmentCount = 0;
        $attchmentArray = array();
        $referenceList = [];

        for ($i = 0; $i < count($questionsArray); $i++) {
            if ($questionsArray[$i]['attachments'] != '') {
                for ($a = 0; $a < count($questionsArray[$i]['attachments']); $a++) {
                    if ($questionsArray[$i]['attachments'][$a]['type'] == 'file' && strpos($questionsArray[$i]['attachments'][$a]['title'], '.pdf')) {
                        // only show a file once in the list, based on title
                        if (!in_array($questionsArray[$i]['attachments'][$a]['title'], $referenceList)) {
                            $attachmentCount++;
                            $attchmentArray[$i][$a]['attachments']['title'] = $questionsArray[$i]['attachments'][$a]['title'];
                            $attchmentArray[$i][$a]['attachments']['id'] = $questionsArray[$i]['attachments'][$a]['id'];
                            $attchmentArray[$i][$a]['attachments']['uuid'] = getUUID($questionsArray[$i]['attachments'][$a], 'get');
                            $attchmentArray[$i][$a]['attachments']['filename'] = $questionsArray[$i]['attachments'][$a]['file_name'];
                            //$questionsArray[$i]['attachments'][$a]['data'] = "data:" . $questionsArray[$i]['attachments'][$a]['file_mime_type'] . ";base64," . base64_encode($this->AnswersService->getAttachmentContent($questionsArray[$i]['attachments'][$a]['id']));

                            $referenceList[] = $questionsArray[$i]['attachments'][$a]['title'];
                        }
                    }
                }
            }
        }

        if ($attachmentCount > 0) {
            $this->set('attachmentArray', $attchmentArray);
            $this->set('attachmentcount', $attachmentCount);
            $this->render('pdf_preview_attchment', 'ajax');
        } else {
            $this->render('pdf_preview', 'ajax');
        }
    }

    public function pdf_attachment_select($test_id, $attachment_id = null)
    {

        // if ($this->request->is('post')) {

        $this->set('test_id', $test_id);

        // $view = new View($this, false);
        // $view->set('questionTypes', $questionTypes);
        // $view->set('education_levels', $education_levels);
        // $view->set('test', $test);
        // $view->set('questions', $questionsArray);
        // $view->set('test_id', $test_id);

        // $attachment = $this->request->data['attachment'];

        if (!empty($attachment_id)) {

            $this->set('attachment_id', $attachment_id);

            // foreach ($attachment as $value) {
            // print_r($value);

            //$this->set('test_id', $value);
            //$this->render('pdf_preview', 'ajax');
            //                $this->autoRender = false;
            //
            //                $attachmentInfo = $this->AnswersService->getAttachmentInfo($value);
            //                $attachmentContent = $this->AnswersService->getAttachmentContent($value);
            //
            //                //file_put_contents(WWW_ROOT.'/mp3/' . time() . '.mp3', $attachmentContent);
            //                //header('location: /mp3/' . time() . '.mp3');
            //                //$this->response->type($attachmentInfo['file_mime_type']);
            //                //$this->response->body($attachmentContent);
            //                //$view->set('test_id', $test_id);
            //                $view = new View($this, false);
            //                // Generate PDF
            //                $html = $view->render('attachmentpdf', 'pdf');
            //                $this->response->body(HtmlConverter::htmlToPdf($html, 'portrait'));
            //                //$this->response->body($attachmentContent);
            //                $this->response->type('pdf');
            //
            //                return $this->response;

            //print_r();
            //$getAttachment = $this->AttachmentsService->getAttachment($value);
            // }

            $this->render('pdf_attachment', 'ajax');
        } else {
            $this->render('pdf_preview', 'ajax');
            return;
        }
//            exit;
//
//
//            echo "<pre>";
//            print_r($getAttachment);
//            exit;
//
//
//            $this->set('attachmentArray', $attchmentArray);
//            $this->set('attachmentcount', $attachmentCount);

        // $html = $view->render('pdf_preview_attchment', 'ajax');


        // Generate PDF
        // $html = $view->render('pdf', 'pdf');

        // $this->response->body(HtmlConverter::htmlToPdf($html, 'portrait'));
        // $this->response->type('json');

        // return $this->response;
        // }

    }

    public function pdf_container($test_id, $attachment_id = null)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $this->set('test_id', $test_id);
        $this->set('attachment_id', $attachment_id);
        $this->render('/Pdf/pdf_container', 'ajax');
    }

    public function pdf($test_id, $attachment_id = null)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $debug = '';
        $this->autoRender = false;

        $test = $this->TestsService->getTest($test_id);
        $questions = $this->TestsService->getQuestions($test_id);

        $questionsArray = [];

        foreach ($questions as $question) {
            if ($question['question']['type'] != 'GroupQuestion') {
                $question = $this->QuestionsService->getQuestion('test', $test_id, getUUID($question, 'get'));

                $questionsArray[] = $this->Question->printVersion($question['question']);
            } else {

                foreach ($question['question']['group_question_questions'] as $groupQuestionsQuestion) {

                    $groupQuestion = $this->QuestionsService->getSingleQuestion(getUUID($groupQuestionsQuestion['question'], 'get'));

                    $groupQuestion['question'] = $question['question']['question'] . '<br />' . $groupQuestion['question'];
                    $groupQuestion['attachments'] = $question['question']['attachments'];

                    $questionsArray[] = $this->Question->printVersion($groupQuestion);
                }
            }
        }

        for ($i = 0; $i < count($questionsArray); $i++) {
            if ($questionsArray[$i]['type'] === 'DrawingQuestion') {
                if ($questionsArray[$i]['bg_name'] !== null) {
                    $attachmentContent = $this->AnswersService->getBackgroundContent(getUUID($questionsArray[$i], 'get'));
                    $questionsArray[$i]['answer_background_image'] = "data:" . $questionsArray[$i]['bg_mime_type'] . ";base64," . base64_encode($attachmentContent);
                }
            }

            for ($a = 0; $a < count($questionsArray[$i]['attachments']); $a++) {
                if ($questionsArray[$i]['attachments'][$a]['type'] == 'file') {
                    $questionsArray[$i]['attachments'][$a]['data'] = "data:" . $questionsArray[$i]['attachments'][$a]['file_mime_type'] . ";base64," . base64_encode($this->AnswersService->getAttachmentContent(getUUID($questionsArray[$i]['attachments'][$a], 'get')));
                }
            }
        }
        // Default data
        $education_levels = $this->TestsService->getEducationLevels();
        $questionTypes = [
            'MultipleChoiceQuestion' => __("Multiple Choice"),
            'OpenQuestion' => __("Open vraag"),
            'CompletionQuestion' => __("Gatentekstvraag"),
            'RankingQuestion' => __("Rangschik-vraag"),
            'MatchingQuestion' => __("Matching"),
            'MatrixQuestion' => __("Matrix"),
        ];

        $view = new View($this, false);
        $view->set('questionTypes', $questionTypes);
        $view->set('education_levels', $education_levels);
        $view->set('test', $test);
        $view->set('questions', $questionsArray);
        $view->set('test_id', $test_id);

        $debug = $this->Session->Read();

        $view->set('debug', $debug);

        $logo_url = 'https://testportal.test-correct.nl/img/logo_full.jpg';
        if (strstr($logo_url, $_SERVER['HTTP_HOST']) != false) {
            $logo_url = 'https://portal.test-correct.nl/img/logo_full.jpg';
        }
        $view->set('logo_url', $logo_url);

        // Generate PDF
        $html = $view->render('pdf', 'pdf');

        $this->response->body(HtmlConverter::getInstance()->htmlToPdf($html));
        $this->response->type('pdf');

        return $this->response;
    }


    public function pdf_attachmentpdf($test_id, $attachment_id)
    {

        $test = $this->request->data['Test'];
        $this->autoRender = false;

        $test = $this->TestsService->getTest($test_id);

        $questions = $this->TestsService->getQuestions($test_id);

        $attachmentMatch = array();

        foreach ($questions as $question) {

            foreach ($question['question']['attachments'] as $attachment) {
                if (getUUID($attachment, 'get') == $attachment_id) {
                    $attachmentMatch = $attachment;
                    if ($attachment['type'] == 'file') {
                        $attachmentMatch['data'] = "data:" . $attachment['file_mime_type'] . ";base64," . base64_encode($this->AnswersService->getAttachmentContent(getUUID($attachment, 'get')));
                    }
                }
            }
        }

        $view = new View($this, false);
        $view->set('attachment', $attachmentMatch);

        // Generate PDF
        $html = $view->render('attachmentpdf', 'pdf');

        $this->response->body($attachmentMatch['data']);
        $this->response->type('pdf');

        return $this->response;
    }

    public function load_att_pdf($test_id, $attachment_id)
    {
        $test = $this->TestsService->getTest($test_id);

        $questions = $this->TestsService->getQuestions($test_id);

        $attachmentMatch = array();

        foreach ($questions as $question) {
            foreach ($question['question']['attachments'] as $attachment) {
                if (getUUID($attachment, 'get') == $attachment_id) {
                    $attachmentMatch = $attachment;
                    if ($attachment['type'] == 'file') {
                        $attachmentMatch['data'] = "data:" . $attachment['file_mime_type'] . ";base64," . base64_encode($this->AnswersService->getAttachmentContent(getUUID($attachment, 'get')));
                        break 2;
                    }
                }
            }
        }


        $this->set('base64', $attachmentMatch['data']);
        $this->set('filename', $attachment['title']);
        $this->render('pdfatt', 'ajax');

        return $this->response;

    }

    public function preview_popup($test_id)
    {
        $this->set('test_id', $test_id);
        $this->render('preview_popup', 'ajax');
    }

    public function preview($test_id, $question_index = 0)
    {
        $this->isAuthorizedAs(["Teacher"]);

        $allQuestions = $this->TestsService->getQuestions($test_id);

        $questions = [];

        foreach ($allQuestions as $allQuestion) {
            if ($allQuestion['question']['type'] == 'GroupQuestion') {

                foreach ($allQuestion['question']['group_question_questions'] as $item) {
                    $item['question']['question'] = '<p>' . $allQuestion['question']['question'] . '</p>' . $item['question']['question'];
                    $item['question']['attachments'] = $allQuestion['question']['attachments'];
                    $questions[] = $item;
                }
            } else {
                $questions[] = $allQuestion;
            }
        }
        $this->Session->write('preview.questions.' . $test_id, $questions);

        $this->set('question_index', $question_index);
        $this->set('test_id', $test_id);
        $this->set('questions', $questions);
        $this->render('preview', 'preview');
    }

    public function get_preview_url($testId)
    {
        return $this->formResponse(true,  $this->TestsService->getTestUrlForLaravel($testId));
    }

    public function create_content()
    {

    }

    private function hasValidFilterValue($filterValue)
    {
        if(empty($filterValue)){
            return false;
        }
        if(!is_array($filterValue)){
            return true;
        }
        foreach($filterValue as $key => $value){
            if(!empty($value)){
                return true;
            }
        }
        return false;
    }
}
