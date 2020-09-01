<?=$this->element('take_attachments', ['question' => $question]);?>
<?php
    $citoClass = '';
    if(AppHelper::isCitoQuestion($question)){
$citoClass = 'cito';
}
?>
<?=$this->Form->create('Answer')?>
    <h1 class="question_type <?=$citoClass?>">
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
        <?=AppHelper::showExternalId($question);?>
    </h1>

<?php

    $useRadio = false;
    $radioOptions = [];
    $default = 0;
    if($question['subtype'] == 'TrueFalse' || $question['selectable_answers'] == 1){
        $useRadio = true;
        $label = '<div class="radio_'.$question['id'].'">';
    }

    shuffle($question['multiple_choice_question_answers']);
?>

<div style="font-size: 20px;">

    <?php

    echo $this->element('take_question', ['question' => $question]);

    echo sprintf('<div class="answer_container %s">',$citoClass);
    if($useRadio){
         echo $this->element('question_multiple_choice_radio_answers',['question' => $question]);
    } else {
        echo $this->element('question_multiple_choice_regular_answers',['question' => $question]);
    }

    ?>

</div>
</div>



<?=$this->Form->end();?>
<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<? if($useRadio) { ?>
    <?= $this->element('question_multiple_choice_radio_javascript', ['question' => $question]); ?>
<? }else{ ?>
    <?= $this->element('question_multiple_choice_regular_javascript', ['question' => $question]); ?>
<? } ?>

<script type="text/javascript">
    function checkMaxSelections(e) {
        if( $('.input_<?=$question['id']?>:checked').length > <?=$question['selectable_answers']?> ) {
            $(e).prop( "checked", false);
        }
    }
</script>
