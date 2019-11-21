<a href="#" class="btn highlight" id="btnHandIn" onclick="TestTake.handIn();">
    Inleveren
</a>
<div id="test_progress">
    <?
    $i = 0;

    foreach($questions as $index => $questions_item) {

        $i++;

        if($index == $take_question_index) {
            $class = 'active';
        }elseif($questions_item['done'] == 1) {
            $class = 'green';
        }else{
            $class = 'grey';
        }

        ?>
        <div class="question <?=$class?>" onclick="Answer.loadQuestion('/test_takes/take/<?=$take_id?>/<?=$index?>');"><?=$i?></div>
        <?
    }
    ?>
    <div class="question green" onclick="Answer.loadQuestion('/test_takes/take_answer_overview/<?=$take_id?>');">
        <span class="fa fa-list"></span>
    </div>

    <?
     if($this->Session->read('Auth.User.has_text2speech') == 1){
        ?>
        <div id="__ba_launchpad"><div class="question green" style="float:right;width:auto;padding-right:7px;padding-left:7px;"><i class="fa fa-volume-up"></i> Lees voor</div></div>
        <?
      }
    ?>

    <br clear="all" />
</div>

<br clear="all" />

<div id="attachmentContainer" style="display:none">
    <div id="attachmentButtonContainer">
        <a href="#" class="btn red" id="btnAttachmentFrame" style="display:inline-block;position:relative;float:right">
            <span class="fa fa-remove"></span>
        </a>

        <a href="#" class="btn green mr8" id="btnAttachmentFrameMove" style="display:inline-block;position:relative;float:right">
            <span class="fa fa-arrows"></span>
        </a>
    </div>


    <iframe id="attachmentFrame" frameborder="0"></iframe>
</div>
<div id="attachmentFade"></div>

<div id="question_load"></div>

<style>
    #attachmentButtonContainer {
        position : absolute;
        right    : 0;
        top      : 0;
        width    : 120px;
        height   : 40px;
    }
    #attachmentButtonContainer a {
        z-index : 11600;
    }
    #attachmentFrame #presentationMode {
        display : none !important;
    }
    #attachmentContainer #btnAttachmentFrame {
        right   : auto;
        top     : auto;
        line-height: initial;
    }
    #attachmentContainer .green {
        background-color: #3D9D36;
        color : white;
    }
    #attachmentContainer .ui-resizable-handle.ui-resizable-se.ui-icon.ui-icon-gripsmall-diagonal-se {
        z-index : 11600 !important;
        color   : red;
    }
    #attachmentContainer {
        position : absolute;
        border : 0px solid #eeeeee;
        background-color: white;
        -webkit-box-shadow: 3px 3px 24px 0px rgba(0,0,0,0.75);
        -moz-box-shadow: 3px 3px 24px 0px rgba(0,0,0,0.75);
        box-shadow: 3px 3px 24px 0px rgba(0,0,0,0.75);
    }
    #attachmentFrame {
        display: block;
        position: relative;
    }
    #attachmentContainer {
        position:absolute;
        width: 80%;
        height:600px;
    }
    #attachmentContainer.draggable {
        position:absolute;
        width: 500px;
        height:350px;
    }
</style>
<script>

    TestTake.startHeartBeat('active');
    TestTake.questionSaved = false;

    <?
    if(isset($questions[$take_question_index + 1])) { ?>
        TestTake.nextUrl =  '/test_takes/take/<?=$take_id?>/<?=($take_question_index+ 1)?>';
    <? }else{ ?>
        TestTake.nextUrl =  '/test_takes/take_answer_overview/<?=$take_id?>';
    <? } ?>

    Answer.loadQuestionAnswer(<?=$active_question?>);

    Answer.takeId = <?=$take_id?>;
    
</script>
<?php
if($this->Session->read('Auth.User.has_text2speech') == 1){
?>
<script type="text/javascript" src="https://www.browsealoud.com/plus/scripts/2.6.0/ba.js" crossorigin="anonymous" integrity="sha256-fmrh3+LxhFr7DoDksKC0j8vUwhWdxRkMn8KFlFCQdRM= sha384-z00WvjaU37iu++7Lpo/4M4S7R56TUvMzVpc17SIQsiZepsABY8IFOPCvJVCe2tqN sha512-9ToHuVTbdiA7nUgUD0s8er8Aul62PXPwR1zenCOwIDaVN1eaJhieH1+yk5wTAi9r7ZIr+e/B7xehNqnweTg5aQ=="></script>
<?php
}
?>


