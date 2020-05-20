<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class QuestionsService
 *
 *
 */
class QuestionsService extends BaseService
{

    protected $error = false;

    public function getTests($params)
    {
        $response = $this->Connector->getRequest('/test', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getTest($test_id)
    {
        $response = $this->Connector->getRequest('/test/' . $test_id, []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteQuestion($owner, $owner_id, $question_id)
    {

        if ($owner == 'test') {
            $response = $this->Connector->deleteRequest('/test_question/' . $question_id, []);
        }

        if ($owner == 'group') {
            $response = $this->Connector->deleteRequest('/group_question_question/' . $owner_id . '/' . $question_id, []);
        }


        echo $this->Connector->getLastResponse();
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function deleteGroup($test_id, $group_id)
    {
        $response = $this->Connector->deleteRequest('/test/' . $test_id . '/question_group/' . $group_id, []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function removeAttachment($owner, $owner_id, $id)
    {

        $response = $this->Connector->deleteRequest('/test_question/' . $owner_id . '/attachment/' . $id, []);

    }

    public function getAttachment($attachment)
    {
        $response = $this->Connector->getRequest('/test_question/' . $question_id, []);
    }

    public function addAttachments($owner, $owner_id, $attachments)
    {

        foreach ($attachments as $attachment) {
            if ($attachment['type'] == 'file') {
                $data = [
                    'type' => 'file',
                    'title' => $attachment['file']['name'],
                    'attachment' => new CURLFile($attachment['path']),
                    'json' => isset($attachment['settings']) ? json_encode($attachment['settings']) : '[]'
                ];

                $response = $this->Connector->postRequestFile('/test_question/' . $owner_id . '/attachment', [], $data);
            } elseif ($attachment['type'] == 'video') {
                $data = [
                    'type' => 'video',
                    'link' => $attachment['url']
                ];

                $response = $this->Connector->postRequest('/test_question/' . $owner_id . '/attachment', $data, []);
            }
        }
    }

    public function getInlineImageContent($image) {

        $response = $this->Connector->getDownloadRequest('/question/inlineimage/' . $image, []);
        return $response;
    }

    public function duplicate($owner, $owner_id, $question_id)
    {

        $data['test_id'] = $owner_id;
        $data['order'] = 0;
        $data['maintain_position'] = 0;
        $data['discuss'] = 1;
        $data['question_id'] = $question_id;

        $response = $this->Connector->postRequest('/test_question', [], $data);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }


    public function getQuestion($owner, $owner_id, $question_id)
    {

        if ($owner == 'test') {
            $response = $this->Connector->getRequest('/test_question/' . $question_id, []);
        } else {
            $response = $this->Connector->getRequest('/group_question_question/' . $owner_id . '/' . $question_id, []);
        }

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getSingleQuestion($question_id)
    {

        $response = $this->Connector->getRequest('/question/' . $question_id, []);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getQuestions($test_id)
    {
        $response = $this->Connector->getRequest('/test/' . $test_id . '/question', []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getAllQuestions($params)
    {

        $params['order'] = ['id' => 'desc'];

        $response = $this->Connector->getRequest('/question', $params);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function updateGroup($test_id, $group_id, $data)
    {

        $response = $this->Connector->putRequest('/test_question/' . $group_id, [], $data);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getAttainments($education_level_id, $subject_id)
    {

        $params = [
            'mode' => 'all',
            'filter' => [
                'education_level_id' => $education_level_id,
                'subject_id' => $subject_id,
                'status' => 'ACTIVE'
            ]
        ];

        $response = $this->Connector->getRequest('/attainment', $params);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        $results = [];

        foreach ($response as $item) {
            if (empty($item['attainment_id'])) {
                $results[$item['id']] = [
                    'title' => "[" . $item['code'] . "] " . $item['description'],
                    'attainments' => [
                        '' => 'Geen subdomein'
                    ]
                ];
            }
        }

        foreach ($response as $item) {
            if (!empty($item['attainment_id'])) {
                $results[$item['attainment_id']]['attainments'][$item['id']] = "[" . $item['code'] . $item['subcode'] . "] " . $item['description'];
            }
        }

        return $results;
    }

    public function addGroup($test_id, $group)
    {

        $group = $this->_fillNewQuestionGroup($group);

        $group['test_id'] = $test_id;
        $group['type'] = 'GroupQuestion';

        if (empty($group['attainments'])) {
            $group['attainments'] = [];
        }

        $response = $this->Connector->postRequest('/test_question', [], $group);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getTags($query)
    {
        $response = $this->Connector->getRequest('/tag', [
            'filter' => [
                'complete_name' => $query
            ],
            'mode' => 'list'
        ]);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function addQuestion($owner, $owner_id, $type, $question, $session = null)
    {

        $owner = $owner == 'group' ? 'question_group' : 'test';

        $question['maintain_position'] = isset($question['maintain_position']) ? $question['maintain_position'] : 0;
        $oriQuestion = $question;

        $hasBackendValidation = false;

        switch ($type) {
            case "InfoscreenQuestion":
                $question = $this->_fillNewInfoscreenQuestion($question);
                break;

            case "OpenQuestion":
                $question = $this->_fillNewOpenQuestion($question);
                break;

            case "TrueFalseQuestion":
                $question = $this->_fillNewTrueFalseQuestion($question);
                break;

            case "CompletionQuestion":
                $hasBackendValidation = true;
                $question = $this->_fillNewCompletionQuestion($question, 'completion');
                break;

            case "MultiCompletionQuestion":
                $question = $this->_fillNewCompletionQuestion($question, 'multi');
                break;

            case "MultiChoiceQuestion":
                $question = $this->_fillNewMultiChoiceQuestion($question, 'MultipleChoice');
                break;

            case "ARQQuestion":
                $question = $this->_fillNewMultiChoiceQuestion($question, 'ARQ');
                break;

            case "RankingQuestion":
                $question = $this->_fillNewRankingQuestion($question);
                break;

            case "MatchingQuestion":
                $question = $this->_fillNewMatchingQuestion($question);
                break;

            case "ClassifyQuestion":
                $hasBackendValidation = true;
                $question = $this->_fillNewClassifyQuestion($question);
                break;

            case "DrawingQuestion":
                $question = $this->_fillNewDrawingQuestion($question, $session);
                break;
        }

        if (empty($question['attainments'])) {
            $question['attainments'] = [];
        }

        if (empty($oriQuestion['tags'])) {
            $question['tags'] = [];
        } else {
            $question['tags'] = $oriQuestion['tags'];
        }

        // added or operator for rtti equaling string null because apparently the frontend return string 'null' an not an empty value;
        if (empty($oriQuestion['rtti']) || $oriQuestion['rtti'] === 'null') {
            $question['rtti'] = null;
        } else {
            $question['rtti'] = $oriQuestion['rtti'];
        }

        if ($owner == 'test') {
            $question['test_id'] = $owner_id;
            $response = $this->Connector->postRequest('/test_question', [], $question);
        } else {
            $response = $this->Connector->postRequest('/group_question_question/' . $owner_id, [], $question);
        }

        // die(var_dump($this->Connector));

        if ($response === false) {
            $error = $this->Connector->getLastResponse();
            if ($this->isValidJson($error)) {
                $err = json_decode($error);
                foreach ($err as $k => $e) {
                    if (is_array($e) || is_object($e)) {
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

            if ($hasBackendValidation) {
                return false;
            }
            return $error;
        }

        return $response;
    }

    public function addTrueFalseAnswers($result, $question, $owner)
    {

        $question_id = $result['id'];

        if ($owner == 'test') {
            $question_id = $result['id'];
            $changed = $this->checkTrueFalseAnswersChanged('test', null, $question_id, $question);
            $path = '/test_question/';
        } else {
            $question_id = $result['group_question_question_path'] . '.' . $result['id']; // ID voor wegschrijven vraag
            $changed = $this->checkTrueFalseAnswersChanged('group', $result['group_question_question_path'], $result['id'], $question);
            $path = '/group_question_question/';
        }

        if (!$changed) {
            return;
        }

        $answerTrue = [
            'answer' => 'Juist',
            'score' => $question['anwser'] == 1 ? $question['score'] : 0,
            'order' => 0
        ];

        $answerFalse = [
            'answer' => 'Onjuist',
            'score' => $question['anwser'] == 0 ? $question['score'] : 0,
            'order' => 0
        ];

        $response = $this->Connector->deleteRequest($path . $question_id . '/multiple_choice_question_answer', []);

        $this->Connector->postRequest($path . $question_id . '/multiple_choice_question_answer', [], $answerTrue);
        $this->Connector->postRequest($path . $question_id . '/multiple_choice_question_answer', [], $answerFalse);
    }

    private function checkTrueFalseAnswersChanged($owner, $owner_id, $question_id, $question)
    {

        $currentAnswers = $this->getQuestion($owner, $owner_id, $question_id);
        $currentAnswers = $currentAnswers['question']['multiple_choice_question_answers'];

        if (count($currentAnswers) == 0) {
            return true;
        }

        $currentAnswerResult = '';

        foreach ($currentAnswers as $currentAnswer) {
            if ($currentAnswer['score'] > 0) {
                $currentAnswerResult = $currentAnswer['answer'];
            }
        }
        if ($question['anwser'] == 1 && $currentAnswerResult == 'Onjuist') {
            return true;
        }

        if ($question['anwser'] == 0 && $currentAnswerResult == 'Juist') {
            return true;
        }

        return false;
    }

    public function addMultiChoiceAnswers($result, $question, $owner)
    {

        $question_id = $result['id'];

        if ($owner == 'test') {
            $question_id = $result['id'];
            $changed = $this->checkMultiChoiceAnswersChanged('test', null, $question_id, $question);
            $path = '/test_question/';
        } else {
            $question_id = $result['group_question_question_path'] . '.' . $result['id']; // ID voor wegschrijven vraag
            $changed = $this->checkMultiChoiceAnswersChanged('group', $result['group_question_question_path'], $result['id'], $question);
            $path = '/group_question_question/';
        }

        if (!$changed) {
            return;
        }

        $response = $this->Connector->deleteRequest($path . $question_id . '/multiple_choice_question_answer', []);

        if ($owner != 'test') {
            $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
        }

        foreach ($question['answers'] as $answer) {
            if ($answer['answer'] != '') {

                $response = $this->Connector->postRequest($path . $question_id . '/multiple_choice_question_answer', [], $answer);

                if ($owner != 'test') {
                    $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
                }
            }
        }
    }

    public function addARQAnswers($result, $question, $owner)
    {

        if ($owner == 'test') {
            $question_id = $result['id'];
            $changed = $this->checkARQAnswersChanged('test', null, $question_id, $question);
            $path = '/test_question/';
        } else {
            $question_id = $result['group_question_question_path'] . '.' . $result['id']; // ID voor wegschrijven vraag
            $changed = $this->checkARQAnswersChanged('group', $result['group_question_question_path'], $result['id'], $question);
            $path = '/group_question_question/';
        }

        if (!$changed) {
            return;
        }

        $response = $this->Connector->deleteRequest($path . $question_id . '/multiple_choice_question_answer', []);

        if ($owner != 'test') {
            $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
        }

        $i = 1;
        foreach ($question['answers'] as $answer) {

            $answer['order'] = $i;
            $i++;

            $response = $this->Connector->postRequest($path . $question_id . '/multiple_choice_question_answer', [], $answer);

            if ($owner != 'test') {
                $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
            }
        }
    }

    public function checkMultiChoiceAnswersChanged($owner, $owner_id, $question_id, $question)
    {

        $currentAnswers = $this->getQuestion($owner, $owner_id, $question_id);
        $currentAnswers = $currentAnswers['question']['multiple_choice_question_answers'];

        $changed = false;
        $answerCount = 0;

        $i = 0;
        foreach ($question['answers'] as $answer) {
            if ($answer['answer'] != '') {
                $answerCount++;

                if (isset($currentAnswers[$i]['score'])) {
                    if ($currentAnswers[$i]['answer'] != $answer['answer']) {
                        $changed = true;
                    }

                    if ($currentAnswers[$i]['score'] != $answer['score']) {
                        $changed = true;
                    }
                } else {
                    $changed = true;
                }
            }

            $i++;
        }

        if ($answerCount != count($currentAnswers)) {
            $changed = true;
        }

        return $changed;
    }

    public function checkARQAnswersChanged($owner, $owner_id, $question_id, $question)
    {

        $currentAnswers = $this->getQuestion($owner, $owner_id, $question_id);
        $currentAnswers = $currentAnswers['question']['multiple_choice_question_answers'];

        $changed = false;
        $answerCount = 0;

        if (!isset($currentAnswers['answers']) || count($currentAnswers['answers']) == 0) {
            return true;
        }

        $i = 0;
        foreach ($question['answers'] as $answer) {
            if (isset($currentAnswers[$i]['score'])) {
                if ($currentAnswers[$i]['score'] != $answer['score']) {
                    $changed = true;
                }
            }

            $i++;
        }

        if ($answerCount != count($currentAnswers)) {
            $changed = true;
        }

        return $changed;
    }

    public function addRankingAnswers($result, $question, $owner)
    {

        $question_id = $result['id'];

        if ($owner == 'test') {
            $question_id = $result['id'];
            $changed = $this->checkRankingAnswersChanged('test', null, $question_id, $question);
            $path = '/test_question/';
        } else {
            $question_id = $result['group_question_question_path'] . '.' . $result['id']; // ID voor wegschrijven vraag
            $changed = $this->checkRankingAnswersChanged('group', $result['group_question_question_path'], $result['id'], $question);
            $path = '/group_question_question/';
        }

        if (!$changed) {
            return;
        }

        $response = $this->Connector->deleteRequest($path . $question_id . '/ranking_question_answer', []);

        if ($owner != 'test') {
            $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
        }

        for ($i = 0; $i < 10; $i++) {
            if ($question['answers'][$i]['answer'] != '') {

                $data = $question['answers'][$i];
                $data['correct_order'] = $data['order'];

                $response = $this->Connector->postRequest($path . $question_id . '/ranking_question_answer', [], $data);

                if ($owner != 'test') {
                    $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
                }
            }
        }
    }

    private function checkRankingAnswersChanged($owner, $owner_id, $question_id, $question)
    {
        $currentAnswers = $this->getQuestion($owner, $owner_id, $question_id);
        $currentAnswers = $currentAnswers['question']['ranking_question_answers'];


        $count = 0;
        foreach ($question['answers'] as $answer) {
            if ($answer['answer'] != '') {

                if (isset($currentAnswers[$count]['answer']) && $answer['answer'] != $currentAnswers[$count]['answer']) {
                    return true;
                }

                $count++;
            }
        }

        if ($count != count($currentAnswers)) {
            return true;
        }

        return false;
    }

    public function addMatchingAnswers($result, $question, $owner)
    {

        $question_id = $result['id'];

        if ($owner == 'test') {
            $question_id = $result['id'];
            $changed = $this->checkMatchingAnswersChanged('test', null, $question_id, $question);
            $path = '/test_question/';
        } else {
            $question_id = $result['group_question_question_path'] . '.' . $result['id']; // ID voor wegschrijven vraag
            $changed = $this->checkMatchingAnswersChanged('group', $result['group_question_question_path'], $result['id'], $question);
            $path = '/group_question_question/';
        }

        if (!$changed) {
            return;
        }

        // $response = $this->Connector->deleteRequest($path . $question_id . '/matching_question_answer', []);

        // if($owner != 'test') {
        // $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
        // }

        foreach ($question['answers'] as $answer) {
            if ($answer['left'] != '') {
                $left['order'] = $answer['order'];
                $left['answer'] = $answer['left'];
                $left['type'] = 'left';

                $response = $this->Connector->postRequest($path . $question_id . '/matching_question_answer', [], $left);

                if ($owner != 'test') {
                    $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
                }

                $right['order'] = $answer['order'];
                $right['answer'] = $answer['right'];
                $right['type'] = 'right';
                $right['correct_answer_id'] = $response['id'];

                $response = $this->Connector->postRequest($path . $question_id . '/matching_question_answer', [], $right);

                if ($owner != 'test') {
                    $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
                }
            }
        }
    }

    public function checkMatchingAnswersChanged($owner, $owner_id, $question_id, $question)
    {
        $currentAnswers = $this->getQuestion($owner, $owner_id, $question_id);
        $currentAnswers = $currentAnswers['question']['matching_question_answers'];

        if (empty($currentAnswers)) {
            return true;
        }


        $left = "";

        $currentAnswersArray = [];

        foreach ($currentAnswers as $answer) {
            if ($answer['type'] == 'LEFT') {
                $left = $answer['answer'];
            } else {
                $currentAnswersArray[] = [
                    'left' => $left,
                    'right' => $answer['answer']
                ];
            }
        }

        $count = 0;
        foreach ($question['answers'] as $newAnswer) {

            if ($newAnswer['left'] != '') {

                $found = false;

                if ($currentAnswersArray[$count]['left'] == $newAnswer['left'] && $currentAnswersArray[$count]['right'] == $newAnswer['right']) {
                    $found = true;
                }

                if (!$found) {
                    return true;
                }

                $count++;

            }
        }

        if ($count != count($currentAnswersArray)) {
            return true;
        }


        return false;
    }

    public function addClassifyAnswers($result, $question, $owner)
    {

        $question_id = $result['id'];

        if ($owner == 'test') {
            $question_id = $result['id'];
            $changed = $this->checkClassifyAnswersChanged('test', null, $question_id, $question);
            $path = '/test_question/';
        } else {
            $question_id = $result['group_question_question_path'] . '.' . $result['id']; // ID voor wegschrijven vraag
            $changed = $this->checkClassifyAnswersChanged('group', $result['group_question_question_path'], $result['id'], $question);
            $path = '/group_question_question/';
        }

        if (!$changed) {
            return;
        }

        $response = $this->Connector->deleteRequest($path . $question_id . '/matching_question_answer', []);

        if ($owner != 'test') {
            $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
        }

        for ($i = 0; $i < 10; $i++) {
            if ($question['answers'][$i]['left'] != '') {
                $left['order'] = $question['answers'][$i]['order'];
                $left['answer'] = $question['answers'][$i]['left'];
                $left['type'] = 'left';

                $response = $this->Connector->postRequest($path . $question_id . '/matching_question_answer', [], $left);
                $correct_id = $response['id'];

                if ($owner != 'test') {
                    $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
                }

                $rights = $question['answers'][$i]['right'];
                $rights = explode("\r\n", $rights);

                foreach ($rights as $right) {
                    if ($right != '') {
                        $item['order'] = $question['answers'][$i]['order'];
                        $item['answer'] = $right;
                        $item['type'] = 'right';
                        $item['correct_answer_id'] = $correct_id;
                        $response = $this->Connector->postRequest($path . $question_id . '/matching_question_answer', [], $item);

                        if ($owner != 'test') {
                            $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
                        }
                    }
                }
            }
        }
    }

    private function checkClassifyAnswersChanged($owner, $owner_id, $question_id, $question)
    {
        $currentAnswers = $this->getQuestion($owner, $owner_id, $question_id);

        if (!isset($currentAnswers['question']['matching_question_answers'])) {
            return true;
        }

        $currentAnswers = $currentAnswers['question']['matching_question_answers'];

        $left = "";

        $currentAnswersArray = [];

        foreach ($currentAnswers as $answer) {
            if ($answer['type'] == 'LEFT') {
                $left = $answer['answer'];
            } else {
                $currentAnswersArray[] = [
                    'left' => $left,
                    'right' => $answer['answer']
                ];
            }
        }

        $count = 0;
        foreach ($question['answers'] as $newAnswer) {
            if ($newAnswer['left'] != '') {

                $count++;
                $count += count(explode("\r\n", $newAnswer['right']));

                $found = false;

                foreach ($currentAnswersArray as $currentAnswersItem) {
                    if ($currentAnswersItem['left'] == $newAnswer['left'] && strstr($newAnswer['right'], $currentAnswersItem['right'])) {
                        $found = true;
                    }
                }

                if (!$found) {
                    return true;
                }

            }
        }

        if ($count != count($currentAnswers)) {
            return true;
        }

        return false;
    }

    public function addCompletionQuestionAnswers($result, $question, $owner)
    {

        $question_id = $result['id'];

        if ($owner == 'test') {
            $question_id = $result['id'];
            $changed = $this->checkCompletionQuestionChanged('test', null, $question_id, $question);
            $path = '/test_question/';
        } else {
            $question_id = $result['group_question_question_path'] . '.' . $result['id']; // ID voor wegschrijven vraag
            $changed = $this->checkCompletionQuestionChanged('group', $result['group_question_question_path'], $result['id'], $question);
            $path = '/group_question_question/';
        }


        if (!$changed) {
            return;
        }

        $response = $this->Connector->deleteRequest($path . $question_id . '/completion_question_answer', []);

        if ($owner != 'test') {
            $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
        }

        $answers = $this->encodeCompletionTags($question['question'])['answers'];


        foreach ($answers as $tag => $answer) {

            $answerItems = explode('|', $answer);


            $correct = 1;
            foreach ($answerItems as $answerItem) {

                $response = $this->Connector->postRequest($path . $question_id . '/completion_question_answer', [], [
                    'tag' => $tag,
                    'answer' => $answerItem,
                    'correct' => $correct
                ]);

                if ($owner != 'test') {
                    $question_id = $response['group_question_question_path']; // ID voor wegschrijven vraag
                }

                $correct = 0;
            }
        }

    }

    public function checkCompletionQuestionChanged($owner, $owner_id, $question_id, $question)
    {
        $currentAnswers = $this->getQuestion($owner, $owner_id, $question_id);

        if (!isset($currentAnswers['question']['completion_question_answers'])) {
            return true;
        }

        $currentAnswers = $currentAnswers['question']['completion_question_answers'];

        $answers = $this->encodeCompletionTags($question['question'])['answers'];

        $changed = false;
        $answerCount = 0;

        $i = 0;
        foreach ($answers as $answer) {
            $answerCount++;

            if (!isset($currentAnswers[$i]['answer']) || $currentAnswers[$i]['answer'] != $answer) {
                $changed = true;
            }
            $i++;
        }

        if ($answerCount != count($currentAnswers)) {
            $changed = true;
        }

        return $changed;
    }

    public function editQuestion($owner, $owner_id, $type, $question_id, $question, $session = null)
    {

        $testUrl = '/test_question/' . $question_id;
        $groupUrl = '/group_question_question/' . $owner_id . '/' . $question_id;

        $oriQuestion = $question;

        $params = [];
        $hasBackendValidation = false;

        switch ($type) {
            case "CompletionQuestion":
            case "MatchingQuestion":
            case "ClassifyQuestion":
//                $processed = $this->encodeCompletionTags($question['question']);
//                $question['question'] = $processed['question'];
                $hasBackendValidation = true;
                break;

            case "ARQQuestion":
                $question['score'] = 0;

                for ($i = 0; $i < 5; $i++) {
                    if ($question['answers'][$i]['score'] != '' && $question['answers'][$i]['score'] > $question['score']) {
                        $question['score'] = $question['answers'][$i]['score'];
                    }
                }
                break;

            case "MultipleChoiceQuestion":

                $question['score'] = 0;
                $question['selectable_answers'] = 0;

                for ($i = 0; $i < 10; $i++) {
                    if (!empty($question['answers'][$i]['score'])) {
                        $question['score'] += $question['answers'][$i]['score'];
                    }

                    if ($question['answers'][$i]['score'] != '' && $question['answers'][$i]['score'] > 0) {
                        $question['selectable_answers']++;
                    }
                }
                break;


            case "DrawingQuestion":

                $attachment = null;

                if (isset($session['drawing_background'])) {
                    $attachment = new CURLFile($session['drawing_background']);
                }

                if (isset($session['drawing_grid'])) {
                    $question['grid'] = $session['drawing_grid'];
                }

                if (empty($question['attainments'])) {
                    $question['attainments'] = [];
                }

                if (empty($oriQuestion['tags'])) {
                    $question['tags'] = [];
                } else {
                    $question['tags'] = $oriQuestion['tags'];
                }

                if (empty($oriQuestion['rtti'])) {
                    $question['rtti'] = null;
                } else {
                    $question['rtti'] = $oriQuestion['rtti'];
                }

                $question['answer'] = $session['drawing_data'];
                $question['bg'] = $attachment;

                if ($owner == 'test') {
                    $response = $this->Connector->putRequestFile($testUrl, $params, $question);
                }

                if ($owner == 'group') {
                    $response = $this->Connector->putRequestFile($groupUrl, $params, $question);
                }

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
                    }

                    if ($hasBackendValidation) {
                        return false;
                    }
                    return $error;
                }

                return $response;

                break;
        }

        if (empty($question['attainments'])) {
            $question['attainments'] = [];
        }

        if (empty($oriQuestion['tags'])) {
            $question['tags'] = [];
        } else {
            $question['tags'] = $oriQuestion['tags'];
        }

        if (empty($oriQuestion['rtti'])) {
            $question['rtti'] = null;
        } else {
            $question['rtti'] = $oriQuestion['rtti'];
        }

        if ($owner == 'test') {
            $response = $this->Connector->putRequest($testUrl, $params, $question);
        }

        if ($owner == 'group') {
            $response = $this->Connector->putRequest($groupUrl, $params, $question);
        }

//        if($response === false){
//            return $this->Connector->getLastResponse();
//        }

        // die(json_encode($response));

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
                $this->addError($error);
            }

            if ($hasBackendValidation) {
                return false;
            }
            return $error;
        }

        return $response;
    }

    public function setIndex($question_id, $test_id, $index)
    {
        $response = $this->Connector->putRequest('/test_question/' . $question_id . '/reorder', [], ['order' => $index]);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function setGroupIndex($group_id, $test_id, $index)
    {
        $response = $this->Connector->putRequest('/test/' . $test_id . '/question_group/' . $group_id, [], ['order' => $index]);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getGroup($test_id, $group_id)
    {
        $response = $this->Connector->getRequest('/test/' . $test_id . '/question_group/' . $group_id, []);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getGroups($test_id)
    {

        $params['mode'] = 'list';

        $response = $this->Connector->getRequest('/test/' . $test_id . '/question_group', $params);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function moveToGroup($question_id, $group_id)
    {
        $response = $this->Connector->putRequest('/question_group/' . $group_id . '/question/' . $question_id, [], ['order' => 0]);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getGroupQuestions($test_id, $group_id)
    {

        $response = $this->Connector->getRequest('/test/' . $test_id . '/question_group/' . $group_id, []);

        debug($response);
        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        usort($response['questions'], function ($a, $b) {
            $a = $a['order'];
            $b = $b['order'];
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });

        return $response['questions'];
    }

    public function setGroupQuestionIndex($question_id, $group_id, $index)
    {
        $response = $this->Connector->putRequest('/group_question_question/' . $group_id . '/' . $question_id . '/reorder', [], ['order' => $index]);

        if ($response === false) {
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function uploadBackground($result, $question, $owner, $session)
    {


        if (isset($session['drawing_background'])) {
            $data['bg'] = new CURLFile($session['drawing_background']);

            if ($owner == 'test') {
                $response = $this->Connector->putRequestFile('/test_question/' . $result['id'], [], $data);
            } else {
                $question_id = $result['group_question_question_path'] . '/' . $result['id'];
                $response = $this->Connector->putRequestFile('/group_question_question/' . $question_id, [], $data);
            }

            if ($response === false) {
                return $this->Connector->getLastResponse();
            }
        }
    }

    private function _fillNewInfoscreenQuestion($question)
    {

        return [
            'question' => $question['question'],
            'answer' => 'niet van toepassing',
            'type' => 'InfoscreenQuestion',
            'score' => 0,
            'order' => 0,
            'subtype' => 'nvt',
            'maintain_position' => 1,
            'discuss' => 0,
            'decimal_score' => 0,
            'add_to_database' => (int) $question['add_to_database'],
            'attainments' => $question['attainments'],
            'note_type' => 'NONE',
            'is_open_source_content' => 0
        ];
    }
    
    private function _fillNewOpenQuestion($question)
    {

        return [
            'question' => $question['question'],
            'answer' => $question['answer'],
            'type' => 'OpenQuestion',
            'score' => $question['score'],
            'order' => 0,
            'subtype' => $question['subtype'],
            'maintain_position' => $question['maintain_position'],
            'discuss' => $question['discuss'],
            'decimal_score' => $question['decimal_score'],
            'add_to_database' => (int) $question['add_to_database'],
            'attainments' => $question['attainments'],
            'note_type' => $question['note_type'],
            'is_open_source_content' => $question['is_open_source_content']
        ];
    }

    private function _fillNewDrawingQuestion($question, $session)
    {

        $grid = 0;

        if (isset($session['drawing_grid'])) {
            $grid = $session['drawing_grid'];
        }

        return [
            'question' => $question['question'],
            'answer' => $session['drawing_data'],
            'type' => 'DrawingQuestion',
            'score' => $question['score'],
            'order' => 0,
            'grid' => $grid,
            'maintain_position' => $question['maintain_position'],
            'discuss' => $question['discuss'],
            'decimal_score' => $question['decimal_score'],
            'add_to_database' => (int) $question['add_to_database'],
            'attainments' => $question['attainments'],
            'note_type' => $question['note_type'],
            'is_open_source_content' => $question['is_open_source_content']
        ];
    }

    /**
     * 20190110 not like this anymore as we will parse and transform the data in the backend
     */
//    private function _fillNewCompletionQuestion($question, $subtype = 'completion') {
//
//        $processed = $this->encodeCompletionTags($question['question']);
//
//        return [
//            'question' => $processed['question'],
//            'type' => 'CompletionQuestion',
//            'score' => $question['score'],
//            'order' => 0,
//            'answers' => $processed['answers'],
//            'maintain_position' => $question['maintain_position'],
//            'subtype' => $subtype,
//            'discuss' => $question['discuss'],
//            'decimal_score' => $question['decimal_score'],
//            'add_to_database' => $question['add_to_database'],
//            'attainments' => $question['attainments'],
//            'note_type' => $question['note_type'],
//            'is_open_source_content' => $question['is_open_source_content']
//        ];
//    }

    private function _fillNewCompletionQuestion($question, $subtype = 'completion')
    {

        return [
            'question' => $question['question'],
            'type' => 'CompletionQuestion',
            'score' => $question['score'],
            'order' => 0,
//            'answers' => $processed['answers'],
            'maintain_position' => $question['maintain_position'],
            'subtype' => $subtype,
            'discuss' => $question['discuss'],
            'decimal_score' => $question['decimal_score'],
            'add_to_database' => (int) $question['add_to_database'],
            'attainments' => $question['attainments'],
            'note_type' => $question['note_type'],
            'is_open_source_content' => $question['is_open_source_content']
        ];
    }

    private function _fillNewQuestionGroup($group)
    {
        return [
            'name' => $group['name'],
            'question' => $group['text'],
            'order' => 0,
            'shuffle' => $group['shuffle'],
            'maintain_position' => $group['maintain_position'],
            'discuss' => 0,
            'add_to_database' => (int) $group['add_to_database'],
        ];
    }

    private function _fillNewTrueFalseQuestion($question)
    {
        return [
            'question' => $question['question'],
            'type' => 'MultipleChoiceQuestion',
            'order' => 0,
            'maintain_position' => $question['maintain_position'],
            'discuss' => $question['discuss'],
            'score' => $question['score'],
            'subtype' => 'TrueFalse',
            'decimal_score' => $question['decimal_score'],
            'add_to_database' => (int) $question['add_to_database'],
            'attainments' => $question['attainments'],
            'note_type' => $question['note_type'],
            'is_open_source_content' => $question['is_open_source_content']
        ];
    }

    private function _fillNewMultiChoiceQuestion($question, $subtype = 'MultipleChoice')
    {

        $score = 0;
        $selectable_answers = 0;

        for ($i = 0; $i < 10; $i++) {
            if (isset($question['answers'][$i])) {
                if (!empty($question['answers'][$i]['score'])) {
                    $score += $question['answers'][$i]['score'];
                }

                if ($question['answers'][$i]['score'] != '' && $question['answers'][$i]['score'] > 0) {
                    $selectable_answers++;
                }

                if ($question['answers'][$i]['score'] == '') {
                    $question['answers'][$i]['score'] = 0;
                }
            }
        }

        return [
            'question' => $question['question'],
            'type' => 'MultipleChoiceQuestion',
            'order' => 0,
            'maintain_position' => $question['maintain_position'],
            'discuss' => $question['discuss'],
            'score' => $score,
            'subtype' => $subtype,
            'decimal_score' => $question['decimal_score'],
            'add_to_database' => (int) $question['add_to_database'],
            'attainments' => $question['attainments'],
            'selectable_answers' => $selectable_answers,
            'note_type' => $question['note_type'],
            'is_open_source_content' => $question['is_open_source_content']
        ];
    }

    private function _fillNewRankingQuestion($question)
    {
        return [
            'type' => 'RankingQuestion',
            'score' => $question['score'],
            'question' => $question['question'],
            'order' => 0,
            'maintain_position' => $question['maintain_position'],
            'discuss' => $question['discuss'],
            'decimal_score' => $question['decimal_score'],
            'add_to_database' => (int) $question['add_to_database'],
            'attainments' => $question['attainments'],
            'note_type' => $question['note_type'],
            'is_open_source_content' => $question['is_open_source_content']
        ];
    }

    public function _fillNewMatchingQuestion($question)
    {
        return [

            'type'                   => 'MatchingQuestion',
            'score'                  => $question['score'],
            'question'               => $question['question'],
            'order'                  => 0,
            'maintain_position'      => $question['maintain_position'],
            'discuss'                => $question['discuss'],
            'subtype'                => 'Matching',
            'decimal_score'          => $question['decimal_score'],
            'add_to_database'        => (int) $question['add_to_database'],
            'attainments'            => $question['attainments'],
            'note_type'              => $question['note_type'],
            'is_open_source_content' => $question['is_open_source_content'],
            'answers'                => $question['answers'],
        ];
    }

    public function _fillNewClassifyQuestion($question)
    {
        return [
            'type'                   => 'MatchingQuestion',
            'score'                  => $question['score'],
            'question'               => $question['question'],
            'order'                  => 0,
            'maintain_position'      => $question['maintain_position'],
            'discuss'                => $question['discuss'],
            'subtype'                => 'Classify',
            'decimal_score'          => $question['decimal_score'],
            'add_to_database'        => (int) $question['add_to_database'],
            'attainments'            => $question['attainments'],
            'note_type'              => $question['note_type'],
            'is_open_source_content' => $question['is_open_source_content'],
            'answers'                => $question['answers'],
        ];
    }

    public function decodeCompletionTags($question)
    {

        $tags = [];

        foreach ($question['completion_question_answers'] as $tag) {
            $tags[$tag['tag']][] = $tag['answer'];
        }

        $searchPattern = '/\[([0-9]+)\]/i';
        $replacementFunction = function ($matches) use ($question, $tags) {
            $tag_id = $matches[1]; // the completion_question_answers list is 1 based
            if (isset($tags[$tag_id])) {
                return sprintf('[%s]', implode('|', $tags[$tag_id]));
            }
        };
        $question['question'] = preg_replace_callback($searchPattern, $replacementFunction, $question['question']);
//        foreach($tags as $tag => $answers) {
//            $question['question'] = str_replace('['.$tag.']', '['.implode('|', $answers).']', $question['question']);
//        }

        return $question;
    }

    public function encodeCompletionTags($question)
    {
        /**
         * 20190110 we don't do this anymore as we transform the question within the backend
         */
        return $question;

        $parts = explode('[', $question);

        $question = "";
        $answers = [];
        $index = 1;

        foreach ($parts as $part) {
            if (strstr($part, ']')) {
                $part = explode(']', $part);

                $answers[$index] = $part[0];
                $question .= "[" . $index . "]";
                $question .= $part[1];

                $index++;

            } else {
                $question .= $part;
            }
        }

        return [
            'question' => $question,
            'answers'  => $answers
        ];
    }
}
