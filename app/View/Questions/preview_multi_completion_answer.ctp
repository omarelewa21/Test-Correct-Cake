
<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }

    $question_text = $question['question'];

    $question_text = preg_replace_callback(
        '/\[(.*?)\]/i',
        function ($matches) use ($count) {
            $count->nr++;
            $answers = explode("|",$matches[1]);
            return '<span style="color:green;">'.$answers[0] .'</span>';
        },
        $question_text
    );

//    $tags = [];

//    foreach($question['completion_question_answers'] as $tag) {
//        if($tag['correct'] == 1) {
//            $tags[$tag['tag']] = $tag['answer'];
//        }
//    }

//    foreach($tags as $tag_id => $answer) {
//        $question_text = str_replace('['.$tag_id.']', '<span style="color:green;">'.$answer.'</span>', $question_text);
//    }

    echo $question_text;
    ?>
</div>

<script type="text/javascript">
    $('#blockDiscussionQuestion').hide();
</script>