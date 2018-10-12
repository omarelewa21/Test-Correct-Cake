<div style="font-size: 20px;">
    <?
    foreach($question['multiple_choice_question_answers'] as $answer) {

        echo $this->Form->input('Answer.'.$answer['id'], [
            'value' => 1,
            'div' => false,
            'type' => 'checkbox',
            'checked' => $answer['score'] > 0,
            'label' => false
        ]);
        echo '&nbsp;'.$answer['answer'].' [' . $answer['score'] . ' pt]<br /><br />';
        $first = false;
    }
    ?>
</div>