<div id="buttons">
    <?= $this->element('back-button'); ?>
</div>

<?= $this->element("attachment_popup"); ?>

<h1><?= __("Toets resultaten")?></h1>
<?
    $i = 0;
    foreach($questions as $question) {
        $i++;
        ?>
        <div class="block questionContainer">
            <div class="block-head"><?= __("Vraag")?> #<?=$i?> <?= __("voorbeeld")?></div>
            <div class="block-content" id="question_preview_<?=$question['question']['id']?>">
            <?= __("Laden..")?>
            </div>
        </div>

        <div class="block" style="width:280px; float:right;">
            <div class="block-head">
            <?= __("Score")?>
            </div>
            <div class="block-content" id="score_<?=$participant_id?><?=$question['question']['id']?>">
            <?= __("Laden..")?>
            </div>
        </div>

        <div class="block" style="width:calc(100% - 300px); margin-bottom: 100px;">
            <div class="block-head"><?= __("Vraag")?> #<?=$i?> <?= __("antwoord")?></div>
            <div class="block-content" id="question_answer_<?=$question['question']['id']?>">
            <?= __("Laden..")?>
            </div>
        </div>

        <br clear="all" />

        <script type="text/javascript">

            $('#question_preview_<?=$question['question']['id']?>').load('/questions/preview_single_load/<?=getUUID($question['question'], 'get');?>');
            $('#question_answer_<?=$question['question']['id']?>').load('/test_takes/rate_teacher_answer/<?=$participant_id?>/<?=getUUID($question['question'], 'get');?>',
                function() {
                    $('#score_<?=$participant_id?><?=$question['question']['id']?>').load('/test_takes/rate_teacher_score/<?=$participant_id?>/<?=getUUID($question['question'], 'get');?>/0');
                }
            );
        </script>
    <?
}
?>

