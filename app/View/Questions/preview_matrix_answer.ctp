<?php
    $citoClass = '';
    if(AppHelper::isCitoQuestion($question)){
        $citoClass = 'cito';
    }

    echo sprintf('<div class="answer_container %s">',$citoClass);
?>
<div style="font-size: 20px;">
    <?php

    $answerSubQuestionReference = [];
    foreach($question['matrix_question_answer_sub_questions'] as $a){
        $answerSubQuestionReference[$a['matrix_question_sub_question_id']] = $a['matrix_question_answer_id'];
    }

    echo $this->element('question_matrix',[
    'question' => $question,
    'answerSubQuestionReference' => $answerSubQuestionReference
    ]);

    ?>
</div>
</div>