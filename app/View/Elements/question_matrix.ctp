<table class="matrix" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th></th>
            <?php
                foreach($question['matrix_question_answers'] as $answer) {
                   echo sprintf('<th style="text-align:center">%s</th>',$answer['answer']);
                }
            ?>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($question['matrix_question_sub_questions'] as $subQuestion) {
            echo $this->element('question_matrix_radio_sub_question',[
                'question' => $question,
                'subQuestion' => $subQuestion,
                'default' => isset($answerSubQuestionReference[getUUID($subQuestion, 'get')]) ? $answerSubQuestionReference[getUUID($subQuestion, 'get')] : -1,
                'rating' => isset($rating) ? $rating : false
                ]);
        }
    ?>
    </tbody>
</table>
<?=$this->element('question_styling',['question' => $question]);?>