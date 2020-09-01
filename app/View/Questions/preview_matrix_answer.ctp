<?php
    $citoClass = '';
    if(AppHelper::isCitoQuestion($question)){
        $citoClass = 'cito';
    }

    echo sprintf('<div class="answer_container %s">',$citoClass);
?>
<div style="font-size: 20px;">
    <?php

    foreach($question['multiple_choice_question_answers'] as $answer) {

        echo '<div>'.$this->Form->input('Answer.'.$answer['id'], [
            'value' => 1,
            'div' => false,
            'type' => $type,
            'checked' => $answer['score'] > 0,
            'label' => false
        ]);
        echo '&nbsp;'.$answer['answer'].' [' . $answer['score'] . ' pt]</div><br />';
    }

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