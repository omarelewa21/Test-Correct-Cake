<?php

    function getAnswerSpan($answer){
        if($answer['id'] === $default){
            return '<i class="fa fa-circle"></i>';
        } else {
            return '<i class="fa fa-circle-o"></i>';
        }
    }

    foreach($question['matrix_question_answers'] as $answer){
        echo sprintf(''<td style="text-align:center" cellpadding="0" cellspacing="0" border="0">%s</td>',getAnswerSpan($answer));
    }
