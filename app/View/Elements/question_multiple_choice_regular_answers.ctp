<?php

    foreach( $question['multiple_choice_question_answers'] as $answer) {

        $checked = false;

        if(isset($answerJson[$answer['id']])) {
            if($answerJson[$answer['id']] == 1){
                $checked = true;
            } else {
                $checked = false;
            }
        }

        echo '<div>'.$this->Form->input('Answer.'.$answer['id'], [
            'value' => 1,
            'div' => false,
            'type' => 'checkbox',
            'checked' => $checked,
            'label' => false,
            'class' => 'multiple_choice_option input_'.$question['id'].' input_'.$answer['id'],
            'onchange' => 'checkMaxSelections(this)'
            ]).'&nbsp;'.$answer['answer'].'</div><br />';

        $first = false;
    }
