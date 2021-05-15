<?= $this->element('preview_attachments2019',['questions' => $questions, 'hideExtra' => $hideExtra]);?>

<h1>ARQ<?=AppHelper::showExternalId($question);?></h1>

<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }
    ?>
    <?=$question['question']?><br />

    <?php
    $citoClass = '';
    if(substr_count($question['metadata'],'cito') > 0){
    $citoClass = 'cito';
    }
    echo sprintf('<div class="answer_container %s">',$citoClass);
        ?>

        <? if($question['subtype'] != 'TrueFalse') { ?>
            <br /><?= __("Selecteer maximaal")?> <?=$question['selectable_answers']?> <?=$question['selectable_answers'] > 1 ? __("antwoorden") : __("antwoord")?><br /><br />
        <? } ?>

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
                    <?= $this->Form->input('Answer.'.getUUID($question['multiple_choice_question_answers'][0], 'get'), [
                        'value' => 1,
                        'div' => false,
                        'type' => 'checkbox',

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
                    <?= $this->Form->input('Answer.'.getUUID($question['multiple_choice_question_answers'][1], 'get'), [
                        'value' => 1,
                        'div' => false,
                        'type' => 'checkbox',

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
                    <?= $this->Form->input('Answer.'.getUUID($question['multiple_choice_question_answers'][2], 'get'), [
                        'value' => 1,
                        'div' => false,
                        'type' => 'checkbox',

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
                    <?= $this->Form->input('Answer.'.getUUID($question['multiple_choice_question_answers'][3], 'get'), [
                        'value' => 1,
                        'div' => false,
                        'type' => 'checkbox',

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
                    <?= $this->Form->input('Answer.'.getUUID($question['multiple_choice_question_answers'][4], 'get'), [
                        'value' => 1,
                        'div' => false,
                        'type' => 'checkbox',

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