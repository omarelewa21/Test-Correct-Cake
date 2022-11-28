<?php

App::uses('AppController', 'Controller');
App::uses('QuestionsService', 'Lib/Services');
App::uses('TestsService', 'Lib/Services');
App::uses('AnswersService', 'Lib/Services');
App::uses('TestTakesService', 'Lib/Services');
App::uses('File', 'Utility');

class AnswersController extends AppController
{

    public function beforeFilter()
    {
        $this->QuestionsService = new QuestionsService();
        $this->TestsService = new TestsService();
        $this->TestTakesService = new TestTakesService();
        $this->AnswersService = new AnswersService();

        parent::beforeFilter();
    }

    public function question2019($question_id)
    {
        $participant_id = $this->Session->read('participant_id');

//        $question = $this->AnswersService->getParticipantQuestion($question_id);
//        $answer = $this->AnswersService->getParticipantQuestionAnswer($question_id, $participant_id);

        $response = $this->AnswersService->getParticipantQuestionAndAnswer($question_id, $participant_id);

        $question = $response['question'];
        $answer = $response['answer'];
        $this->Session->write('answer_id', getUUID($answer, 'get'));
        $this->Session->write('question_id', $question_id);

        /**
         * @2019
         */
        $this->Session->write('question',serialize($question));

        if(empty($answer['json'])) {
            $answerJson = null;
        }else{
            $answerJson = json_decode($answer['json'], true);
        }

        $timeout = null;

        foreach($question['attachments'] as $attachment) {
            if(isset($attachment['json'])) {
                $settings = json_decode($attachment['json'], true);
                if(isset($settings['timeout']) && !empty($settings['timeout'])) {
                    $timeout = $settings['timeout'];
                }
            }
        }
        if(!empty($timeout) && $answer['time'] > 0) {
            die(__("Het is niet meer mogelijk deze vraag te beantwoorden"));
        }
        if($answer['closed'] == 1) {
            die(__("Het is niet meer mogelijk deze vraag te beantwoorden."));
        }
        if($answer['closed_group'] == 1) {
            die(__("Het is niet meer mogelijk deze vraag te beantwoorden."));
        }

        switch($question['type']) {
            case 'InfoscreenQuestion':
                $view = 'question_infoscreen';
                break;

            case 'OpenQuestion':
                $view = 'question_open';
                break;

            case 'CompletionQuestion':
                if($question['subtype'] == 'completion') {
                    $view = 'question_completion';
                }else{
                    $view = 'question_multi_completion';
                }
                break;

            case 'MatchingQuestion':
                $view = 'question_matching';
                break;

            case 'MultipleChoiceQuestion':
                if($question['subtype'] == 'multi' || $question['subtype'] == 'MultipleChoice' ||  $question['subtype'] == 'MultiChoice' || $question['subtype'] == 'TrueFalse') {
                    $view = 'question_multiple_choice';
                }else{
                    $view = 'question_arq';
                }
                break;

            case 'RankingQuestion':
                $view = 'question_ranking';
                break;

            case 'DrawingQuestion':
                $view = 'question_drawing';
                break;

            case 'MatrixQuestion':
                $view = 'question_matrix';
                break;

            default:
                echo getUUID($question, 'get');
                break;
        }


        if(isset($answer['answer_parent_questions'][0]['group_question']['attachments'])) {
            $question['attachments'] = $answer['answer_parent_questions'][0]['group_question']['attachments'];
        }

        if(isset($answer['answer_parent_questions'][0]['group_question']['question'])) {
            $question['question'] = "<p>" . $answer['answer_parent_questions'][0]['group_question']['question'] . "</p>" . $question['question'];
        }

        $this->set('has_next_question', $this->Session->read('has_next_question'));
        $this->set('answer', $answer);
        $this->set('answerJson', $answerJson);
        $this->set('question', $question);

        $this->render($view, 'ajax');
    }

