<?php
    $answerOptions = [];
    foreach($question['matrix_question_answers'] as $answer) {
        $answerOptions[$answer['id']] = '';
    }

    $label = '<td style="text-align:center" cellpadding="0" cellspacing="0" border="0">';

    echo $this->Form->input('Answer.'.$subQuestion['id'], [
        'type' => 'radio',
        'legend'=> false,
        'label' => false,
        'div' => [], //array('class' => 'btn-group', 'data-toggle' => 'buttons'),
        'class' => 'matrix_choice_option single_choice_option input_radio_'.$question['id'],
        'default'=> $default,
        'before' => $label,
        'separator' => '</td>'.$label,
        'after' => '</td>',
        'options' => $answerOptions,
    ]);