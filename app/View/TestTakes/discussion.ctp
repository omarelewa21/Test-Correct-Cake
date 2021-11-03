<?
if(!isset($take)) {
    ?>
    <h1><?= __("Toets bespreken")?></h1>
    <center>
    <?= __("Er is geen toets om te bespreken.")?>
    </center>
    <?
}else {
    ?>

    <div id="buttons">
        <a href="#" class="btn highlight mr2" onclick="TestTake.finishDiscussion('<?=getUUID($take, 'get');?>');"><?= __("Bespreking beëindigen")?></a>
        <? if($has_next_question) { ?>
            <a href="#" onclick="TestTake.nextDiscussionQuestion('<?=getUUID($take, 'get');?>');" class="btn highlight mr2 nextDiscussionQuestion"><?= __("Volgende vraag")?></a>
        <? }  ?>
    </div>

    <h1><?= __("Bespreken")?> <?=$take['test']['name']?></h1>

<?= $this->element("attachment_popup"); ?>

    <div style="float:right; width:250px;">
        <?php if(isset($take['test_take_code']) && $take['test_take_code'] != null) {
            $test_code = sprintf('%s %s', $take['test_take_code']['prefix'], chunk_split($take['test_take_code']['code'], 3, ' '));
            ?>
            <div class="discuss-test-code-box" style="">
                <h5>Student inlogtoetscode</h5>
                <h1><?= $test_code ?></h1>
            </div>
        <?php } ?>
        <div class="block">
            <div class="block-head"><?= __("Studenten")?></div>
            <div class="block-content" style="padding:13px 15px 13px 15px;" id="participants">
            </div>
        </div>
    </div>

    <div style="float:left; width:calc(100% - 280px)">
        <div class="block" id="blockDiscussionQuestion">
            <div class="block-head"><?= __("Vraag")?></div>
            <div class="block-content" id="questionQuestion" style="font-size: 24px !important;">

            </div>
        </div>

        <div class="block" style="border-left: 20px solid var(--menu-blue);" id="blockDiscussionAnswer">
            <div class="block-head" style="color:white; background-color:var(--menu-blue);"><strong><?= __("Antwoordmodel")?></strong></div>
            <div class="block-content" id="questionAnswer" style="font-size: 24px !important;">

            </div>
        </div>
    </div>
    <br clear="all" />

    <a href="#" class="btn red" id="btnAttachmentFrame">
        <span class="fa fa-remove"></span>
    </a>
    <div id="attachmentFade"></div>

    <script type="text/javascript">
    <?php if (isset($take['discussing_question_uuid'])) { ?>
        $('#questionQuestion').load('/questions/preview_single_load/<?=$take['discussing_question_uuid']?>/<?=isset($group) ? $group : ''?>');
        $('#questionAnswer').load('/questions/preview_answer_load/<?=$take['discussing_question_uuid']?>');
    <?php } ?>
        $('#participants').load('/test_takes/discussion_participants/<?=getUUID($take, 'get');?>');

        clearInterval(window.participantsTimeout);
        window.participantsTimeout = setInterval(function() {
            Loading.discard = true;
            $('#participants').load('/test_takes/discussion_participants/<?=getUUID($take, 'get');?>');
        }, 2000);
    </script>
    <?
}
?>