    public function question($question_id)
    {
        $this->question2019($question_id);
        return;

        $participant_id = $this->Session->read('participant_id');

        $question = $this->AnswersService->getParticipantQuestion($question_id);
        $answer = $this->AnswersService->getParticipantQuestionAnswer($question_id, $participant_id);

        $this->Session->write('answer_id', getUUID($answer, 'get'));
        $this->Session->write('question_id', $question_id);

        if(empty($answer['json'])) {
            $answerJson = null;
        }else{
            $answerJson = json_decode($answer['json'], true);
        }

        $timeout = null;

        foreach($question['attachments'] as $attachment) {
            if(isset($attachment['json'])) {
                $settings = json_decode($attachment['json'], true);
                if(isset($settings['timeout']) && !empty($settings['timeout'])) {
                    $timeout = $settings['timeout'];
                }
            }
        }
        if(!empty($timeout) && $answer['time'] > 0) {
            die(__("Het is niet meer mogelijk deze vraag te beantwoorden"));
        }


        switch($question['type']) {
            case 'OpenQuestion':
                $view = 'question_open';
                break;

            case 'CompletionQuestion':
                if($question['subtype'] == 'completion') {
                    $view = 'question_completion';
                }else{
                    $view = 'question_multi_completion';
                }
                break;

            case 'MatchingQuestion':
                $view = 'question_matching';
                break;

            case 'MultipleChoiceQuestion':
                if($question['subtype'] == 'multi' || $question['subtype'] == 'MultipleChoice' ||  $question['subtype'] == 'MultiChoice' || $question['subtype'] == 'TrueFalse') {
                    $view = 'question_multiple_choice';
                }else{
                    $view = 'question_arq';
                }
                break;

            case 'RankingQuestion':
                $view = 'question_ranking';
                break;

            case 'DrawingQuestion':
                $view = 'question_drawing';
                break;

            default:
                echo getUUID($question, 'get');
                break;
        }


        if(isset($answer['answer_parent_questions'][0]['group_question']['attachments'])) {
            $question['attachments'] = $answer['answer_parent_questions'][0]['group_question']['attachments'];
        }

        if(isset($answer['answer_parent_questions'][0]['group_question']['question'])) {
            $question['question'] = "<p>" . $answer['answer_parent_questions'][0]['group_question']['question'] . "</p>" . $question['question'];
        }

        $this->set('has_next_question', $this->Session->read('has_next_question'));
        $this->set('answer', $answer);
        $this->set('answerJson', $answerJson);
        $this->set('question', $question);

        $this->render($view, 'ajax');
    }

    public function has_background($question_id) {
        $this->autoRender = false;
        $question = $this->AnswersService->getParticipantQuestion($question_id);

        if(empty($question['bg_name'])) {
            echo 0;
        }else{
            $attachmentContent = $this->AnswersService->getBackgroundContent($question_id);

            echo "data:".$question['bg_mime_type'].";base64,".base64_encode($attachmentContent);
        }
    }

    public function save2019($time, $closeAction) {

        $this->autoRender = false;

        $answer_id = $this->Session->read('answer_id');
        $question_id = $this->Session->read('question_id');
        $participant_id = $this->Session->read('participant_id');
        $take_id = $this->Session->read('take_id');

        $data = $this->request->data;

        $question = $this->Session->read('question');

        if (!in_array($closeAction, ['close_group', 'close_question'])) {
            $closeAction = false;
        }

        $data['close_action'] = $closeAction;
        $needsQuestionFromRemote = true;
        if($question && strlen($question) > 3){
            $question = unserialize($question);
            if(getUUID($question, 'get') == $question_id){
                $needsQuestionFromRemote = false;
            }
        }

        if($needsQuestionFromRemote) {
            $question = $this->AnswersService->getParticipantQuestion($question_id);
        }

//        $questions = $this->TestTakesService->getParticipantQuestions($participant_id);

        if($this->isCitoQuestion($question) && $question['type'] == 'CompletionQuestion') {
            $mask = $this->getMaskFromQuestionIfAvailable($question);
            if ($mask) {
                $mask = sprintf('/%s/i',$mask);
                if(!preg_match($mask,$data['Answer'][0])){
                    echo json_encode([
                        'error' => __("Het gegeven antwoord is niet valide"),
                    ]);
                    exit;
                }
            }
        }

        $take_question_index = $this->Session->read('take_question_index');

        $response = $this->AnswersService->saveAnswer2019($participant_id, $answer_id, $question, $data, $time, $_SESSION, $take_question_index, $take_id);

        if(!$response){
            // should return the latest error, but isn't handled in the browser
            echo json_encode([
                'status' => 'done'
            ]);

        } elseif($response && is_array($response) && isset($response['success']) && $response['success'] == true){
            echo json_encode($response);
        }else {
            $questions = $this->TestTakesService->getParticipantQuestions($participant_id);
            if (isset($questions[$take_question_index + 1])) {
                echo json_encode([
                    'status' => 'next',
                    'take_id' => $take_id,
                    'question_id' => ($take_question_index + 1),
                    'alert' => (isset($response['alert'])) ? $response['alert'] : false
                ]);
            } else {
                echo json_encode([
                    'status' => 'done',
                    'alert' => (isset($response['alert'])) ? $response['alert'] : false
                ]);
            }
        }
    }


