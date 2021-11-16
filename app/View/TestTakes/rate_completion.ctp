<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

$question = $rating['answer']['question']['question'];
?>
<div style="font-size: 20px;">
    <?= $this->element('discussing_completion_question_html',['question' => $question,'answer' => $answer, 'zeroBased' => true]);?>
</div>
<?=$this->element('question_styling',['question' => $rating['answer']['question']]);?>