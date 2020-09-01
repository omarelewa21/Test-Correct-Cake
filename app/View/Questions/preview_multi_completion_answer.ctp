
<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }

    $question_text = $question['question'];

    $tags = [];

    foreach($question['completion_question_answers'] as $tag) {
        if($tag['correct'] == 1) {
            $tags[$tag['tag']] = $tag['answer'];
        }
    }

    $question_text = preg_replace_callback(
        '/\[([0-9]+)\]/i',
        function ($matches) use ($tags) {
            if(isset($tags[$matches[1]])){
                return '<span style="color:green;">'.$tags[$matches[1]] .'</span>';
            }
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

    <?php
    $citoClass = '';
    if(AppHelper::isCitoQuestion($question)){
        $citoClass = 'cito';
    }

    echo sprintf('<div class="answer_container %s">',$citoClass);

    echo $question_text;
    ?>
    </div>
</div>

<script type="text/javascript">
    //$('#blockDiscussionQuestion').hide();
</script>