    public function save($time, $markClosed = false) {
        return $this->save2019($time, $markClosed);

        $this->autoRender = false;

        $answer_id = $this->Session->read('answer_id');
        $question_id = $this->Session->read('question_id');
        $participant_id = $this->Session->read('participant_id');
        $take_id = $this->Session->read('take_id');

        $data = $this->request->data;

        $question = $this->AnswersService->getParticipantQuestion($question_id);
        $questions = $this->TestTakesService->getParticipantQuestions($participant_id);

        // $filecontent = $this->Session->read('drawing_data')[$question_id]['drawing'];
        // $base = explode(',',$filecontent)[1];
        // $imagecontent = base64_decode($base);
        // $PATH = realpath($_SERVER['DOCUMENT_ROOT']).'/app/tmp/drawing/test.png';
        // $res = file_put_contents($PATH, $imagecontent);
        //
        // echo (json_encode(
        //   $PATH
        // ));
        // exit();

        $this->AnswersService->saveAnswer($participant_id, $answer_id, $question, $data, $time, $_SESSION);

        $take_question_index = $this->Session->read('take_question_index');

        if(isset($questions[$take_question_index + 1])) {
            echo json_encode([
                'status' => 'next',
                'take_id' => $take_id,
                'question_id' => ($take_question_index + 1)
            ]);
        }else{
            echo json_encode([
                'status' => 'done'
            ]);
        }
    }

    public function drawing_grid($question_id) {
        $this->autoRender = false;
        $question = $this->AnswersService->getParticipantQuestion($question_id);

        if(!empty($question['grid'])) {
            echo $question['grid'];
        }else{
            echo 0;
        }
    }

    public function attachment($attachment_id) {

        $this->autoRender = false;

        $attachmentInfo = $this->AnswersService->getAttachmentInfo($attachment_id);
        $this->set('attachment', $attachmentInfo);
        $this->set('attachment_id', $attachment_id);


        $this->Session->write('attachment_id', $attachment_id);

        $extension = strtolower(substr($attachmentInfo['title'], -3));

        if($attachmentInfo['type'] == 'file') {
            if(in_array($extension, ['jpg', 'png', 'peg'])) {
                $this->render('attachment_image', 'ajax');
            }elseif(strtolower($attachmentInfo["file_mime_type"]) == 'audio/mpeg') {
                $this->render('attachment_audio', 'ajax');
            }elseif(in_array($extension, ['pdf'])) {
                ## TODO: Dit pad vervangen door een net pad
                $this->set("attachment_url", "/answers/attachment_pdf/" . $attachment_id);
                $this->set("is_question_pdf", true);

                $this->render('/Pdf/pdf_container', 'ajax');
            }else{
                die(__("Iets ging er mis ") . $extension);
            }
        }elseif($attachmentInfo['type'] == 'video') {
            $link = $this->_getVideoCode($attachmentInfo['link']);
            $this->set('video_src', $link);
            $this->render('attachment_video', 'ajax');
        }
    }

    public function attachment_pdf($attachment_id = null) {

        $attachment_id = empty($attachment_id) ? $this->Session->read('attachment_id') : $attachment_id;

        $this->autoRender = false;
        $attachmentContent = $this->AnswersService->getAttachmentContent($attachment_id);
        $this->response->type('application/pdf');
        $this->response->body($attachmentContent);
    }

    public function drawing_pad($question_id) {

        if(!empty($this->request->data)) {
            $this->Session->write('drawing_pad.'.$question_id, $this->request->data);
            echo 1;
            die;
        }

        $this->set('question_id', $question_id);
        $this->render('drawing_pad', 'ajax');
    }

