<?= $this->element('preview_attachments2019',['questions' => $questions, 'hideExtra' => $hideExtra]);?>

<h1>ARQ</h1>

<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }
    ?>
    <?=$question['question']?><br />

    <? if($question['subtype'] != 'TrueFalse') { ?>
        <br />Selecteer maximaal <?=$question['selectable_answers']?> <?=$question['selectable_answers'] > 1 ? 'antwoorden' : 'antwoord'?><br /><br />
    <? } ?>

    <table class="table" id="tableMultiChoiceOptions">
        <thead>
        <tr>
            <th width="40">&nbsp;</th>
            <th width="40">&nbsp;</th>
            <th width="40">St. 1</th>
            <th width="40">St. 2</th>
            <th>Reden</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <?= $this->Form->input('Answer.'.$question['multiple_choice_question_answers'][0]['id'], [
                    'value' => 1,
                    'div' => false,
                    'type' => 'checkbox',

                    'label' => false,
                    'class' => 'multiple_choice_option'
                ]);?>
            </td>
            <td>A</td>
            <td>J</td>
            <td>J</td>
            <td>Juiste reden</td>
        </tr>
        <tr>
            <td>
                <?= $this->Form->input('Answer.'.$question['multiple_choice_question_answers'][1]['id'], [
                    'value' => 1,
                    'div' => false,
                    'type' => 'checkbox',

                    'label' => false,
                    'class' => 'multiple_choice_option'
                ]);?>
            </td>
            <td>B</td>
            <td>J</td>
            <td>J</td>
            <td>Onjuiste reden</td>
        </tr>
        <tr>
            <td>
                <?= $this->Form->input('Answer.'.$question['multiple_choice_question_answers'][2]['id'], [
                    'value' => 1,
                    'div' => false,
                    'type' => 'checkbox',

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

<? if($question['subtype'] == 'TrueFalse') { ?>
    <script type="text/javascript">
        $('input[type=checkbox]').click(function() {
            $('input[type=checkbox]').prop('checked' , false);
            $(this).prop('checked' , true);
        });
    </script>
<? } ?>

<? if(isset($next_question)) { ?>
    <br />
    <center>
        <a href="#" class="btn highlight large" onclick="TestPreview.loadQuestionPreview('<?=$test_id?>', '<?=$next_question?>');">
            <span class="fa fa-check"></span>
            Volgende vraag
        </a>
    </center>
<? } ?>