<?php
App::uses('AppModel', 'Model');

class Question extends AppModel
{
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    public function printVersion($question) {

        $html = $question['type'];

        if($question['type'] != 'GroupQuestion') {
            switch ($question['type']) {
                case 'OpenQuestion' :
                case 'InfoscreenQuestion':
                    $html = $question['question'];
                    break;

                case 'MultipleChoiceQuestion' :
                    $html =  $question['question'] . '<br />';



                    if($question['subtype'] == 'ARQ') {
                        $html .= "<table width=100% cellpadding=10 cellspacing=0><tr><td></td><td>St.1</td><td>St.2</td><td>Reden</td></tr>";
                        $html .= "<tr><td width=40>A</td><td width=40>J</td><td width=40>J</td><td>Juiste reden</td></tr>";
                        $html .= "<tr><td>B</td><td>J</td><td>J</td><td>Onjuiste reden</td></tr>";
                        $html .= "<tr><td>C</td><td>J</td><td>O</td><td></td></tr>";
                        $html .= "<tr><td>D</td><td>O</td><td>J</td><td></td></tr>";
                        $html .= "<tr><td>E</td><td>O</td><td>O</td><td></td></tr>";
                        $html .= "</table>";
                    }else {

                        $html .= 'Selecteer maximaal ' . $question['selectable_answers'] . ' ';
                        $html .= $question['selectable_answers'] > 1 ? 'antwoorden' : 'antwoord';

                        $answers = [];

                        foreach ($question['multiple_choice_question_answers'] as $answer) {
                            $answers[] = $answer['answer'];
                        }

                        $letters = [
                            'A',
                            'B',
                            'C',
                            'D',
                            'E',
                            'F',
                            'G',
                            'H',
                            'I',
                            'J',
                            'K',
                        ];

                        $i = 0;
                        foreach ($answers as $answer) {
                            $html .= "<p>" . $letters[$i] . ": &nbsp;&nbsp;" . $answer . "</p>";
                            $i++;
                        }
                    }
                    break;

                case 'CompletionQuestion':

                    if($question['subtype'] == 'multi') {
                        $html = "<p><strong>Streep de verkeerde antwoorden door</strong></p>";
                        $questionText = $question['question'];


                        for ($i = 1; $i < 100; $i++) {

                            $answers = [];

                            foreach($question['completion_question_answers'] as $answer) {
                                if($answer['tag'] == $i) {
                                    $answers[] = $answer['answer'];
                                }
                            }

                            shuffle($answers);

                            $questionText = str_replace('[' . $i . ']', implode(' / ', $answers), $questionText);
                        }

                        $html .= "<p>" . $questionText . "</p>";
                    }else{
                        $html = "<p><strong>Noteer welke woorden op de plekken horen</strong></p>";
                        $questionText = $question['question'];
                        for ($i = 1; $i < 100; $i++) {
                            $questionText = str_replace('[' . $i . ']', ':______________', $questionText);
                        }

                        $html .= "<p>" . $questionText . "</p>";
                    }

                    break;


                case 'RankingQuestion':
                    $html = $question['question'] ;

                    $answers = [];

                    foreach($question['ranking_question_answers'] as $answer) {
                        $answers[] = $answer['answer'];
                    }

                    shuffle($answers);

                    foreach($answers as $answer) {
                        $html .= "<p>" . $answer . "</p>";
                    }

                    break;

                case 'MatchingQuestion':
                    $html = $question['question'];

                    $left = [];
                    $right = [];

                    foreach($question['matching_question_answers'] as $answer) {
                        if($answer['type'] == 'LEFT') {
                            $left[] = $answer['answer'];
                        }

                        if($answer['type'] == 'RIGHT') {
                            $right[] = $answer['answer'];
                        }
                    }

                    shuffle($left);
                    shuffle($right);

                    $html .= "<table width=100% cellpadding=0 cellspacing=0><tr><td valign=top>";

                    foreach($left as $item) {
                        $html .= $item."<br /><br />";
                    }

                    $html .= "</td><td valign=top width='50%'>";

                    foreach($right as $item) {
                        $html .= $item."<br /><br />";
                    }

                    $html .= "</tr></table>";

                    break;
                case 'DrawingQuestion':
                    $html = $question['question'];
                    break;
            }

            $question['html'] = $html;
        }

        return $question;
    }