    public function save_drawing($question_id) {
        $this->autoRender = false;

        $data = $this->request->data;

        $this->Session->write('drawing_data.'.$question_id, $data);

        echo 1;
    }

    public function save_drawing_pad($question_id) {
        $this->autoRender = false;

        $data = $this->request->data;

        $this->Session->write('drawing_pad.'.$question_id, $data);

        echo 1;
    }

    public function note_pad($question_id) {

        if(!empty($this->request->data['text'])) {
            $this->Session->write('note_pad.'.$question_id, $this->request->data['text']);
            echo 1;
            die;
        }

        $this->set('question_id', $question_id);
        $this->render('note_pad', 'ajax');
    }

    public function show_note($participant_id, $question_id) {
        $answer = $this->AnswersService->getParticipantQuestionAnswer($question_id, $participant_id, true);
        $this->set('answer', $answer);
    }

    public function drawing_answer($question_id) {
        $this->autoRender = false;
        $question = $this->AnswersService->getParticipantQuestion($question_id);

        if($this->Session->check('drawing_data.'.$question_id)) {
            $drawing_data = $this->Session->read('drawing_data.'.$question_id);

            $this->set('drawing_data', $drawing_data);
        }

        $this->set('question_id', $question_id);
        $this->render('drawing_answer', 'ajax');
    }

    public function drawing_answer_canvas() {

        $question_id = $this->Session->read('question_id');
        $this->set('question_id', $question_id);
        $this->render('drawing_answer_canvas', 'ajax');
    }

    public function download_attachment($attachment_id) {
        $this->autoRender = false;
        $attachmentInfo = $this->AnswersService->getAttachmentInfo($attachment_id);
        $attachmentContent = $this->AnswersService->getAttachmentContent($attachment_id);
        $this->response->type($attachmentInfo['file_mime_type']);
        $this->response->body($attachmentContent);
    }

    // Er is een verdraaid lastig probleem met audio files , HTML5 en IOS. Als die namelijk
    // partial content opvraagt gebeurt dat zonder sessie cookie, en dus mogen ze er niet langs
    // van cake.
    // De oplossing nu is om een base64 string te sturen als text en deze als "data uri" op
    // de pagina te zetten en af te spelen.
    public function download_attachment_sound($attachment_id) {
        $attachmentContent = $this->AnswersService->getAttachmentContent($attachment_id);
        $this->autoRender = false;
        $this->response->type("text/plain");
        $this->response->body( base64_encode ($attachmentContent));
    }

    private function _getVideoCode($subject) {
        $youtubeRegex = "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))(?<video_id>[^\?&\"'>]+)/";
        $vimeoRegex = "/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*(?<video_id>[0-9]{6,11})[?]?.*/";

        preg_match($youtubeRegex, $subject, $matches);
        if(!empty($matches['video_id'])){
            $parts = parse_url($subject);
            parse_str($parts['query'],$query);
            $t = 0;
            switch(true) {
                case isset($query['t']):
                    $t = $query['t'];
                    break;
                case isset($query['start']):
                    $t = $query['start'];
                    break;
            }
            return sprintf('https://www.youtube.com/embed/%s?rel=0&start=%d',$matches['video_id'],$t);
        }

        preg_match($vimeoRegex, $subject, $matches);
        if(!empty($matches['video_id'])){
            return 'https://player.vimeo.com/video/'.$matches['video_id'];
        }

        return false;
    }

    public function clear() {
        $this->autoRender = false;
    }

    public function is_allowed_inbrowser_testing($test_take_id) {
        echo json_encode(['response' => $this->AnswersService->is_allowed_inbrowser_testing($test_take_id)]);
        die;
    }

    public function is_taking_inbrowser_test() {
        // we assume for now that the test is being taken so all we need to check is if there's a header available
        // if the TLCVersion session variable === 'x' then we are in the browser
        $inBrowserTesting = false;
        if($this->Session->check('TLCVersion') && $this->Session->read('TLCVersion') === 'x'){
            $inBrowserTesting = true;
        }
        echo json_encode(['response' => $inBrowserTesting]);
        die;
    }
}
