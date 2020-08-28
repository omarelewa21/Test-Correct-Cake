<?php

    foreach($question['matrix_question_answers'] as $answer){
        if($answer['id'] == $default){
            $icon = '<i class="fa fa-check-square-o"></i>';
        } else {
            $icon = '<i class="fa fa-square-o"></i>';
        }



        echo sprintf('<td style="text-align:center" cellpadding="0" cellspacing="0" border="0">%s</td>',$icon);
    }
