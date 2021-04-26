<a href="#" class="btn highlight" id="btnHandIn" onclick="Navigation.back();">
<?= __("Terug")?>
</a>

<br clear="all" />
<?
foreach($answers as $answer) {

    ?>

    <div class="block">
        <div class="block-head"><?= __("Vraag")?></div>
        <div class="block-content" id="participant_question_<?=getUUID($answer['answer']['testparticipant'], 'get')?><?=getUUID($answer['answer']['question'], 'get')?>">

        </div>
    </div>

    <div class="block" style="border-left: 3px solid #197cb4;">
        <div class="block-head"><?= __("Antwoordmodel")?></div>
        <div class="block-content" id="participant_question_answer_<?=getUUID($answer['answer']['testparticipant'], 'get')?><?=getUUID($answer['answer']['question'], 'get')?>">

        </div>
    </div>

    <div class="block" style="float:left; width:calc(100% - 250px);">
        <div class="block-head">
        <?= __("Antwoord")?>
        </div>
        <div id="participant_answer_<?=getUUID($answer['answer']['testparticipant'], 'get')?><?=$answer['answer']['uuid']?>" class="block-content">
        <?= __("Laden..")?>
        </div>
    </div>

    <div class="block" style="float:right; width: 230px; border-left: 3px solid #3D9D36">
        <div class="block-head"><?= __("Score")?></div>
        <div class="block-content" id="score_<?=getUUID($answer['answer']['testparticipant'], 'get')?><?=getUUID($answer['answer']['question'], 'get')?>">
            --
        </div>
    </div>

    <br clear="all" />
    <script type="text/javascript">
        $('#participant_question_<?=getUUID($answer['answer']['testparticipant'], 'get')?><?=getUUID($answer['answer']['question'], 'get')?>').load('/questions/preview_single_load/<?=getUUID($answer['answer']['question'], 'get')?>');
        $('#participant_question_answer_<?=getUUID($answer['answer']['testparticipant'], 'get')?><?=getUUID($answer['answer']['question'], 'get')?>').load('/questions/preview_answer_load/<?=getUUID($answer['answer']['question'], 'get')?>');
        $('#participant_answer_<?=getUUID($answer['answer']['testparticipant'], 'get')?><?=$answer['answer']['uuid']?>').load('/test_takes/rate_teacher_answer/<?=getUUID($answer['answer']['testparticipant'], 'get')?>/<?=getUUID($answer['answer']['question'], 'get')?>',
            function() {
                $('#score_<?=getUUID($answer['answer']['testparticipant'], 'get')?><?=getUUID($answer['answer']['question'], 'get')?>').load('/test_takes/rate_teacher_score/<?=getUUID($answer['answer']['testparticipant'], 'get')?>/<?=getUUID($answer['answer']['question'], 'get')?>');
            }
        );
    </script>
    <?
}
?>