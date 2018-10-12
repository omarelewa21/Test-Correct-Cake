<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

$question = $rating['answer']['question']['question'];

for($i = 0; $i < count($answer); $i++) {

    $html = '<span style="color:green;">' . $answer[$i] . '</span>';
    $html .= '<span class="fa fa-question toggleOption" onclick="toggleOption(this);" style="color:orange; cursor: pointer;"></span>';

    $question = str_replace('[' . ($i + 1) . ']', $html, $question);
}

?>
<div style="font-size: 20px;">
    <?=$question?>
</div>