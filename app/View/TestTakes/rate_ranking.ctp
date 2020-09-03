<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);
$question = $rating['answer']['question'];

asort($answer);

    $citoClass = '';
        if(substr_count($question['metadata'],'cito') > 0){
$citoClass = 'cito';
}
echo sprintf('<div class="answer_container %s">',$citoClass);


foreach($answer as $answer_id => $ranking) {
    foreach($question['ranking_question_answers'] as $option) {
        if($option['id'] == $answer_id) {
            ?>
            <div style="background: grey; padding:10px; margin: 2px;">
                <?= $option['answer'] ?>
            </div>
            <?
        }
    }
}

?>
</div>
<?=$this->element('question_styling',['question' => $question]);?>