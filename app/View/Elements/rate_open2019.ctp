<?

$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

?>
<div style="font-size: 20px;">
    <?= $answer['value'] ?>
</div>
<?=$this->element('question_styling',['question' => $rating['answer']['question']]);?>