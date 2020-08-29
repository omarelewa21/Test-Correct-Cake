<?php
    $citoClass = '';
    if(AppHelper::isCitoQuestion($question)){
        $citoClass = 'cito';
    }

    echo sprintf('<div class="answer_container %s">',$citoClass);
?>
<div style="font-size: 20px;">
    <?
    $type = 'checkbox';
    if($question['subtype'] == 'TrueFalse' || $question['selectable_answers'] == 1){
        $type = 'radio';
    }

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
    ?>
</div>
</div>