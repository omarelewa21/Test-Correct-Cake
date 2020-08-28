<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

$question = $rating['answer']['question'];

$answerSubQuestionReference = $answer;

        echo $this->element('question_matrix',[
'question' => $question,
'answerSubQuestionReference' => $answerSubQuestionReference,
'rating' => true
]);

foreach($question['multiple_choice_question_answers'] as $option) {
    if($answer[$option['id']] == 0) {
        ?>
        <div><span class="fa fa-square-o"></span>
        <?
    }else{
        ?>
        <div><span class="fa fa-check-square-o"></span>
        <?
    }

    echo $option['answer'] . '</div><br />';
}
?>