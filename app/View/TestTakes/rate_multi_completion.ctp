<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

$question = $rating['answer']['question']['question'];

    $citoClass = '';
        if(substr_count($question['metadata'],'cito') > 0){
$citoClass = 'cito';
}
echo sprintf('<div class="answer_container %s">',$citoClass);

for($i = 1; $i <= count($answer); $i++) {

    $html = '<strong>' . $answer[$i] . '</strong>';
    $html .= '<span class="fa fa-question toggleOption" onclick="toggleOption(this);" style="color:orange; cursor: pointer;"></span>';

    $question = str_replace('[' . $i . ']', '<span style="color:green;">' . $html . '</span>', $question);
}

?>
<div style="font-size: 20px;">
    <?=$question?>
</div>
</div>
<?=$this->element('question_styling',['question' => $question]);?>