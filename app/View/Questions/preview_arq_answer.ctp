<?php
    $citoClass = '';
    if(AppHelper::isCitoQuestion($question)){
        $citoClass = 'cito';
    }

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
                        'checked' => $question['multiple_choice_question_answers'][0]['score'] > 0,
                        'label' => false,
                        'class' => 'multiple_choice_option'
                    ]);?>
                </td>
                <td>A</td>
                <td><?= __('J') ?></td>
                <td><?= __('J') ?></td>
                <td><?= __("Juiste reden")?></td>
            </tr>
            <tr>
                <td>
                    <?= $this->Form->input('Answer.'.$question['multiple_choice_question_answers'][1]['id'], [
                        'value' => 1,
                        'div' => false,
                        'type' => 'checkbox',
                        'checked' => $question['multiple_choice_question_answers'][1]['score'] > 0,
                        'label' => false,
                        'class' => 'multiple_choice_option'
                    ]);?>
                </td>
                <td>B</td>
                <td><?= __('J') ?></td>
                <td><?= __('J') ?></td>
                <td><?= __("Onjuiste reden")?></td>
            </tr>
            <tr>
                <td>
                    <?= $this->Form->input('Answer.'.$question['multiple_choice_question_answers'][2]['id'], [
                        'value' => 1,
                        'div' => false,
                        'type' => 'checkbox',
                        'checked' => $question['multiple_choice_question_answers'][2]['score'] > 0,
                        'label' => false,
                        'class' => 'multiple_choice_option'
                    ]);?>
                </td>
                <td>C</td>
                <td><?= __('J') ?></td>
                <td><?= __('O') ?></td>
                <td>-</td>
            </tr>
            <tr>
                <td>
                    <?= $this->Form->input('Answer.'.$question['multiple_choice_question_answers'][3]['id'], [
                        'value' => 1,
                        'div' => false,
                        'type' => 'checkbox',
                        'checked' => $question['multiple_choice_question_answers'][3]['score'] > 0,
                        'label' => false,
                        'class' => 'multiple_choice_option'
                    ]);?>
                </td>
                <td>D</td>
                <td><?= __('O') ?></td>
                <td><?= __('J') ?></td>
                <td>-</td>
            </tr>
            <tr>
                <td>
                    <?= $this->Form->input('Answer.'.$question['multiple_choice_question_answers'][4]['id'], [
                        'value' => 1,
                        'div' => false,
                        'type' => 'checkbox',
                        'checked' => $question['multiple_choice_question_answers'][4]['score'] > 0,
                        'label' => false,
                        'class' => 'multiple_choice_option'
                    ]);?>
                </td>
                <td>E</td>
                <td><?= __('O') ?></td>
                <td><?= __('O') ?></td>
                <td>-</td>
            </tr>
            </tbody>
        </table>
    </div>
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
            <?= __("Volgende vraag")?>
        </a>
    </center>
<? } ?>
<?=$this->element('question_styling',['question' => $question]);?>