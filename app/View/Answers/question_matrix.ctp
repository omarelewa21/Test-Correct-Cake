<?=$this->element('take_attachments', ['question' => $question]);?>
<?php
    $citoClass = '';
    if(substr_count($question['metadata'],'cito') > 0){
        $citoClass = 'cito';
    }
?>
<?=$this->Form->create('Answer')?>
    <h1 class="<?=$citoClass?>">
        <?
        if($question['subtype'] == 'SingleChoice') {
            ?><?= __("Matrix")?><?
        }else{
            ?><?= __("Matrix ONBEKEND")?><?
        }
        ?>
        [<?=$question['score']?>pt]<?=AppHelper::showExternalId($question);?>
    </h1>

<div style="font-size: 20px;">
    <?=$this->element('take_question', ['question' => $question]);?>

    <?php

        echo sprintf('<div class="answer_container %s">',$citoClass);


        $answerSubQuestionReference = $answerJson;

        echo $this->element('question_matrix',[
            'question' => $question,
            'answerSubQuestionReference' => $answerSubQuestionReference,
        ]);
    ?>
</div>
</div>



<?=$this->Form->end();?>
<?= $this->element('take_footer', ['has_next_question' => $has_next_question]); ?>

<script type="text/javascript">
    $('input[type=radio]').click(function() {
        Answer.answerChanged = true;
    });

    Answer.answerChanged = false;
</script>
<?=$this->element('question_styling',['question' => $question]);?>


