<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

$question = $rating['answer']['question']['question'];

for($i = 1; $i <= count($answer); $i++) {

    $html = '<strong>' . $answer[$i] . '</strong>';
    $html .= '<span class="fa fa-question toggleOption" onclick="toggleOption(this);" style="color:orange; cursor: pointer;"></span>';

    $question = str_replace('[' . $i . ']', '<span style="color:green;">' . $html . '</span>', $question);
}

?>
<div style="font-size: 20px;">
    <?=$question?>
</div>