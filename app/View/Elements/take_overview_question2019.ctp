<? if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
echo '<p>'. $question['question_group']['text'].'</p>';
}
?>
<?
if(in_array($question['type'],['CompletionQuestion'])){
$question['question'] = preg_replace_callback(
            '/\[([0-9]+)\]/i',
            function ($matches) use ($answer) {
                    return '...';
            },
            $question['question']
        );
}
echo $question['question'];
?>