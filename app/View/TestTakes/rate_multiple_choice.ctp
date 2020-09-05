<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

$question = $rating['answer']['question'];

    $citoClass = '';
        if(substr_count($question['metadata'],'cito') > 0){
$citoClass = 'cito';
}
echo sprintf('<div class="answer_container %s">',$citoClass);


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
        </div>