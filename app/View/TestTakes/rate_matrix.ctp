<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

$question = $rating['answer']['question'];

$answerSubQuestionReference = $answer;

    $citoClass = '';
    if(AppHelper::isCitoQuestion($question)){
        $citoClass = 'cito';
    }
    echo sprintf('<div class="answer_container %s">',$citoClass);

echo $this->element('question_matrix',[
'question' => $question,
'answerSubQuestionReference' => $answerSubQuestionReference,
'rating' => true
]);

?>
</div>
<?=$this->element('question_styling',['question' => $question]);?>