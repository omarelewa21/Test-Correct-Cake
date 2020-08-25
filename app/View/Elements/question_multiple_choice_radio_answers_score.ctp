<?php

    $radioOptions = [];
    $default = 0;
    $label = '<div class="radio_'.$question['id'].'">';
    $answersHaveImages = false;
    $radioOptionsScore = [];

    foreach( $question['multiple_choice_question_answers'] as $answer) {

        if(substr_count($answer['answer'],'<img ') > 0){
            $answersHaveImages = true;
        }

        $checked = false;

        if(isset($answerJson[$answer['id']])) {
            if($answer['score'] > 0){
                $checked = true;
                $default = $answer['id'];
            } else {
                $checked = false;
            }
        }

        $radioOptions[$answer['id']] = ' '.$answer['answer'].' [' . $answer['score'] . ' pt];
        $radioOptionsScore[$answer['id']] = ' [' . $answer['score'] . ' pt];

        echo '
            <span style="display:none">'.$this->Form->input('Answer.'.$answer['id'], [
                'value' => 1,
                'div' => false,
                'type' => 'checkbox',
                'checked' => $checked,
                'label' => false,
                'class' => 'multiple_choice_option input_'.$question['id'].' checkbox_radio_'.$answer['id'],
            ])
            .'</span>';

        $first = false;
    }

    if($answersHaveImages){
        echo $this->element('question_multiple_choice_radio_image_answers_score', ['question' => $question,'radioOptions' => $radioOptionsScore]);
    } else {
        echo $this->Form->input('Question.'.$question['id'], [
            'type' => 'radio',
            'legend'=> false,
            'label' => false,
            'div' => [], //array('class' => 'btn-group', 'data-toggle' => 'buttons'),
            'class' => 'multiple_choice_option single_choice_option input_radio_'.$question['id'],
            'default'=> $default,
            'before' => $label,//'<div class="btn btn-primary">',
                'separator' => '</div><br/>'.$label,//'</label><div class="btn btn-primary">',
                'after' => '</div>',
            'options' => $radioOptions,
            ]).'<br/>';
    }


