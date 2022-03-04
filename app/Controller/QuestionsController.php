<?php

App::uses('AppController', 'Controller');
App::uses('QuestionsService', 'Lib/Services');
App::uses('TestsService', 'Lib/Services');
App::uses('AnswersService', 'Lib/Services');
App::uses('SchoolLocationsService', 'Lib/Services');
App::uses('File', 'Utility');
App::uses('HelperFunctions','Lib');
App::uses('CarouselMethods', 'Trait');

class QuestionsController extends AppController
{
    use CarouselMethods;

    private $carouselGroupQuestionNotifyMsg = '';

    public function beforeFilter()
    {
        $this->QuestionsService = new QuestionsService();
        $this->TestsService = new TestsService();
        $this->AnswersService = new AnswersService();
        $this->SchoolLocationsService = new SchoolLocationsService();

        parent::beforeFilter();
    }

    protected function hasBackendValidation($questionType)
    {
        $questionTypesWithBackendValidation = ['matchingquestion', 'completionquestion', 'multicompletionquestion', 'completionautovalidatequestion'];
        return (bool)(in_array(strtolower($questionType), $questionTypesWithBackendValidation));
    }

    public function index()
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $education_level_years = $this->getEducationLevelYears();

        $education_levels = $this->TestsService->getEducationLevels();
        $subjects = $this->TestsService->getSubjects(true);

        $_baseSubjects = $this->TestsService->getMyBaseSubjects();

        $baseSubjects = [
            '' => __('Alle'),
        ];

        foreach ($_baseSubjects as $baseSubject) {
            $baseSubjects[getUUID($baseSubject, 'get')] = $baseSubject['name'];
        }

        $baseSubjects = HelperFunctions::getInstance()->revertSpecialChars($baseSubjects);
        $education_levels = ['' => __('Alle')] + $education_levels;
        $subjects = HelperFunctions::getInstance()->revertSpecialChars(['' => __('Alle')] + $subjects);


        $filterTypes = [
            '' => 'Alle',
            'MultipleChoiceQuestion.TrueFalse' => __('Juist / Onjuist'),
            'MultipleChoiceQuestion.ARQ' => __('ARQ'),
            'MultipleChoiceQuestion.MultipleChoice' => __('Meerkeuze'),
            'OpenQuestion.Short' => __('Korte open vraag'),
            'OpenQuestion.Long' => __('Lange open vraag'),
            'CompletionQuestion.multi' => __('Selectie'),
            'CompletionQuestion.completion' => __('Gatentekst'),
            'RankingQuestion' => __('Rangschik'),
            'MatchingQuestion.Matching' => __('Combineer'),
            'MatchingQuestion.Classify' => __('Rubriceer'),
            'DrawingQuestion' => __('Teken'),
            'GroupQuestion' => __('Groepvraag')
        ];

        $filterSource = [
            '' => __('Alles'),
            'me' => __('Eigen content'),
            'schoolLocation' => __('Schoollocatie'),
        ];
        if (AuthComponent::user('hasSharedSections')) {
            $filterSource['school'] = __('Scholengemeenschap');
        }

