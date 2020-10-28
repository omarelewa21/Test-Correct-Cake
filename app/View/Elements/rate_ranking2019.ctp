<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);
$question = $rating['answer']['question'];

asort($answer);

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
<?=$this->element('question_styling',['question' => $question]);?>
