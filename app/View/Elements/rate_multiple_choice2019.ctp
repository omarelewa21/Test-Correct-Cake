<?
$answer = $rating['answer']['json'];
$answer = $answerJson = json_decode($answer, true);

$question = $rating['answer']['question'];

$useRadio = false;
$radioOptions = [];
if($question['subtype'] == 'TrueFalse' || $question['selectable_answers'] == 1){
        $useRadio = true;
}

if($useRadio){
   echo $this->element('question_multiple_choice_radio_answers',['question' => $question,'rating' => true,'answerJson' => $answerJson]);
} else {
   echo $this->element('question_multiple_choice_regular_answers',['question' => $question, 'rating' => true, 'answerJson' => $answerJson]);
}
