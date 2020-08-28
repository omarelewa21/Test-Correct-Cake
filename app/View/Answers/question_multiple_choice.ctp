<?=$this->element('take_attachments', ['question' => $question]);?>

<?=$this->Form->create('Answer')?>
    <h1>
        <?
        if($question['subtype'] == 'TrueFalse') {
            ?>Juist / Onjuist<?
        }elseif($question['subtype'] == 'ARQ') {
            ?>ARQ<?
        }else{
            ?>Meerkeuze<?
        }
        ?>
        [<?=$question['score']?>pt]
    </h1>

<div style="font-size: 20px;">
    <?=$this->element('take_question', ['question' => $question]);?>

    <? if($question['subtype'] != 'TrueFalse') { ?>
        <br />Selecteer maximaal <?=$question['selectable_answers']?> <?=$question['selectable_answers'] > 1 ? 'antwoorden' : 'antwoord'?><br /><br />
    <? }

    shuffle($question['multiple_choice_question_answers']);

        $citoClass = '';
        if(substr_count($question['metadata'],'cito') > 0){
            $citoClass = 'cito';
        }
        echo sprintf('<div class="answer_container %s">',$citoClass);


        foreach( $question['multiple_choice_question_answers'] as $answer) {

        $checked = false;

        if(isset($answerJson[$answer['id']])) {
            $checked = $answerJson[$answer['id']] == 1 ? true : false;
        }

        echo '<div>'.$this->Form->input('Answer.'.$answer['id'], [
            'value' => 1,
            'div' => false,
            'type' => 'checkbox',
            'checked' => $checked,
            'label' => false,
            'class' => 'multiple_choice_option',
            'onchange' => 'checkMaxSelections(this)'
        ]);

        echo '&nbsp;'.$answer['answer'].'</div><br />';

        $first = false;
    }
    ?>
    </div></div>



<?=$this->Form->end();?>
<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<? if($question['subtype'] == 'TrueFalse' || $question['selectable_answers'] == 1) { ?>
    <script type="text/javascript">
        $('input[type=checkbox]').click(function() {
            $('input[type=checkbox]').prop('checked' , false);
            $(this).prop('checked' , true);
            Answer.answerChanged = true;
        });

        Answer.answerChanged = false;
    </script>
<? }else{ ?>
    <script type="text/javascript">
        $('input[type=checkbox]').click(function() {
            Answer.answerChanged = true;
        });

        Answer.answerChanged = false;
    </script>
<? } ?>

<script type="text/javascript">
    function checkMaxSelections(e) {
        if( $('.multiple_choice_option:checked').length > <?=$question['selectable_answers']?> ) {
            $(e).prop( "checked", false);
        }
    }
</script>
