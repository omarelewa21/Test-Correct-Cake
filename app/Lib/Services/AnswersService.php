<?php

App::uses('BaseService', 'Lib/Services');

/**
 * Class QuestionsService
 *
 *
 */
class AnswersService extends BaseService {
    public function getParticipantQuestion($question_id) {
        $response = $this->Connector->getRequest('/question/' . $question_id, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getParticipantQuestionAnswer($question_id, $participant_id, $ratings = false) {

        $params['filter'] = ['question_id' => $question_id];
        $params['mode'] = 'all';

        if($ratings) {
            $params['with'] = ['answer_ratings'];
        }else{
            $params['with'] = ['question'];
        }

        $response = $this->Connector->getRequest('/test_participant/' . $participant_id . '/answer', $params);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return isset($response[0]) ? $response[0] : false;
    }

    public function getParticipantQuestionAnswers($participant_id) {
        $params['mode'] = 'all';

        $response = $this->Connector->getRequest('/test_participant/' . $participant_id . '/answer', $params);


        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function saveAnswer2019($participant_id, $answer_id, $question, $data, $time, $session, $take_question_index, $take_id) {

        switch($question['type']) {
            case 'OpenQuestion':
                $data = $this->_fillOpenQuestion($question, $data);
                break;

            case 'CompletionQuestion':
                $data = $data['Answer'];
                break;

            case 'MatchingQuestion':
                $data = $data['Answer'];
                break;

            case 'MultipleChoiceQuestion':
                $data = $data['Answer'];
                break;

            case 'RankingQuestion':
                $data = $data['Answer'];
                break;

            case 'DrawingQuestion':

                $data['answer'] = isset($session['drawing_data'][$question['id']]) ? $session['drawing_data'][$question['id']]['drawing'] : '';

                if(!empty($data['answer'])) {

                    if(!defined('DS')){
                        define('DS',DIRECTORY_SEPARATOR);
                    }

                    $base = explode(',',$data['answer'])[1];
                    $format = explode(',',$data['answer'])[0];

                    if (stripos($format,'jpg') > 0) {
                        $ext = '.jpg';
                    }elseif (stripos($format,'jpeg') > 0) {
                        $ext = '.jpeg';
                    }elseif (stripos($format,'png') > 0) {
                        $ext = '.png';
                    }else{
                        $ext = '.png';
                    }

                    $imagecontent = base64_decode($base);
                    $PATH = realpath($_SERVER['DOCUMENT_ROOT']).DS.'..'.DS.'tmp'.DS.'drawing'.DS;
                    $filename = time().$ext;
                    $res = file_put_contents($PATH.$filename, $imagecontent);
                    $src = $_SERVER['HTTP_ORIGIN'].DS.'custom'.DS.'imageload.php?filename='.$filename.'&type=drawing';
                    $data['answer'] = $src;
                }

                $data['additional_text'] = isset($session['drawing_data'][$question['id']]) ? $session['drawing_data'][$question['id']]['additional_text'] : '';
                break;
        }

        // $data['value'] = preg_replace('!\\r?\\n!', "<br/>", $data['value']);

        $data = [
            'json' => json_encode($data, JSON_FORCE_OBJECT),
            'add_time' => $time,
            'question_id' => $question['id'],
            'take_question_index' => $take_question_index,
            'take_id' => $take_id,
        ];

        //$json = preg_replace('!\\r?\\n!', "", $json);

        if(isset($session['drawing_pad'][$question['id']])) {
            $data['note'] = $session['drawing_pad'][$question['id']];
        }

        if(isset($session['note_pad'][$question['id']])) {
            $data['note'] = $session['note_pad'][$question['id']];
        }

        $response = $this->Connector->putRequest('/test_participant/' . $participant_id . '/answer2019/' . $answer_id, [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        } else {
            return $response;
        }
    }

    public function saveAnswer($participant_id, $answer_id, $question, $data, $time, $session) {

        switch($question['type']) {
            case 'OpenQuestion':
                $data = $this->_fillOpenQuestion($question, $data);
                break;

            case 'CompletionQuestion':
                $data = $data['Answer'];
                break;

            case 'MatchingQuestion':
                $data = $data['Answer'];
                break;

            case 'MultipleChoiceQuestion':
                $data = $data['Answer'];
                break;

            case 'RankingQuestion':
                $data = $data['Answer'];
                break;

            case 'DrawingQuestion':

                $data['answer'] = isset($session['drawing_data'][$question['id']]) ? $session['drawing_data'][$question['id']]['drawing'] : '';

                if(!empty($data['answer'])) {

                  if(!defined('DS')){
                    define('DS',DIRECTORY_SEPARATOR);
                  }

                  $base = explode(',',$data['answer'])[1];
                  $format = explode(',',$data['answer'])[0];

                  if (stripos($format,'jpg') > 0) {
                    $ext = '.jpg';
                  }elseif (stripos($format,'jpeg') > 0) {
                    $ext = '.jpeg';
                  }elseif (stripos($format,'png') > 0) {
                    $ext = '.png';
                  }else{
                    $ext = '.png';
                  }

                  $imagecontent = base64_decode($base);
                  $PATH = realpath($_SERVER['DOCUMENT_ROOT']).DS.'..'.DS.'tmp'.DS.'drawing'.DS;
                  $filename = time().$ext;
                  $res = file_put_contents($PATH.$filename, $imagecontent);
                  $src = $_SERVER['HTTP_ORIGIN'].DS.'custom'.DS.'imageload.php?filename='.$filename.'&type=drawing';
                  $data['answer'] = $src;
                }

                $data['additional_text'] = isset($session['drawing_data'][$question['id']]) ? $session['drawing_data'][$question['id']]['additional_text'] : '';
                break;
        }

        // $data['value'] = preg_replace('!\\r?\\n!', "<br/>", $data['value']);

        $data = [
            'json' => json_encode($data, JSON_FORCE_OBJECT),
            'add_time' => $time
        ];

        //$json = preg_replace('!\\r?\\n!', "", $json);

        if(isset($session['drawing_pad'][$question['id']])) {
            $data['note'] = $session['drawing_pad'][$question['id']];
        }

        if(isset($session['note_pad'][$question['id']])) {
            $data['note'] = $session['note_pad'][$question['id']];
        }

        $response = $this->Connector->putRequest('/test_participant/' . $participant_id . '/answer/' . $answer_id, [], $data);

        if($response === false){
            return $this->Connector->getLastResponse();
        }
    }

    public function getAttachmentInfo($attachment_id) {
        $response = $this->Connector->getRequest('/attachment/' . $attachment_id, []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getAttachmentContent($attachment_id) {
        $response = $this->Connector->getDownloadRequest('/attachment/' . $attachment_id . '/download', []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    public function getBackgroundContent($question_id) {
        $response = $this->Connector->getDownloadRequest('/question/' . $question_id . '/bg', []);

        if($response === false){
            return $this->Connector->getLastResponse();
        }

        return $response;
    }

    private function _fillOpenQuestion($question, $data) {
        return ['value' => $data['Answer']['answer']];
    }

    public function prepareQuestionCompletion($question, $answerJson) {

    }
}
