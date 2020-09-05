<?php

    $radioOptions = [];
    $default = 0;
    $label = '<div class="radio_'.getUUID($question, 'get').'">';
    $answersHaveImages = false;
    foreach( $question['multiple_choice_question_answers'] as $answer) {

        if(substr_count($answer['answer'],'<img ') > 0){
            $answersHaveImages = true;
        }
    }

    $rating = (isset($rating)) ? $rating : false;

    foreach( $question['multiple_choice_question_answers'] as $answer) {

        $checked = false;

            if(isset($answerJson[getUUID($answer, 'get')])) {
                if($answerJson[getUUID($answer, 'get')] == 1){
                    $checked = true;
                    $default = getUUID($answer, 'get');
                } else {
                    $checked = false;
                }
            }

            $radioOptions[getUUID($answer, 'get')] = sprintf('<span> %s</span>',$answer['answer']);
            if(!$rating){
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
            }

            $first = false;

    }

    if($answersHaveImages){
        echo $this->element('question_multiple_choice_radio_image_answers', ['question' => $question,'radioOptions' => $radioOptions, 'rating' => $rating,'default' => $default]);
    } else {
        echo $this->Form->input('Question.'.getUUID($question, 'get'), [
            'type' => 'radio',
            'legend'=> false,
            'label' => false,
            'disabled' => $rating,
            'div' => [], //array('class' => 'btn-group', 'data-toggle' => 'buttons'),
            'class' => 'multiple_choice_option multiple_choice_option_radio single_choice_option input_radio_'.getUUID($question, 'get'),
            'default'=> $default,
            'before' => $label,//'<div class="btn btn-primary">',
                'separator' => '</div><br/>'.$label,//'</label><div class="btn btn-primary">',
                'after' => '</div>',
            'options' => $radioOptions,
            ]).'<br/>';
    }


