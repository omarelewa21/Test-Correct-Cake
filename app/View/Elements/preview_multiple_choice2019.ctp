<?= $this->element('preview_attachments2019',['questions' => $questions, 'hideExtra' => $hideExtra]);?>

<h1>
    <?
    if($question['subtype'] == 'TrueFalse') {
        ?>Juist / Onjuist<?
    }else{
        ?>Multiple choice<?
    }
    ?>
</h1>

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

    <?
    if($question['subtype'] == 'TrueFalse') {
        $first = false;
    }else{
        $first = false;
    }

    foreach($question['multiple_choice_question_answers'] as $answer) {

        echo '<div>'.$this->Form->input('Answer.'.$answer['id'], [
            'value' => 1,
            'div' => false,
            'type' => 'checkbox',
            'checked' => $first,
            'label' => false,
            'class' => 'multiple_choice_option'
        ]);
        echo '&nbsp;'.$answer['answer'].'</div><br />';
        $first = false;
    }
    ?>
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
