<?php

    foreach( $question['multiple_choice_question_answers'] as $answer) {

        $checked = false;

        if(isset($answerJson[getUUID($answer, 'get')])) {
            if($answer['score'] > 0){
                $checked = true;
            } else {
                $checked = false;
            }
        }

        echo '<div>'.$this->Form->input('Answer.'.getUUID($answer, 'get'), [
            'value' => 1,
            'div' => false,
            'type' => 'checkbox',
            'checked' => $checked,
            'label' => false,
            'class' => 'multiple_choice_option input_'.getUUID($question, 'get').' input_'.getUUID($answer, 'get'),
            'onchange' => 'checkMaxSelections(this)'
            ]).'&nbsp;'.$answer['answer'].' [' . $answer['score'] . ' pt]</div><br />';

    }