    public function validateUpload($file) {
        $acceptedTypes = [
            'image/jpeg',
            'image/png',
            'audio/mpeg',
            'audio/mp3'
        ];

        if(!in_array($file['type'], $acceptedTypes)) {
            return 'file_type';
        }

        if($file['size'] > 1024000000) {
            return 'file_size';
        }

        return '';
    }

    public function resizeAttachment($file) {
        //$this->createThumb($file, $file, 1000)
    }

    private function createThumb($sourcePath, $thumbPath, $thumbWidth = 0, $thumbHeight = 0, $quality = 75)
    {
        $sourceExtention = strtolower(array_pop(split("[.]", $sourcePath)));

        if(strcasecmp($sourceExtention, "gif") === 0)
        {
            $sourceData = imagecreatefromgif($sourcePath);
        }
        elseif(strcasecmp($sourceExtention, "png") === 0)
        {
            $sourceData = imagecreatefrompng($sourcePath);
        }
        elseif((strcasecmp($sourceExtention, "jpg") === 0) || (strcasecmp($sourceExtention, "jpeg") === 0))
        {
            $sourceData = imagecreatefromjpeg($sourcePath);
        }
        else
        {
            return false;
        }

        if($sourceData)
        {
            $sourceWidth = imagesx($sourceData);
            $sourceHeight = imagesy($sourceData);

            if($thumbWidth > 0)
            {
                if($thumbHeight === 0)
                {
                    $thumbHeight = round($sourceHeight * ($thumbWidth / $sourceWidth));
                }
            }
            elseif($thumbHeight > 0)
            {
                $thumbWidth = round($sourceWidth * ($thumbHeight / $sourceHeight));
            }
            else // No scaling
            {
                $thumbHeight = $sourceHeight;
                $thumbWidth = $sourceWidth;
            }

            $thumbData = imagecreatetruecolor($thumbWidth, $thumbHeight);

            if(imagecopyresampled($thumbData, $sourceData, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $sourceWidth, $sourceHeight))
            {
                if(ImageJpeg($thumbData, $thumbPath, $quality))
                {
                    Imagedestroy($sourceData);
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }

            return true;
        }
    }

    public function check($type, $question, $session = null) {

        $errors = [];

        if(empty($question['question'])) {
            $errors[] = "Geen vraag ingevuld";
        }

        if($type == 'OpenQuestion') {
            if(empty($question['answer'])) {
                $errors[] = "Geen antwoord ingevuld";
            }
        }

        $questionsWithAnswerScore = ['TrueFalseQuestion','MultiChoiceQuestion','MultipleChoiceQuestion','ARQQuestion'];


        if(!in_array($type,$questionsWithAnswerScore)){
            $errors = $this->validateScore($question, $errors);
        } else {
            $answerErrors = [];
            foreach($question['answers'] as $answer){
                if($answer['answer'] !== ''){
                    if($error = $this->validateScore($answer, [])){
                        $answerErrors = $error;
                    }
                }
            }
            if(count($answerErrors) > 0) {
                $errors = array_merge($errors, $answerErrors);
            }
        }


        if($type == 'MultiChoiceQuestion' || $type == 'MultipleChoiceQuestion') {
            $found = 0;
            $scores = true;
            $scoresFound = false;

            for($i = 0; $i < 10; $i++) {
                if($question['answers'][$i]['answer'] != '') {
                    $found ++;
                }

                if($question['answers'][$i]['answer'] != '' && !is_numeric($question['answers'][$i]['score'])) {
                    $scores = false;
                }

                if(!empty($question['answers'][$i]['score'])) {
                    $scoresFound = true;
                }
            }

            if($found < 2) {
                $errors[] = "U dient minimaal 2 antwoorden op te geven";
            }elseif(!$scores) {
                $errors[] = "Bij elk antwoord dient een (numeriek) aantal punten opgegeven te zijn.";
            }

            if(!$scoresFound) {
                $errors[] = "U dient minimaal 1 antwoord van een score te voorzien.";
            }

        }

        if($type == 'ARQQuestion') {
            $scores = true;
            $found = false;

            for($i = 0; $i < 5; $i++) {
                if($question['answers'][$i]['score'] != '0') {
                    $found = true;
                }

                if($question['answers'][$i]['score'] == '' || !is_numeric($question['answers'][$i]['score'])) {
                    $scores = false;
                }
            }

            if(!$scores) {
                $errors[] = "Bij elk antwoord dient een (numeriek) aantal punten opgegeven te zijn.";
            }elseif(!$found) {
                $errors[] = "U dient minimaal 1 optie een score te geven.";
            }
        }

        if($type == 'MatchingQuestion') {
            $found = 0;
            $right = true;



            for($i = 0; $i < 10; $i++) {
                if($question['answers'][$i]['left'] != '') {
                    $found ++;
                }

                if($question['answers'][$i]['left'] != '' && $question['answers'][$i]['right'] == '') {
                    $right = false;
                }
            }

            if($found < 2) {
                $errors[] = "U dient minimaal 2 items op te geven";
            }elseif(!$right) {
                $errors[] = "Niet elk item heeft een antwoord";
            }

        }

        if($type == 'ClassifyQuestion') {
            $found = 0;
            $right = true;

            for($i = 0; $i < 10; $i++) {
                if($question['answers'][$i]['left'] != '') {
                    $found ++;
                }
            }

            if($found < 2) {
                $errors[] = "U dient minimaal 2 items op te geven";
            }

        }

        if($type == 'RankingQuestion') {
            $found = 0;
            for($i = 0; $i < 10; $i++) {
                if($question['answers'][$i]['answer'] != '') {
                    $found ++;
                }
            }

            if($found < 2) {
                $errors[] = "U dient minimaal 2 antwoorden op te geven";
            }
        }

        if($type == 'DrawingQuestion') {
            if(!isset($session['drawing_data']) || empty($session['drawing_data'])) {
                $errors[] = "U heeft nog geen antwoord getekend";
            }
        }

        if ($type == 'CompletionQuestion' || $type == 'MultiCompletionQuestion') {
            //TC-116
            //https://stackoverflow.com/a/10778067
            //Forbid the use of HTML between brackets
            preg_match_all("/\[(.*?)\]/i",$question['question'], $stringsBetweenBrackets);

            //preg_match_all gives multi-dimensional array, so a double foreach
            foreach ($stringsBetweenBrackets as $stringArray) {
                foreach($stringArray as $string) {

                    if (preg_match("/<[^<]+>/",$string) != 0) {
                        $errors[] = 'U kunt geen opmaak gebruiken tussen de vierkante haakjes';
                        break 2;
                    }

                    //TC-154
                    //empty brackets are not allowed
                    if ($string == '' || $string == null) {
                        $errors[] = 'U dient minimaal &eacute;&eacute;n antwoord tussen vierkante haakjes te plaatsen';
                        break 2;
                    }
                }
            }
        }

        if($type == 'CompletionQuestion') {
//            if(!strstr($question['question'], '[') && !strstr($question['question'], ']')) {
//                $errors[] = "U dient minimaal &eacute;&eacute;n woord tussen vierkante haakjes te plaatsen.";
//            }
        }

        if($type == 'MultiCompletionQuestion') {
            if(!strstr($question['question'], '[') && !strstr($question['question'], ']')) {
                $errors[] = "U dient minimaal &eacute;&eacute;n keuze toe te voegen aan de vraag. ";
            }
        }

        return [
            'status' => count($errors) == 0 ? true : false,
            'errors' => $errors
        ];
    }

    private function validateScore($question,$errors){

        if(!(new AppController())->is_eu_numeric($question['score'])){
            $errors[] = "De score dient numeriek te zijn";
        }
        if($question['score']==''||is_null($question['score'])){
            $errors[] = "De score is verplicht";
        }
        if(stristr($question['score'], '.')||stristr($question['score'], ',')){
                $errors[] = "De score dient in hele getallen te worden opgegeven";
        }
        if(is_numeric($question['score'])&&(intval($question['score']<0))){
            $errors[] = "De score dient groter dan nul te zijn";
        }
        return $errors;
    }

}
