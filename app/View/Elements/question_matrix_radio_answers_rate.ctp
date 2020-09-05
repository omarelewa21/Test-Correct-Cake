<?php

    foreach($question['matrix_question_answers'] as $answer){
        if($answer['id'] == $default){
            $icon = '<input type="radio" class="matrix_radio" disabled checked><span></span>';
        } else {
            $icon = '<input type="radio" class="matrix_radio" disabled><span></span>';
        }

        echo sprintf('<td style="text-align:center" cellpadding="0" cellspacing="0" border="0">%s</td>',$icon);
    }
