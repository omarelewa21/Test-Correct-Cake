<? if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
echo '<p>'. $question['question_group']['text'].'</p>';
}
?>
<?
if(!in_array($question['type'],['CompletionQuestion'])){
    echo $question['question'];
}
?>