<?
$answer = $rating['answer']['json'];
$answer = $answerJson = json_decode($answer, true);

$question = $rating['answer']['question'];

$useRadio = false;
$radioOptions = [];
if($question['subtype'] == 'TrueFalse' || $question['selectable_answers'] == 1){
        $useRadio = true;
}
$citoClass = '';
    if(AppHelper::isCitoQuestion($question)){
$citoClass = 'cito';
}
echo sprintf('<div class="answer_container %s">',$citoClass);

if($useRadio){
   echo $this->element('question_multiple_choice_radio_answers',['question' => $question,'rating' => true,'answerJson' => $answerJson]);
} else {
   echo $this->element('question_multiple_choice_regular_answers',['question' => $question, 'rating' => true, 'answerJson' => $answerJson]);
}

?>
</div>
<?=$this->element('question_styling',['question' => $question]);?>