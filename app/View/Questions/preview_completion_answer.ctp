<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }

    $question_text = $question['question'];

    foreach($question['completion_question_answers'] as $tag_id => $tag) {
        $question_text = str_replace('['.$tag['tag'].']', '<span style="color:green;">'.$tag['answer'] . '</span>', $question_text);
    }

    echo $question_text;
    ?>
</div>

<script type="text/javascript">
    $('#multiCompletionQuestion').hide();
</script>