        $this->set('education_levels', $education_levels);
        $this->set('education_level_years', $education_level_years);
        $this->set('subjects', $subjects);
        $this->Set('filterTypes', $filterTypes);
        $this->set('filterSource', $filterSource);
        $this->set('baseSubjects', $baseSubjects);
    }

    public function preview_single($question_id)
    {

        $question = $this->QuestionsService->getSingleQuestion($question_id);

        $question['question'] = $question;
        $question['discuss'] = 0;
        $question['maintain_position'] = 0;

        $tagsArray = [];

        foreach ($question['question']['tags'] as $tag) {
            $tagsArray[$tag['name']] = $tag['name'];
        }

        $question['question']['tags'] = $tagsArray;

        switch ($question['question']['type']) {

            case 'DrawingQuestion' :
                $this->Session->write('drawing_data', $question['question']['answer']);
                $view = 'edit_drawing';
                break;

            case 'InfoscreenQuestion' :
                $view = 'edit_infoscreen';
                break;

            case 'OpenQuestion' :
                $view = 'edit_open';
                break;

            case 'CompletionQuestion' :
                $question['question'] = $this->QuestionsService->decodeCompletionTags($question['question']);
                if ($question['question']['subtype'] == 'multi') {
                    $view = 'edit_multi_completion';
                } else {
                    $view = 'edit_completion';
                }
                break;

            case 'MultipleChoiceQuestion' :
                if ($question['question']['subtype'] == 'TrueFalse') {
                    $view = 'edit_true_false';
                } elseif ($question['question']['subtype'] == 'ARQ') {
                    $view = 'edit_arq';
                } else {
                    $view = 'edit_multiple_choice';
                }
                break;

            case 'RankingQuestion':
                $view = 'edit_ranking';
                break;

            case 'MatchingQuestion' :
                if ($question['question']['subtype'] == 'Classify') {
                    $view = 'edit_classify';
                } else {
                    $view = 'edit_matching';
                }
                break;

            case 'GroupQuestion' :
                $view = 'no_preview_group_question';
                break;
        }

        $test = $this->Session->read('active_test');
        $this->set('attainments', $this->QuestionsService->getAttainments($test['education_level_id'], $test['subject_id']));

        $selectedAttainments = [];

        foreach ($question['question']['attainments'] as $attainment) {
            $selectedAttainments[] = getUUID($attainment, 'get');
        }

        $this->set('selectedAttainments', $selectedAttainments);
        $this->set('question', $question);
        $this->set('owner', 'test');
        $this->set('owner_id', 0);

        $this->set('editable', false);
        $this->Session->write('attachments_editable', false);

        $this->render($view, 'ajax');
    }

    public function preview_single_load($question_id, $group_id = null, $hideExtra = false)
    {
        $this->autoRender = false;
        $question = $this->QuestionsService->getSingleQuestion($question_id);

        if (isset($group_id) && !empty($group_id)) {
            $group = $this->QuestionsService->getSingleQuestion($group_id);
            $question['attachments'] = $group['attachments'];
            $question['question'] = $group['question'] . '<br /><br />' . $question['question'];
        }

        switch ($question['type']) {
            case 'InfoscreenQuestion':
                $view = 'preview_infoscreen';
                break;

            case 'OpenQuestion':
                $view = 'preview_open';
                break;

            case 'CompletionQuestion':
                if ($question['subtype'] == 'completion') {
                    $view = 'preview_completion';
                } else {
                    $view = 'preview_multi_completion';
                }
                break;

            case 'MatchingQuestion':
                $view = 'preview_matching';
                break;

            case 'MultipleChoiceQuestion':
                if ($question['subtype'] == 'ARQ') {
                    $view = 'preview_arq';
                } else {
                    $view = 'preview_multiple_choice';
                }
                break;

            case 'RankingQuestion':
                $view = 'preview_ranking';
                break;

            case 'DrawingQuestion':
                $view = 'preview_drawing';
                break;

            case 'MatrixQuestion':
                $view = 'preview_matrix';
                break;

            default:
                echo $question['type'];
                break;
        }

        $this->set('test_id', null);
        $this->set('question', $question);
        $this->set('hideExtra', $hideExtra);
        $this->render($view, 'ajax');

    }

    public function preview_answer_load($id)
    {
        $time = microtime(true);
        $question = $this->QuestionsService->getSingleQuestion($id);

        switch ($question['type']) {
            case 'InfoscreenQuestion':
                $view = 'preview_infoscreen_answer';
                break;

            case 'OpenQuestion':
                $view = 'preview_open_answer';
                break;

            case 'CompletionQuestion':
                if ($question['subtype'] == 'completion') {
                    $view = 'preview_completion_answer';
                } else {
                    $view = 'preview_multi_completion_answer';
                }
                break;

            case 'MatchingQuestion':
                $view = 'preview_matching_answer';
                break;

            case 'MultipleChoiceQuestion':
                if ($question['subtype'] == 'ARQ') {
                    $view = 'preview_arq_answer';
                } else {
                    $view = 'preview_multiple_choice_answer';
                }
                break;

            case 'RankingQuestion':
                $view = 'preview_ranking_answer';
                break;

            case 'DrawingQuestion':
                $this->set('image', $this->getDrawingQuestionAnswerImage($question));
                $view = 'preview_drawing_answer';
                break;

            case 'MatrixQuestion':
                $view = 'preview_matrix_answer';
                break;

            default:
                echo $question['type'];
                break;
        }

        $this->set('test_id', null);
        $this->set('question', $question);

        $this->render($view, 'ajax');
    }


    public function preview($test_id, $question_index)
    {
        $this->autoRender = false;
        $questions = $this->Session->read('preview.questions.' . $test_id);

        $oriquestion = $questions[$question_index];


        $question = $this->AnswersService->getParticipantQuestion(getUUID($oriquestion['question'], 'get'));

        $question['attachments'] = $oriquestion['question']['attachments'];
        $question['question'] = $oriquestion['question']['question'];


        switch ($question['type']) {
            case 'InfoscreenQuestion':
                $view = 'preview_infoscreen';
                break;

            case 'OpenQuestion':
                $view = 'preview_open';
                break;

            case 'CompletionQuestion':
                if ($question['subtype'] == 'completion') {
                    $view = 'preview_completion';
                } else {
                    $view = 'preview_multi_completion';
                }
                break;

            case 'MatchingQuestion':
                $view = 'preview_matching';
                break;

            case 'MultipleChoiceQuestion':

                if ($question['subtype'] == 'MultipleChoice' || $question['subtype'] == 'MultiChoice') {
                    $view = 'preview_multiple_choice';
                } elseif ($question['subtype'] == 'TrueFalse') {
                    $view = 'preview_multiple_choice';
                } else {
                    $view = 'preview_arq';
                }
                break;

            case 'RankingQuestion':
                $view = 'preview_ranking';
                break;

            case 'DrawingQuestion':
                $view = 'preview_drawing';
                break;

            case 'MatrixQuestion':
                $view = 'preview_matrix';
                break;

            default:
                echo $question['type'];
                break;
        }

        if ($question_index < (count($questions) - 1)) {
            $this->set('next_question', ($question_index + 1));
        }

        $this->set('test_id', $test_id);
        $this->set('question', $question);
        $this->set('hideExtra', false);
        $this->render($view, 'ajax');
    }

    public function add_custom($owner, $owner_id, $test_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);
        $this->set('newEditor', AuthComponent::user('school_location.allow_new_question_editor') ?? 0);
        $this->set('newDrawingQuestion', AuthComponent::user('school_location.allow_new_drawing_question') ?? 0);
        $this->set('owner', $owner);
        $this->set('test_id', $test_id ?? $owner_id);
        $this->set('owner_id', $owner_id);
    }

    public function add_open_selection($owner, $owner_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $this->set('owner', $owner);
        $this->set('owner_id', $owner_id);
    }

    public function move_to_group($test_id = null, $question_id = null)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        if ($this->request->is('post')) {
            $this->autoRender = false;
            $group_id = $this->request->data['group_id'];
            $test_id = $this->Session->read('test_id');
            $question_id = $this->Session->read('question_id');

            $this->QuestionsService->moveToGroup($question_id, $group_id);

        } else {

            $this->Session->write('question_id', $question_id);
            $this->Session->write('test_id', $test_id);

            $groups = $this->QuestionsService->getGroups($test_id);

            $this->set('groups', $groups);
        }
    }

    public function add_group($test_id,$groupquestion_type = 'standard')
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        if ($this->request->is('post')) {
            $group = $this->request->data['QuestionGroup'];

            $result = $this->QuestionsService->addGroup($test_id, $group);

            $this->formResponse(
                !empty($result),
                $result
            );
        }

        $test = $this->Session->read('active_test');
        $this->set('attainments', $this->QuestionsService->getAttainments($test['education_level_id'], $test['subject_id']));
        $this->set('test_id', $test_id);
        $this->handleGroupQuestionType($groupquestion_type,__('aanmaken'));


    }

    public function edit_group($test_id, $group_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        if ($this->request->is('post') || $this->request->is('put')) {
            $group = $this->request->data['QuestionGroup'];

            $result = $this->QuestionsService->updateGroup($test_id, $group_id, $group);

            $this->formResponse(
                !empty($result),
                $result
            );
        }

        $group = $this->QuestionsService->getQuestion('test', $test_id, $group_id);
        $group['QuestionGroup'] = $group['question'];
        $this->set('groupQuestionName',$group['question']['name']);

        $test = $this->Session->read('active_test');
        $this->set('attainments', $this->QuestionsService->getAttainments($test['education_level_id'], $test['subject_id']));

        $selectedAttainments = [];

        foreach ($group['QuestionGroup']['attainments'] as $attainment) {
            $selectedAttainments[] = getUUID($attainment, 'get');
        }

        $this->set('selectedAttainments', $selectedAttainments);
        $this->request->data = $group;
        $this->set('test_id', $test_id);
        $this->handleGroupQuestionType($group['QuestionGroup']['groupquestion_type'],__('bewerken'));
        $this->set('carouselGroupQuestionNotify', false);
        $this->setNotificationsForViewGroup($group['QuestionGroup']);
    }

    public function editPost()
    {
        return $this->edit(
            $this->request['data'][0],
            $this->request['data'][1],
            $this->request['data'][2],
            $this->request['data'][3],
            true
        );
    }

    /**
     * @param $owner
     * @param $owner_id
     * if $owner = 'test' $owner_id is instance of tcCore\Test::uuid
     * if $owner = 'group' $owner_id is instance of tcCore\TestQuestion::uuid
     * @param $type
     * @param $question_id tcCore\GroupQuestionQuestion::id || tcCore\TestQuestion::id
     * @param  false  $internal
     * @param  false  $hideresponse
     * @param  false  $is_clone_request
     * @return false|void
     */
    public function edit($owner, $owner_id, $type, $question_id, $internal = false, $hideresponse = false, $is_clone_request = false)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);
        $oldQuestion = $this->QuestionsService->getQuestion($owner, $owner_id, $question_id);

        if ($this->request->is('post') || $internal == true) {

            $question = $this->request->data['Question'];
            if ($question == null && $internal == true) {
                $question = $oldQuestion['question'];
            }

            $test = $this->TestsService->getTest($owner_id);

            if (empty($question['type'])) {
                //TCP-133
                //TrueFalse and ARQ are subtypes of MultipleChoice
                //so type should be MultipleChoiceQuestion
                if ($type == "TrueFalseQuestion") {
                    $question['type'] = 'MultipleChoiceQuestion';
                    $question['subtype'] = 'TrueFalse';
                } elseif ($type == "ARQQuestion") {
                    $question['type'] = 'MultipleChoiceQuestion';
                    $question['subtype'] = 'ARQ';
                } else {
                    $question['type'] = $type;
                }
            }

            if ($test['is_open_source_content'] == 1) {
                $question['add_to_database'] = 1;
                $question['is_open_source_content'] = 1;
            } else {
                $question['is_open_source_content'] = 0;
            }

            if (!empty($question['sub_attainments'])) {
                $question['attainments'] = [
                    $question['attainments'], $question['sub_attainments']
                ];
            }

            $check = $this->Question->check($type, $question, $this->Session->read());
            if ($check['status'] == true) {

                $result = $this->QuestionsService->editQuestion($owner, $owner_id, $type, $question_id, $question, $this->Session->read(), true);

                if ($this->hasBackendValidation($type) && !$result) {

                    $this->formResponse(false, $this->QuestionsService->getErrors());
                    return false;
                }

                // if ($type == 'TrueFalseQuestion') {
                //     $this->QuestionsService->addTrueFalseAnswers($result, $question, $owner);
                // }

//                if($type == 'CompletionQuestion') {
//                    $this->QuestionsService->addCompletionQuestionAnswers($result, $question, $owner);
//                }

                // if ($type == 'MultipleChoiceQuestion') {
                //     $this->QuestionsService->addMultiChoiceAnswers($result, $question, $owner);

                // }

                // if ($type == 'ARQQuestion') {
                //     $this->QuestionsService->addARQAnswers($result, $question, $owner);
                // }

//                if($type == 'RankingQuestion') {
//                    $this->QuestionsService->addRankingAnswers($result, $question, $owner);
//                }

//                if($type == 'ClassifyQuestion') {
//                    $this->QuestionsService->addClassifyAnswers($result, $question, $owner);
//                }
                if ($type == 'DrawingQuestion') {
                    $this->Session->delete('drawing_grid');
                    $this->Session->delete('drawing_data');
                    $this->Session->delete('drawing_background');
                }

//                if($type == 'MatchingQuestion') {
//                    if($oldQuestion['question']['subtype'] == 'Classify') {
//                        $this->QuestionsService->addClassifyAnswers($result, $question, $owner);
//                    }else{
//                        $this->QuestionsService->addMatchingAnswers($result, $question, $owner);
//                    }
//                }

                if ($internal === false) {
                    $this->formResponse(true);
                    die;
                }
            } else if (!$hideresponse) {
                $this->formResponse(false, $check['errors']);
            }
        } else {

            $question = $this->QuestionsService->getQuestion($owner, $owner_id, $question_id);

            $tagsArray = [];

            foreach ($question['question']['tags'] as $tag) {
                $tagsArray[$tag['name']] = $tag['name'];
            }
            $test = $this->Session->read('active_test');
            $this->set('test_name', $test['name']);
            $question['question']['tags'] = $tagsArray;

            switch ($question['question']['type']) {

                case 'DrawingQuestion' :
                    $this->Session->write('drawing_data', $question['question']['answer']);
                    $view = 'edit_drawing';
                    break;

                case 'InfoscreenQuestion' :
                    $view = 'edit_infoscreen';
                    break;

                case 'OpenQuestion' :
                    $this->set('subtype', $question['question']['subtype']);
                    $view = 'edit_open';
                    break;

                case 'CompletionQuestion' :
                    $question['question'] = $this->QuestionsService->decodeCompletionTags($question['question']);
                    if ($question['question']['subtype'] == 'multi') {
                        $view = 'edit_multi_completion';
                    } else {
                        $view = 'edit_completion';
                    }
                    break;

                case 'MultipleChoiceQuestion' :

                    if ($question['question']['subtype'] == 'TrueFalse') {
                        $view = 'edit_true_false';
                    } elseif ($question['question']['subtype'] == 'ARQ') {
                        $view = 'edit_arq';
                    } else {
                        $view = 'edit_multiple_choice';
                    }
                    if(!$question['question']['html_specialchars_encoded']){
                        break;
                    }
                    foreach ($question['question']['multiple_choice_question_answers'] as $key => $answerArray){
                        $question['question']['multiple_choice_question_answers'][$key]['answer'] = $this->QuestionsService->transformHtmlCharsReverse($answerArray['answer']);
                    }
                    break;

                case 'RankingQuestion':
                    $view = 'edit_ranking';
                    if(!$question['question']['html_specialchars_encoded']){
                        break;
                    }
                    foreach ($question['question']['ranking_question_answers'] as $key => $answerArray){
                        $question['question']['ranking_question_answers'][$key]['answer'] = $this->QuestionsService->transformHtmlCharsReverse($answerArray['answer']);
                    }
                    break;

                case 'MatchingQuestion' :
                    if ($question['question']['subtype'] == 'Classify') {
                        $view = 'edit_classify';
                    } else {
                        $view = 'edit_matching';
                    }
                    if(!$question['question']['html_specialchars_encoded']){
                        break;
                    }
                    foreach ($question['question']['matching_question_answers'] as $key => $answerArray){
                        $question['question']['matching_question_answers'][$key]['answer'] = $this->QuestionsService->transformHtmlCharsReverse($answerArray['answer']);
                    }
                    break;
            }

            $test = $this->Session->read('active_test');
            $this->set('attainments', $this->QuestionsService->getAttainments($test['education_level_id'], $test['subject_id']));

            $selectedAttainments = [];

            foreach ($question['question']['attainments'] as $attainment) {
                $selectedAttainments[] = $attainment['id'];
            }

            $this->set('selectedAttainments', $selectedAttainments);
            $this->set('question', $question);
            $this->set('owner', $owner);
            $this->set('owner_id', $owner_id);
            $this->set('editable', true);
            $this->set('is_clone_request', $is_clone_request);
            $this->Session->write('attachments_editable', true);

            $school_location_id = $this->Session->read('Auth.User.school_location.uuid');
            $school_location = $this->SchoolLocationsService->getSchoolLocation($school_location_id);
            $this->set('is_open_source_content_creator', (bool)$school_location['is_open_source_content_creator']);

            $this->render($view, 'ajax');
        }
    }

    public function clone($owner, $owner_id, $question_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $question = $this->QuestionsService->getQuestion($owner, $owner_id, $question_id);

        $tagsArray = [];

        foreach ($question['question']['tags'] as $tag) {
            $tagsArray[$tag['name']] = $tag['name'];
        }

        $question['question']['tags'] = $tagsArray;

        switch ($question['question']['type']) {

            case 'DrawingQuestion' :
                $this->Session->write('drawing_data', $question['question']['answer']);
                $view = 'clone_drawing';
                break;

            case 'InfoscreenQuestion' :
                $view = 'clone_infoscreen';
                break;

            case 'OpenQuestion' :
                $this->set('subtype', $question['question']['subtype']);
                $view = 'clone_open';
                break;

            case 'CompletionQuestion' :
                $question['question'] = $this->QuestionsService->decodeCompletionTags($question['question']);
                if ($question['question']['subtype'] == 'multi') {
                    $view = 'clone_multi_completion';
                } else {
                    $view = 'clone_completion';
                }
                break;

            case 'MultipleChoiceQuestion' :

                if ($question['question']['subtype'] == 'TrueFalse') {
                    $view = 'clone_true_false';
                } elseif ($question['question']['subtype'] == 'ARQ') {
                    $view = 'clone_arq';
                } else {
                    $view = 'clone_multiple_choice';
                }
                if(!$question['question']['html_specialchars_encoded']){
                    break;
                }
                foreach ($question['question']['multiple_choice_question_answers'] as $key => $answerArray){
                    $question['question']['multiple_choice_question_answers'][$key]['answer'] = $this->QuestionsService->transformHtmlCharsReverse($answerArray['answer']);
                }
                break;

            case 'RankingQuestion':
                $view = 'clone_ranking';
                if(!$question['question']['html_specialchars_encoded']){
                    break;
                }
                foreach ($question['question']['ranking_question_answers'] as $key => $answerArray){
                    $question['question']['ranking_question_answers'][$key]['answer'] = $this->QuestionsService->transformHtmlCharsReverse($answerArray['answer']);
                }
                break;

            case 'MatchingQuestion' :
                if ($question['question']['subtype'] == 'Classify') {
                    $view = 'clone_classify';
                } else {
                    $view = 'clone_matching';
                }
                if(!$question['question']['html_specialchars_encoded']){
                    break;
                }
                foreach ($question['question']['matching_question_answers'] as $key => $answerArray){
                    $question['question']['matching_question_answers'][$key]['answer'] = $this->QuestionsService->transformHtmlCharsReverse($answerArray['answer']);
                }
                break;
        }

        $test = $this->Session->read('active_test');
        $this->set('attainments', $this->QuestionsService->getAttainments($test['education_level_id'], $test['subject_id']));

        $selectedAttainments = [];

        foreach ($question['question']['attainments'] as $attainment) {
            $selectedAttainments[] = $attainment['id'];
        }

        $this->set('selectedAttainments', $selectedAttainments);
        $this->set('question', $question);
        $this->set('owner', $owner);
        $this->set('owner_id', $owner_id);
        $this->set('editable', true);
        $this->Session->write('attachments_editable', true);

        $school_location_id = $this->Session->read('Auth.User.school_location.uuid');
        $school_location = $this->SchoolLocationsService->getSchoolLocation($school_location_id);
        $this->set('is_open_source_content_creator', (bool)$school_location['is_open_source_content_creator']);

        $this->render($view, 'ajax');
    }

    public function add_multi_completion_item()
    {

    }

    public function add_completion_item()
    {

    }

    public function add_existing($owner, $owner_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $this->Session->write('addExisting', [
            'owner' => $owner,
            'owner_id' => $owner_id
        ]);

        $education_level_years = [
            0 => __("Alle"),
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6
        ];

        $education_levels = $this->TestsService->getEducationLevels();
        $subjects = $this->TestsService->getSubjects(true);

//        $education_levels = [0 => __("Alle")] + $education_levels;
//        $subjects = [0 => __("Alle")] + $subjects;

        $_baseSubjects = $this->TestsService->getMyBaseSubjects();

        $baseSubjects = [
            '' => __("Alle"),
        ];

        foreach($_baseSubjects as $baseSubject){
            $baseSubjects[getUUID($baseSubject,'get')] = $baseSubject['name'];
        }

        $filterTypes = [
            '' => __("Alle"),
            'MultipleChoiceQuestion.TrueFalse' => __("Juist / Onjuist"),
            'MultipleChoiceQuestion.ARQ' => __("ARQ"),
            'MultipleChoiceQuestion.MultipleChoice' => __("Meerkeuze"),
            'OpenQuestion.Short' => __("Korte open vraag"),
//            'OpenQuestion.Medium' => __("Lange open vraag"),
//            'OpenQuestion.Long' => __("Wiskunde vraag"),
            'OpenQuestion.Long' => __("Lange open vraag"),
            'CompletionQuestion.multi' => __("Selectie"),
            'CompletionQuestion.completion' => __("Gatentekst"),
            'RankingQuestion' => __("Rangschik"),
            'MatchingQuestion.Matching' => __("Combineer"),
            'MatchingQuestion.Classify' => __("Rubriceer"),
            'DrawingQuestion' => __("Teken"),
            'GroupQuestion' => __("Groepvraag")
        ];

        $test = $this->Session->read('active_test');

        $filterSource = [
            '' => __("Alles"),
            'me' => __("Eigen content"),
            'schoolLocation' => __("Schoollocatie"),
        ];
        if(AuthComponent::user('hasSharedSections')){
            $filterSource['school'] = __("Scholengemeenschap");
        }

        $this->set('subject_id', getUUID($test['subject'], 'get'));
        $this->set('year_id', $test['education_level_year']);
        $this->set('education_level_id', $test['education_level_id']);

        $this->set('education_levels', $education_levels);
        $this->set('education_level_years', $education_level_years);
        $this->set('subjects', $subjects);
        $this->set('filterTypes', $filterTypes);
        $this->set('baseSubjects',$baseSubjects);
        $this->set('filterSource',$filterSource);
    }

    /**
     * @param $owner
     * @param $owner_id Note the owner in this scenario is the groupId.
     */
    public function add_existing_to_group($owner, $owner_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $this->Session->write('addExisting', [
            'owner' => $owner, // group
            'owner_id' => $owner_id // group_id
        ]);

        $education_level_years = [
            0 => 'Alle',
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6
        ];

        $education_levels = $this->TestsService->getEducationLevels();
        $subjects = $this->TestsService->getSubjects(true);

        $education_levels = [0 => 'Alle'] + $education_levels;
        $subjects = [0 => 'Alle'] + $subjects;

        $_baseSubjects = $this->TestsService->getMyBaseSubjects();

        $baseSubjects = [
            '' => 'Alle',
        ];

        foreach($_baseSubjects as $baseSubject){
            $baseSubjects[getUUID($baseSubject,'get')] = $baseSubject['name'];
        }

        $filterTypes = [

            '' => __("Alle"),
            'MultipleChoiceQuestion.TrueFalse' => __("Juist / Onjuist"),
            'MultipleChoiceQuestion.ARQ' => __("ARQ"),
            'MultipleChoiceQuestion.MultipleChoice' => __("Meerkeuze"),
            'OpenQuestion.Short' => __("Korte open vraag"),
//            'OpenQuestion.Medium' => __("Lange open vraag"),
//            'OpenQuestion.Long' => __("Wiskunde vraag"),
            'OpenQuestion.Long' => __("Lange open vraag"),
            'CompletionQuestion.multi' => __("Selectie"),
            'CompletionQuestion.completion' => __("Gatentekst"),
            'RankingQuestion' => __("Rangschik"),
            'MatchingQuestion.Matching' => __("Combineer"),
            'MatchingQuestion.Classify' => __("Rubriceer"),
            'DrawingQuestion' => __("Teken"),
            'GroupQuestion' => __("Groepvraag")
        ];

        $test = $this->Session->read('active_test');

        $filterSource = [
            '' => 'Alles',
            'me' => 'Eigen content',
            'schoolLocation' => 'Schoollocatie',
        ];
        if(AuthComponent::user('hasSharedSections')){
            $filterSource['school'] = 'Scholengemeenschap';
        }

        $this->set('subject_id', getUUID($test['subject'], 'get'));
        $this->set('year_id', $test['education_level_year']);
        $this->set('education_level_id', $test['education_level_id']);

        $this->set('education_levels', $education_levels);
        $this->set('education_level_years', $education_level_years);
        $this->set('subjects', $subjects);
        $this->set('filterTypes', $filterTypes);
        $this->set('baseSubjects',$baseSubjects);
        $this->set('filterSource',$filterSource);
    }

    public function add_existing_question($question_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $this->autoRender = false;

        $data = $this->Session->read('addExisting');

        $this->QuestionsService->duplicate($data['owner'], $data['owner_id'], $question_id);
    }

    public function add_existing_question_to_group($question_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $this->autoRender = false;

        $data = $this->Session->read('addExisting');


        $this->QuestionsService->duplicatetogroup($data['owner'], $data['owner_id'], $question_id);
    }



    public function add_existing_question_group($group_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $this->autoRender = false;

        $data = $this->Session->read('addExisting');

        $this->QuestionsService->duplicateGroup('test', $data['owner_id'], $group_id);
    }

    public function add_existing_question_list()
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $params = $this->request->data;

        $filters = [];
        parse_str($params['filters'], $filters);
        $filters = $filters['data']['Question'];

        unset($params['filters']);

