<tr>
    <?php
        echo sprintf('<td>%s</td>',$subQuestion['sub_question']);
        if(isset($rating) && $rating === true){
            echo $this->element('question_matrix_radio_answers_rate',[
                'question' => $question,
                'subQuestion' => $subQuestion,
                'default' => $default,
            ]);
        } else {
            echo $this->element('question_matrix_radio_answers',[
                'question' => $question,
                'subQuestion' => $subQuestion,
                'default' => $default,
            ]);
        }
    ?>
</tr>

