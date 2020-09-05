<?php

    $radioOptions = [];
    $default = 0;
    $label = '<div class="radio_'.getUUID($question, 'get').'">';
    $answersHaveImages = false;
    $radioOptionsScore = [];

    foreach( $question['multiple_choice_question_answers'] as $answer) {

        if(substr_count($answer['answer'],'<img ') > 0){
            $answersHaveImages = true;
        }

        $checked = false;

        if(isset($answerJson[getUUID($answer, 'get')])) {
            if($answer['score'] > 0){
                $checked = true;
                $default = getUUID($answer, 'get');
            } else {
                $checked = false;
            }
        }

        $radioOptions[getUUID($answer, 'get')] = ' '.$answer['answer'].' [' . $answer['score'] . ' pt]';
        $radioOptionsScore[getUUID($answer, 'get')] = ' [' . $answer['score'] . ' pt]';

        echo '
            <span style="display:none">'.$this->Form->input('Answer.'.getUUID($answer, 'get'), [
                'value' => 1,
                'div' => false,
                'type' => 'checkbox',
                'checked' => $checked,
                'label' => false,
                'class' => 'multiple_choice_option input_'.getUUID($question, 'get').' checkbox_radio_'.getUUID($answer, 'get'),
            ])
            .'</span>';

        $first = false;
    }

    if($answersHaveImages){
        echo $this->element('question_multiple_choice_radio_image_answers_score', ['question' => $question,'radioOptions' => $radioOptionsScore]);
    } else {
        echo $this->Form->input('Question.'.getUUID($question, 'get'), [
            'type' => 'radio',
            'legend'=> false,
            'label' => false,
            'div' => [], //array('class' => 'btn-group', 'data-toggle' => 'buttons'),
            'class' => 'multiple_choice_option single_choice_option input_radio_'.getUUID($question, 'get'),
            'default'=> $default,
            'before' => $label,//'<div class="btn btn-primary">',
                'separator' => '</div><br/>'.$label,//'</label><div class="btn btn-primary">',
                'after' => '</div>',
            'options' => $radioOptions,
            ]).'<br/>';
    }


