<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

$question = $rating['answer']['question']['question'];
// NB: answers are 0 based, matches 1 based
$question = HelperFunctions::getInstance()->getDiscussingCompletionQuestionHtml($question,$answer);
?>
<div style="font-size: 20px;">
    <?= $this->element('discussing_completion_question_html',['question' => $question]);?>
</div>
<?=$this->element('question_styling',['question' => $rating['answer']['question']]);?>