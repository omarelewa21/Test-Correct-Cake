<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

$question = $rating['answer']['question']['question'];

    $citoClass = '';
    if(substr_count($question['metadata'],'cito') > 0){
        $citoClass = 'cito';
    }
echo sprintf('<div class="answer_container %s">',$citoClass);
?>
<?= $this->element('discussing_completion_question_html',['question' => $question,'answer' => $answer]);?>

    </div>
<?=$this->element('question_styling',['question' => $rating['answer']['question']]);?>