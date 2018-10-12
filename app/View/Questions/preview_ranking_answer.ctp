<?
$answers = $question['ranking_question_answers'];

foreach($answers as $answer) {
    ?>
    <div style="padding:10px; margin-bottom: 2px; background: grey;" class="ranking_answer">
        <?=$answer['answer']?>
    </div>
<?
}
?>