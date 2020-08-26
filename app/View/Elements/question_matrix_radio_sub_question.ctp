<tr>
    <?php
        echo sprintf('<td>%s</td>',$subQuestion['sub_question']);
        echo $this->element('question_matrix_radio_answers',[
            'question' => $question,
            'subQuestion' => $subQuestion,
            'default' => $default
        ]);
    ?>
</tr>

