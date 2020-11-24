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
        <div id="__ba_launchpad"><div class="question green" style="float:right;width:auto;padding-right:7px;padding-left:7px;" onClick="toggleBrowseAloud();"><i class="fa fa-volume-up"></i> Lees voor</div></div>
        <?
      }
    ?>

    <br clear="all" />
</div>

<br clear="all" />

<?= $this->element("attachment_popup"); ?>

<div id="question_load"></div>



<script>

    TestTake.startHeartBeat('active');
    TestTake.questionSaved = false;

    <?php
    if(isset($questions[$take_question_index + 1])) { ?>
        TestTake.nextUrl =  '/test_takes/take/<?=$take_id?>/<?=($take_question_index+ 1)?>';
    <?php }else{ ?>
        TestTake.nextUrl =  '/test_takes/take_answer_overview/<?=$take_id?>';
    <?php } ?>

    Answer.loadQuestionAnswer('<?=$active_question?>');

    Answer.takeId = '<?=$take_id?>';

    jQuery("#btnAttachmentFrameMove").on('mouseenter', function(e){
        console.log('mousentered');
        jQuery("#attachmentContainer").draggable({handle:'#btnAttachmentFrameMove'});
    });


    <?php
    if($this->Session->read('Auth.User.has_text2speech') == 1){
        ?>
        function toggleBrowseAloud(){
            if(typeof BrowseAloud == 'undefined'){
                var s = document.createElement('script');
                s.crossorigin='anonymous';
                s.src='https://www.browsealoud.com/plus/scripts/2.6.0/ba.js';
                document.getElementsByTagName('BODY')[0].appendChild(s);
                waitForBrowseAloudAndThenRun();
            }
            else {
                _toggleBA();
            }
        }
        var _baTimer;
        function waitForBrowseAloudAndThenRun(){
            if(typeof BrowseAloud == 'undefined' || BrowseAloud.panel == 'undefined' || typeof BrowseAloud.panel.toggleBar == 'undefined'){
                _baTimer = setTimeout(function(){
                    waitForBrowseAloudAndThenRun();
                },
                150);
            }else{
                clearTimeout(_baTimer);
                _toggleBA();
            }
        }

        function _toggleBA(){
            BrowseAloud.panel.toggleBar(!0);
        }

    <?php
    }
    ?>

</script>



