<?=$this->element('take_attachments', ['question' => $question]);?>

<?=$this->Form->create('Answer')?>
<?php
    $citoClass = '';
    if(substr_count($question['metadata'],'cito') > 0){
$citoClass = 'cito';
}
?>
<h1 class="question_type <?=$citoClass?>">ARQ [<?=$question['score']?>pt]<?=AppHelper::showExternalId($question);?></h1>

<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }

    $checked1 = isset($answerJson[$question['multiple_choice_question_answers'][0]['id']]) && $answerJson[$question['multiple_choice_question_answers'][0]['id']] == 1;
    $checked2 = isset($answerJson[$question['multiple_choice_question_answers'][1]['id']]) && $answerJson[$question['multiple_choice_question_answers'][1]['id']] == 1;
    $checked3 = isset($answerJson[$question['multiple_choice_question_answers'][2]['id']]) && $answerJson[$question['multiple_choice_question_answers'][2]['id']] == 1;
    $checked4 = isset($answerJson[$question['multiple_choice_question_answers'][3]['id']]) && $answerJson[$question['multiple_choice_question_answers'][3]['id']] == 1;
    $checked5 = isset($answerJson[$question['multiple_choice_question_answers'][4]['id']]) && $answerJson[$question['multiple_choice_question_answers'][4]['id']] == 1;


    ?>
    <?=$question['question']?><br />
    <?php

    echo sprintf('<div class="answer_container %s">',$citoClass);
    ?>

        <table class="table" id="tableMultiChoiceOptions">
        <thead>
        <tr>
            <th width="40">&nbsp;</th>
            <th width="40">&nbsp;</th>
            <th width="40">St. 1</th>
            <th width="40">St. 2</th>
            <th><?= __("Reden")?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <?= $this->Form->input('Answer.'.$question['multiple_choice_question_answers'][0]['id'], [
        'value' => 1,
        'div' => false,
        'type' => 'checkbox',
        'checked' => $checked1,
        'label' => false,
        'class' => 'multiple_choice_option'
    ]);?>
    </td>
    <td>A</td>
    <td>J</td>
    <td>J</td>
    <td><?= __("Juiste reden")?></td>
    </tr>
    <tr>
        <td>
            <?= $this->Form->input('Answer.'.$question['multiple_choice_question_answers'][1]['id'], [
                'value' => 1,
                'div' => false,
                'type' => 'checkbox',
                'checked' => $checked2,
                'label' => false,
                'class' => 'multiple_choice_option'
            ]);?>
        </td>
        <td>B</td>
        <td>J</td>
        <td>J</td>
        <td><?= __("Onjuiste reden")?></td>
    </tr>
    <tr>
        <td>
            <?= $this->Form->input('Answer.'.$question['multiple_choice_question_answers'][2]['id'], [
                'value' => 1,
                'div' => false,
                'type' => 'checkbox',
                'checked' => $checked3,
                'label' => false,
                'class' => 'multiple_choice_option'
            ]);?>
        </td>
        <td>C</td>
        <td>J</td>
        <td>O</td>
        <td>-</td>
    </tr>
    <tr>
        <td>
            <?= $this->Form->input('Answer.'.$question['multiple_choice_question_answers'][3]['id'], [
                'value' => 1,
                'div' => false,
                'type' => 'checkbox',
                'checked' => $checked4,
                'label' => false,
                'class' => 'multiple_choice_option'
            ]);?>
        </td>
        <td>D</td>
        <td>O</td>
        <td>J</td>
        <td>-</td>
    </tr>
    <tr>
        <td>
            <?= $this->Form->input('Answer.'.$question['multiple_choice_question_answers'][4]['id'], [
                'value' => 1,
                'div' => false,
                'type' => 'checkbox',
                'checked' => $checked5,
                'label' => false,
                'class' => 'multiple_choice_option'
            ]);?>
        </td>
        <td>E</td>
        <td>O</td>
        <td>O</td>
        <td>-</td>
    </tr>
    </tbody>
    </table>
</div>
</div>

<?=$this->Form->end();?>
<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<script type="text/javascript">
    $('input[type=checkbox]').click(function() {
        $('input[type=checkbox]').prop('checked' , false);
        $(this).prop('checked' , true);
        Answer.answerChanged = true;
    });

    Answer.answerChanged = false;
</script>
<?=$this->element('question_styling',['question' => $question]);?>