//        $params['filter'] = [];
//
//        $filterKeys = [
//            'source' => 'source',
//            'base_subject_id' => 'base_subject_id',
//            'education_levels' => 'education_level_id',
//            'education_level_years' => 'education_level_year',
//            'subject' => 'subject_id',
//            'search' => 'search',
//            'id' => 'id',
//            'is_open_source_content' => 'is_open_source_content',
//            'author_id' => 'author_id'
//        ];
//        foreach ($filterKeys as $from => $to) {
//            if (!empty($filters[$from])) {
//                $params['filter'][$to] = $filters[$from];
//            }
//        }
//        if (!empty($filters['type'])) {
//            $typeFilter = explode('.', $filters['type']);
//            $params['filter']['type'] = $typeFilter[0];
//            if (isset($typeFilter[1])) {
//                $params['filter']['subtype'] = $typeFilter[1];
//            }
//        }
        $params['filter'] = $this->getQuestionFilterParams($filters);

        $questions = $this->QuestionsService->getAllQuestions($params);
        foreach ($questions['data'] as $question) {
            if ($question['type'] !== 'GroupQuestion') {
                $question['question'] = $this->stripTagsWithoutMath($question['question']);
            }
        }
        $education_levels = $this->TestsService->getEducationLevels();
        $this->set('education_levels', $education_levels);
        $this->set('questions', $questions['data']);
    }

    public function add_existing_question_to_group_list()
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $params = $this->request->data;
        $filters = array();
        parse_str($params['filters'], $filters);

        $filters = $filters['data']['Question'];

        unset($params['filters']);

        $params['filter'] = [];

        if(!empty($filters['source'])){
            $params['filter']['source'] = $filters['source'];
        }

        if(!empty($filters['base_subject_id'])){
            $params['filter']['base_subject_id'] = $filters['base_subject_id'];
        }

        if (!empty($filters['subject'])) {
            $params['filter']['subject_id'] = $filters['subject'];
        }

        if (!empty($filters['education_levels'])) {
            $params['filter']['education_level_id'] = $filters['education_levels'];
        }

        if (!empty($filters['education_level_years'])) {
            $params['filter']['education_level_year'] = $filters['education_level_years'];
        }

        if (!empty($filters['id'])) {
            $params['filter']['id'] = $filters['id'];
        }


        if (!empty($filters['search'])) {
            $params['filter']['search'] = $filters['search'];
        }

        if (!empty($filters['is_open_source_content'])) {
            $params['filter']['is_open_source_content'] = $filters['is_open_source_content'];
        }

        if (!empty($filters['type'])) {

            $typeFilter = explode('.', $filters['type']);

            $params['filter']['type'] = $typeFilter[0];

            if (isset($typeFilter[1])) {
                $params['filter']['subtype'] = $typeFilter[1];
            }
        }

        $questions = $this->QuestionsService->getAllQuestions($params);
        $filter_group_questions = [];
        foreach ($questions['data'] as $question) {
            // @todo this should be done better in the backend
            if ($question['type'] !== 'GroupQuestion') {
                $question['question'] = $this->stripTagsWithoutMath($question['question']);
                array_push($filter_group_questions, $question);
            }
        }

        $education_levels = $this->TestsService->getEducationLevels();
        $this->set('education_levels', $education_levels);
        $this->set('questions', $filter_group_questions);


    }


    public function add_existing_test_list()
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $education_levels = $this->TestsService->getEducationLevels();
        $periods = $this->TestsService->getPeriods();
        $subjects = $this->TestsService->getSubjects();
        $kinds = $this->TestsService->getKinds();
        $data = $this->Session->read('addExisting');

        $this->set('education_levels', $education_levels);
        $this->set('kinds', $kinds);
        $this->set('periods', $periods);
        $this->set('subjects', $subjects);

        $params = $this->handleRequestOrderParameters($this->request->data);
        $tests = $this->TestsService->getTests($params);
        $this->set('test_id', $data['owner_id']);
        $this->set('tests', $tests['data']);
    }

    public function closeable_info()
    {

    }

    public function group_closeable_info()
    {

    }

    public function public_info()
    {

    }

    public function drawing_background_add()
    {
        $this->autoRender = false;
        $file = new File($this->request->data['Question']['background']['tmp_name']);
        $tmpFile = TMP . time();
        $file->copy($tmpFile);
        $this->Session->write('drawing_background', $tmpFile);
    }

    public function add($owner, $owner_id, $type)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        if ($this->request->is('post')) {

            $question = $this->request->data['Question'];

            if ($owner == 'test') {
                $test = $this->TestsService->getTest($owner_id);
            } else {
                $test = $this->QuestionsService->getTest(getUUID($this->Session->read('active_test'), 'get'));
            }

            if ($test['is_open_source_content'] == 1) {
                $question['add_to_database'] = 1;
                $question['is_open_source_content'] = 1;
            } else {
                $question['is_open_source_content'] = 0;
            }

            if (!empty($question['sub_attainments'])) {
                $question['attainments'] = [
                    $question['attainments'], $question['sub_attainments']
                ];
            }

            $check = $this->Question->check($type, $question, $this->Session->read());
            if ($check['status'] == true) {
                $result = $this->QuestionsService->addQuestion($owner, $owner_id, $type, $question, $this->Session->read());
                if(is_string($result)){
                    $this->formResponse(false, $this->QuestionsService->getErrors());
                    return false;
                }
                if ($this->hasBackendValidation($type) && !$result) {

                    $this->formResponse(false, $this->QuestionsService->getErrors());
                    return false;
                }

                // if ($type == 'TrueFalseQuestion') {
                //     $this->QuestionsService->addTrueFalseAnswers($result, $question, $owner);
                // }

                /**
                 * 20190110 switched off as we decided to save the data in one call with add question
                 */
//                if($type == 'CompletionQuestion' || $type == 'MultiCompletionQuestion') {
//                    $this->QuestionsService->addCompletionQuestionAnswers($result, $question, $owner);
//                }

                // if ($type == 'MultiChoiceQuestion') {
                //     $this->QuestionsService->addMultiChoiceAnswers($result, $question, $owner);
                // }

                // if ($type == 'ARQQuestion') {
                //     $this->QuestionsService->addARQAnswers($result, $question, $owner);
                // }

//                if($type == 'RankingQuestion') {
//                    $this->QuestionsService->addRankingAnswers($result, $question, $owner);
//                }

                /**
                 * 20190514 switched off as we decided to save the data in one call with add question
                 **/
//                if($type == 'MatchingQuestion') {
//                    $this->QuestionsService->addMatchingAnswers($result, $question, $owner);
//                }

//                if($type == 'ClassifyQuestion') {
//                    $this->QuestionsService->addClassifyAnswers($result, $question, $owner);
//                }



                if ($type == 'DrawingQuestion') {
                    $this->QuestionsService->uploadBackground($result, $question, $owner, $this->Session->read());
                    $this->Session->delete('drawing_data');
                    $this->Session->delete('drawing_grid');
                    $this->Session->delete('drawing_background');
                }

                if ($owner == 'test') {
                    $attachments = $this->Session->read('attachments');
                    $this->QuestionsService->addAttachments('question', getUUID($result, 'get'), $attachments);
                    $this->Session->write('attachments', []);
                }

                $this->formResponse(true);
            } else {
                $this->formResponse(false, $check['errors']);
            }
        } else {

            $this->set('owner', $owner);
            $this->set('owner_id', $owner_id);

            $test = $this->Session->read('active_test');
            $this->Session->write('attachments_editable', true);
            $this->set('test_name', $test['name']);
            $this->set('editable', true);
            $this->set('attainments', $this->QuestionsService->getAttainments($test['education_level_id'], $test['subject_id']));
            $this->set('selectedAttainments', []);

            $school_location_id = $this->Session->read('Auth.User.school_location.uuid');
            $school_location = $this->SchoolLocationsService->getSchoolLocation($school_location_id);
            $this->set('is_open_source_content_creator', (bool)$school_location['is_open_source_content_creator']);

            switch ($type) {
                case 'InfoscreenQuestion':
                    $this->render('add_infoscreen', 'ajax');
                    break;

                case 'OpenQuestion' :
                    $this->render('add_open', 'ajax');
                    break;

                case 'CompletionQuestion' :
                    $this->render('add_completion', 'ajax');
                    break;

                case 'TrueFalseQuestion' :
                    $this->render('add_true_false', 'ajax');
                    break;

                case 'MultipleChoiceQuestion' :
                    $this->render('add_multiple_choice', 'ajax');
                    break;

                case 'ARQQuestion' :
                    $this->render('add_arq_question', 'ajax');
                    break;

                case 'RankingQuestion' :
                    $this->render('add_ranking', 'ajax');
                    break;

                case 'MatchingQuestion' :
                    $this->render('add_matching', 'ajax');
                    break;
                case 'ClassifyQuestion' :
                    $this->render('add_classify', 'ajax');
                    break;

                case 'MultiCompletionQuestion' :
                    $this->render('add_multi_completion', 'ajax');
                    break;

                case 'DrawingQuestion' :

                    $this->Session->delete('drawing_data');
                    $this->Session->delete('drawing_background');

                    $this->render('add_drawing', 'ajax');
                    break;
            }
        }
    }

    public function save_drawing()
    {
        $this->autoRender = false;
        $data = $this->request->data['drawing'];

        if ($this->Session->write('drawing_data', $data)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function add_drawing_answer()
    {
        if ($this->Session->check('drawing_data')) {
            $this->set('drawing_data', $this->Session->read('drawing_data'));
        }
    }

    public function add_drawing_grid($grid)
    {
        $this->autoRender = false;
        $this->Session->write('drawing_grid', $grid);
    }

    public function add_drawing_answer_canvas()
    {
        $this->render('add_drawing_answer_canvas', 'ajax');
    }

    public function delete($owner, $owner_id, $question_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        if ($this->request->is('delete')) {
            $result = $this->QuestionsService->deleteQuestion($owner, $owner_id, $question_id);
            $this->formResponse(!empty($result));
        }
    }

    public function delete_group($test_id, $group_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        if ($this->request->is('delete')) {
            $result = $this->QuestionsService->deleteGroup($test_id, $group_id);
            $this->formResponse(!empty($result));
        }
    }

    public function attachments($type, $owner = null, $owner_id = null, $id = null)
    {
        $cloneAttachments = null;
        if ($type == 'add') {
            if (!$this->Session->check('attachments')) {
                $this->Session->write('attachments', []);
                $attachments = [];
            } else {
                $attachments = $this->Session->read('attachments');
            }
            if(null !== $owner && null !== $owner_id && null !== $id){
                $question = $this->QuestionsService->getQuestion('test', null, $id);
                $cloneAttachments = $question['question']['attachments'];
                $owner = $owner_id = $id = null;
            }
        } elseif ($type == 'edit') {
            $question = $this->QuestionsService->getQuestion('test', null, $id);
            $attachments = [];
            if(array_key_exists('attachments', $question['question']) && is_array($question['question']['attachments'])) {
                $attachments = $question['question']['attachments'];
            }
        }

        $this->set('owner', $owner);
        $this->set('owner_id', $owner_id);
        $this->set('id', $id);
        $this->set('type', $type);
        $this->set('is_clone',!!($type === 'add' && null !== $owner));
        $this->set('editable', $this->Session->read('attachments_editable'));
        $this->set('attachments', $attachments);
        $this->set('clone_attachments',$cloneAttachments);

    }

    public function attachments_sound($type, $owner = null, $owner_id = null, $id = null)
    {
        $this->set('owner', $owner);
        $this->set('owner_id', $owner_id);
        $this->set('id', $id);
        $this->set('type', $type);
    }

    public function attachments_video($type, $owner = null, $owner_id = null, $id = null)
    {
        $this->set('owner', $owner);
        $this->set('owner_id', $owner_id);
        $this->set('id', $id);
        $this->set('type', $type);
    }

    public function remove_add_attachment($id)
    {
        if ($this->request->is('delete')) {
            $this->autoRender = false;
            $attachments = $this->Session->read('attachments');
            unset($attachments[$id]);
            $this->Session->write('attachments', $attachments);
        }
    }

    public function remove_edit_attachment($owner, $owner_id, $id)
    {
        $this->autoRender = false;
        $this->QuestionsService->removeAttachment($owner, $owner_id, $id);
    }

    public function load()
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $params = $this->request->data;

        $filters = [];
        parse_str($params['filters'], $filters);
        $filters = $filters['data']['Question'];

        unset($params['filters']);

        $params['filter'] = $this->getQuestionFilterParams($filters);

        $questions = $this->QuestionsService->getAllQuestions($params);
        $this->set('questions', $questions['data']);
    }

    public function inlineimage($image)
    {

        if (substr_count($image, '..') > 0) {
            exit;
        }

        set_time_limit(0);

        $path = (ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'inlineimages' . DS);

        if (!is_dir($path)) {
            mkdir($path, 0777);
        }

        $file = $path . $image;

        if (!file_exists($file)) {
            $content = ($this->QuestionsService->getInlineImageContent($image));
            if (strlen($content) > 15) {
                file_put_contents($file, base64_decode($content));
            } else {
                exit;
            }
        }

        $mime = mime_content_type($file);
        switch ($mime) {
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/JPG':
            case 'image/JPEG':
                //MF 28-8-2020 added gif as image type dirty but it works (for now)
            case 'image/gif':

                $img = imagecreatefromstring(file_get_contents($file));

                header('Content-type: ' . $mime);
                imagejpeg($img);
                break;
            case 'image/png':
            case 'image/PNG':

                $img = imagecreatefrompng($file);

                imagealphablending($img, false);
                imagesavealpha($img, true);
                imagecolorallocatealpha($img, 0, 0, 0, 127);

                header('Content-type: ' . $mime);
                imagepng($img);
                break;
        }

        imagedestroy($img);
        exit();
    }

    public function upload_attachment($type, $owner = null, $owner_id = null, $id = null)
    {
        $this->autoRender = false;
        $data = $this->request->data;


        if (isset($this->request->data['Question']['file2']['name']) && !empty($this->request->data['Question']['file2']['name'])) {
            $this->request->data['Question']['file'] = $this->request->data['Question']['file2'];
        }

        if (empty($this->request->data['Question']['file']['name'])) {
            echo '<script>window.parent.Attachments.uploadError("no_file");window.parent.Loading.hide();</script>';
            die;
        }

        $extension = substr($this->request->data['Question']['file']['name'], -3);

        if (!in_array($extension, ['jpg', 'peg', 'png', 'mp3', 'pdf'])) {
            echo '<script>window.parent.Attachments.uploadError("file_type");window.parent.Loading.hide();</script>';
            die;
        }

        if (isset($this->request->data['Question']['file']['name']) && !empty($this->request->data['Question']['file']['name'])) {
            $file = new File($this->request->data['Question']['file']['tmp_name']);
            $tmpFile = TMP . time();
            $file->copy($tmpFile);

            if (!empty($errors)) {
                echo '<script>window.parent.Attachments.uploadError("' . $errors . '");window.parent.Loading.hide();</script>';
                die;
            }

            $settings = [];

            if (isset($data['Question']['pausable'])) {
                $settings['pausable'] = $data['Question']['pausable'];
            }

            if (isset($data['Question']['play_once'])) {
                $settings['play_once'] = $data['Question']['play_once'];
            }

            if (isset($data['Question']['timeout'])) {
                $settings['timeout'] = $data['Question']['timeout'];
            }

            if ($type == 'add') {
                if (!$this->Session->check('attachments')) {
                    $this->Session->write('attachments', []);
                    $attachments = [];
                } else {
                    $attachments = $this->Session->read('attachments');
                }

                array_push($attachments, [
                    'type' => 'file',
                    'file' => $this->request->data['Question']['file'],
                    'path' => $tmpFile,
                    'settings' => $settings
                ]);

                $this->Session->write('attachments', $attachments);

                if ($extension == 'mp3') {
                    echo '<script>window.parent.Popup.closeLast();</script>';
                }
                echo '<script>window.parent.Questions.loadAddAttachments();window.parent.Loading.hide();</script>';
            } else {

                $attachments = [];

                array_push($attachments, [
                    'type' => 'file',
                    'file' => $this->request->data['Question']['file'],
                    'path' => $tmpFile,
                    'settings' => $settings
                ]);

                $response = $this->QuestionsService->addAttachments($owner == 'test' ? 'question' : 'question_group', $id, $attachments);

                if(!is_null($response)){
                    $errors = json_decode($response)->errors;
                    $error = false;
                    if(property_exists($errors,'json')&&is_array($errors->json)&&(count($errors->json)>0)){
                        $error = $errors->json[0];
                    }
                    if($error) {
                        echo '<script>window.parent.Attachments.uploadError("' . $error . '");window.parent.Loading.hide();</script>';
                        die;
                    }
                }
                if ($extension == 'mp3') {
                    echo '<script>window.parent.Popup.closeLast();</script>';
                }
                echo '<script>window.parent.Questions.loadEditAttachments("' . $owner . '", "' . $owner_id . '", "' . $id . '"); window.parent.Loading.hide();</script>';
            }
        }
    }

    public function add_video_attachment()
    {
        $this->autoRender = false;
        if (!$this->Session->check('attachments')) {
            $this->Session->write('attachments', []);
            $attachments = [];
        } else {
            $attachments = $this->Session->read('attachments');
        }

        if (!strstr($this->request->data['url'], 'youtube') && !strstr($this->request->data['url'], 'youtu.be') && !strstr($this->request->data['url'], 'vimeo')) {
            echo 0;
        } else {

            array_push($attachments, [
                'type' => 'video',
                'url' => $this->request->data['url']
            ]);

            $this->Session->write('attachments', $attachments);

            echo 1;
        }
    }

    public function add_edit_video_attachment($owner, $owner_id, $id)
    {
        $this->autoRender = false;
        $attachments = [];

        if (!strstr($this->request->data['url'], 'youtube') && !strstr($this->request->data['url'], 'youtu.be') && !strstr($this->request->data['url'], 'vimeo')) {
            echo 0;
        } else {

            array_push($attachments, [
                'type' => 'video',
                'url' => $this->request->data['url']
            ]);

            $this->QuestionsService->addAttachments($owner == 'test' ? 'question' : 'question_group', $id, $attachments);

            echo 1;
        }
    }

    public function tags()
    {
        $this->autoRender = false;
        $query = $_GET['q'];

        $results = ['items' => []];
        $tags = $this->QuestionsService->getTags($query);

        $i = 1;
        foreach ($tags as $tag) {
            $results['items'][] = [
                'id' => $tag,
                'text' => $tag
            ];
            $i++;
        }

        echo json_encode($results);

    }

    public function update_index($type, $question_id, $test_id, $index)
    {
        $this->autoRender = false;
        $this->QuestionsService->setIndex($question_id, $test_id, $index);
    }

    public function update_group_question_index($question_id, $group_id, $index)
    {
        $this->autoRender = false;
        $this->QuestionsService->setGroupQuestionIndex($question_id, $group_id, $index);
    }

    public function view_group($test_id, $group_id)
    {
        $this->isAuthorizedAs(["Teacher", "Invigilator"]);

        $group = $this->QuestionsService->getQuestion('test', '', $group_id);

        $questions = $group['question']['group_question_questions'];

        foreach ($questions as $question) {
            $question['question']['question'] = $this->stripTagsWithoutMath($question['question']['question']);
        }

        usort($questions, function ($a, $b) {
            $a = $a['order'];
            $b = $b['order'];
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });
        $this->set('carouselGroupQuestionNotify', false);
        $this->setNotificationsForViewGroup($group['question']);
        $this->set('questions', $questions);
        $this->set('group', $group);
        $this->set('group_id', $group_id);
        $this->set('test_id', $test_id);
        $this->set('newEditor', AuthComponent::user('school_location.allow_new_question_editor') ?? 0);
        $this->set('usesNewDrawingQuestion', AuthComponent::user('school_location.allow_new_drawing_question') ?? 0);
        $this->Session->write('attachments_editable', true);
    }

    private function handleGroupQuestionType($groupquestion_type,$mode = '__("aanmaken")'){
        $this->set('groupquestion_type', $groupquestion_type);
        $this->set('title',__("Vraaggroep").' '.$mode);
        switch ($groupquestion_type) {
            case 'standard':
                $this->set('title',__("Standaard vraaggroep").' '.$mode);
                break;
            case 'carousel':
                $this->set('title',__("Carrousel vraaggroep").' '.$mode);
                break;
        }
    }

    private function getQuestionFilterParams($filters)
    {
        $filterParams = [];

        $filterKeys = [
            'source' => 'source',
            'base_subject_id' => 'base_subject_id',
            'education_levels' => 'education_level_id',
            'education_level_years' => 'education_level_year',
            'subject' => 'subject_id',
            'search' => 'search',
            'id' => 'id',
            'is_open_source_content' => 'is_open_source_content',
            'author_id' => 'author_id'
        ];
        foreach ($filterKeys as $from => $to) {
            if (!empty($filters[$from])) {
                $filterParams[$to] = $filters[$from];
            }
        }
        if (!empty($filters['type'])) {
            $typeFilter = explode('.', $filters['type']);
            $filterParams['type'] = $typeFilter[0];
            if (isset($typeFilter[1])) {
                $filterParams['subtype'] = $typeFilter[1];
            }
        }
        return $filterParams;
    }

    private function getDrawingQuestionAnswerImage($question)
    {
        if (isset($question['answer']) && !empty($question['answer']) && empty($question['zoom_group'])) {
            return $question['answer'];
        }

        $zoomGroup = json_decode($question['zoom_group'], true);
        $svg = sprintf('<svg viewBox="%s %s %s %s" class="w-full h-full" id="" xmlns="http://www.w3.org/2000/svg">
                    <g class="question-svg">%s</g>
                    <g class="answer-svg">%s</g>
                    <g id="grid-preview-svg" stroke="var(--all-BlueGrey)" stroke-width="1"></g>
                </svg>',
            $zoomGroup['x'],
            $zoomGroup['y'],
            $zoomGroup['width'],
            $zoomGroup['height'],
            base64_decode($question['question_svg']),
            base64_decode($question['answer_svg'])
        );

        return $svg;
    }
}
