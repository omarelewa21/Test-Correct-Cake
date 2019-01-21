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
     if($this->Session->read('Auth.User.time_dispensation') == 1){
    ?>
    <div class="question green" style="float:right;width:auto;padding-right:7px;padding-left:7px;" onclick="document.getElementsByTagName('BODY')[0].appendChild(document.createElement('script')).src='https://babm.texthelp.com/Bookmarklet.ashx?l=nl';"><i class="fa fa-volume-up"></i> Lees voor</div>
    <?
      }
    ?>
    <br clear="all" />
</div>

<br clear="all" />

<a href="#" class="btn red" id="btnAttachmentFrame" onclick="">
    <span class="fa fa-remove"></span>
</a>
<iframe id="attachmentFrame" frameborder="0"></iframe>
<div id="attachmentFade"></div>

<div id="question_load"></div>

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

