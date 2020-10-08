<?php

    $radioOptions = [];
    $default = 0;
    $label = '<div class="radio_'.$question['id'].'">';
    $answersHaveImages = false;
    foreach( $question['multiple_choice_question_answers'] as $answer) {

        if(substr_count($answer['answer'],'<img ') > 0){
            $answersHaveImages = true;
        }
    }

    $rating = (isset($rating)) ? $rating : false;

    foreach( $question['multiple_choice_question_answers'] as $answer) {

        $checked = false;

            if(isset($answerJson[$answer['id']])) {
                if($answerJson[$answer['id']] == 1){
                    $checked = true;
                    $default = $answer['id'];
                } else {
                    $checked = false;
                }
            }

            $radioOptions[$answer['id']] = sprintf('<span> %s</span>',$answer['answer']);
            if(!$rating){
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
            }

            $first = false;

    }

    if($answersHaveImages){
        echo $this->element('question_multiple_choice_radio_image_answers', ['question' => $question,'radioOptions' => $radioOptions, 'rating' => $rating,'default' => $default]);
    } else {
        $random = '';
        if($rating){
            // we need a random string here as with rating by a teacher and per question, every student has the same name attribute
            // 'Question.'.$question['id']
            // and if that is the case the radio buttons interact and get broken
            $length = 10;
            $random = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
        }
        echo $this->Form->input('Question.'.$question['id'].$random, [
            'type' => 'radio',
            'legend'=> false,
            'label' => false,
            'disabled' => $rating,
            'div' => [], //array('class' => 'btn-group', 'data-toggle' => 'buttons'),
            'class' => 'multiple_choice_option multiple_choice_option_radio single_choice_option input_radio_'.$question['id'],
            'default'=> $default,
            'before' => $label,//'<div class="btn btn-primary">',
                'separator' => '</div><br/>'.$label,//'</label><div class="btn btn-primary">',
                'after' => '</div>',
            'options' => $radioOptions,
            'value' => $default,
            ]).'<br/>';
    }


