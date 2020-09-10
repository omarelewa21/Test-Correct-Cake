<?
if(!isset($take)) {
    ?>
    <h1>Toets bespreken</h1>
    <center>
        Er is geen toets om te bespreken.
    </center>
    <?
}else {
    ?>

    <div id="buttons">
        <a href="#" class="btn highlight mr2" onclick="TestTake.finishDiscussion('<?=getUUID($take, 'get');?>');">Bespreking be&euml;indigen</a>
        <? if($has_next_question) { ?>
            <a href="#" onclick="TestTake.nextDiscussionQuestion('<?=getUUID($take, 'get');?>');" class="btn highlight mr2 nextDiscussionQuestion">Volgende vraag</a>
        <? }  ?>
    </div>

    <h1>Bespreken <?=$take['test']['name']?></h1>

<?= $this->element("attachment_popup"); ?>

    <div class="block" style="float:right; width:250px;">
        <div class="block-head">Studenten</div>
        <div class="block-content" style="padding:13px 15px 13px 15px;" id="participants">

        </div>
    </div>

    <div style="float:left; width:calc(100% - 280px)">
        <div class="block" id="blockDiscussionQuestion">
            <div class="block-head">Vraag</div>
            <div class="block-content" id="questionQuestion" style="font-size: 24px !important;">

            </div>
        </div>

        <div class="block" style="border-left: 20px solid #197cb4;" id="blockDiscussionAnswer">
            <div class="block-head" style="color:white; background-color:#197cb4;"><strong>Antwoordmodel</strong></div>
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
