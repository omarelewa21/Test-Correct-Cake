<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);

$question = $rating['answer']['question']['question'];
// NB: answers are 0 based, matches 1 based
$question = preg_replace_callback(
            '/\[([0-9]+)\]/i',
            function ($matches) use ($answer) {
                $tag_id = $matches[1]-1; // answers are 0 based
                $color = 'red';
                $nswr = ' - ';
                if(isset($answer[$tag_id])) {
                    $color = 'green';
                    $nswr = $answer[$tag_id];
                }
                $html = '<span style="color:'.$color.';">' . $nswr . '</span>';
                $html .= '<span class="fa fa-question toggleOption" onclick="toggleOption(this);" style="color:orange; cursor: pointer;"></span>';
                return $html;
            },
            $question
        );
/*
for($i = 0; $i < count($answer); $i++) {

    $html = '<span style="color:green;">' . $answer[$i] . '</span>';
    $html .= '<span class="fa fa-question toggleOption" onclick="toggleOption(this);" style="color:orange; cursor: pointer;"></span>';

    $question = str_replace('[' . ($i + 1) . ']', $html, $question);
}
*/
?>
<div style="font-size: 20px;">
    <?=$question?>
</div>
<?=$this->element('question_styling',['question' => $question]);?>