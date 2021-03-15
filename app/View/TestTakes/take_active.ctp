<a href="#" class="btn highlight" id="btnHandIn" onclick="TestTake.handIn();">
    Inleveren
</a>
<div id="test_progress">
    <?
    $i = 0;

    const START_DEL = '<div style="float:left; overflow:auto; margin:0 5px; padding-left:2px">';
    const END_DEL = '</div>';

    $groupId = false;
    $groupCloseable = false;
    $closeGroupStarted = false;

    $groupId = false;

    $before = '';
    $nextId = '';
    $pickNext = false;

    $navigationItems = [];
    $currentItemClosed = false;
    foreach ($questions as $index => $questions_item) {
        if ($pickNext) {
            $nextId = getUUID($questions_item, 'get');
            $pickNext = false;
        }

        $item =  [];

        if (array_key_exists('answer_parent_questions', $questions_item)
            && array_key_exists(0, $questions_item['answer_parent_questions'])
            && array_key_exists('group_question', $questions_item['answer_parent_questions'][0])
        ) {
            $currentGroupId = ($questions_item['answer_parent_questions'][0]['group_question']['id']);
            $groupCloseable = $questions_item['answer_parent_questions'][0]['group_question']['closeable'];

// nieuwe group gestart
            if($groupId != $currentGroupId) {
                if ($closeGroupStarted) {
                    $before .= END_DEL;
                    //if($groupCloseable) {
                        $before .= START_DEL;
                        $closeGroupStarted = true;
                    //}
                } else {
                    $before .= START_DEL;
                    $closeGroupStarted = true;
                }
            }


            $groupId = $currentGroupId;
        } else {
            if($closeGroupStarted) {
                $before .= END_DEL;
                $groupId = false;
            }
            $closeGroupStarted = false;
        }


        $i++;

        if ($index == $take_question_index) {
            if ($questions_item['closed'] || $questions_item['closed_group']) {
                $currentItemClosed  = true;
            }
            $class = 'active';
            $pickNext = true;

        } elseif ($questions_item['done'] == 1) {
            $class = 'green';
        } else {
            $class = 'grey';
        }

        $item['target_url'] = sprintf('/test_takes/take/%s/%s', $take_id, $index);
        $item['closed'] = $questions_item['closed'];
        $item['closed_group'] = $questions_item['closed_group'];

        $item['element'] = sprintf('%s<div id="%s" group-id="%d" class="question %s" onclick="%s" current-item-closed="%s">%d</div>', $before, getUUID($questions_item, 'get') ,$groupId, $class,'%s', $currentItemClosed, $i);

        $navigationItems[] = $item;

        $before = '';
    }

    foreach($navigationItems as $item) {
        $onclickEvent = sprintf("Answer.loadQuestion('%s', this)",$item['target_url']);
        if ($currentItemClosed == true) {
            $onclickEvent = sprintf("Navigation.load('%s')",$item['target_url']);
        }

        printf($item['element'], $onclickEvent);
    }

    if ($closeGroupStarted) {
        echo END_DEL;
    }
    $action = 'Answer.loadQuestion';
    if ($currentItemClosed) {
        $action = 'Navigation.load';
    }
    ?>
    <div class="question green" group-id="0" onclick="<?= $action ?>('/test_takes/take_answer_overview/<?= $take_id ?>', this);">
        <span class="fa fa-list"></span>
    </div>

    <?
    if ($this->Session->read('Auth.User.has_text2speech') == 1) {
        ?>
        <div id="__ba_launchpad">

        </div>
        <div class="question green" style="float:right;width:auto;padding-right:7px;padding-left:7px;"
             onClick="toggleBrowseAloud();"><i class="fa fa-volume-up"></i> Lees voor
        </div>
        <?
    }
    ?>

    <br clear="all"/>
</div>

<br clear="all"/>

<?= $this->element("attachment_popup"); ?>

<div id="question_load"></div>

<script>

    TestTake.startHeartBeat('active');
    TestTake.questionSaved = false;

    <?php
    if(isset($questions[$take_question_index + 1])) { ?>
    TestTake.nextUrl = '/test_takes/take/<?=$take_id?>/<?=($take_question_index + 1)?>';
    <?php }else{ ?>
    TestTake.nextUrl = '/test_takes/take_answer_overview/<?=$take_id?>';
    <?php } ?>

    Answer.loadQuestionAnswer('<?=$active_question?>');
    Answer.partOfCloseableGroup = false;
    Answer.currentGroupId = false;
    Answer.nextId = '<?= $nextId ?>';

    <?php
    if (array_key_exists('answer_parent_questions', $questions[$take_question_index])
    && array_key_exists(0, $questions[$take_question_index]['answer_parent_questions'])
    && array_key_exists('group_question', $questions[$take_question_index]['answer_parent_questions'][0])
    && array_key_exists('closeable', $questions[$take_question_index]['answer_parent_questions'][0]['group_question'])
    ) {
    ?>
    Answer.partOfCloseableGroup = <?php echo (int) $questions[$take_question_index]['answer_parent_questions'][0]['group_question']['closeable'] == 1 ? 'true;' : 'false;' ?>
    Answer.currentGroupId = <?php echo (int) $questions[$take_question_index]['answer_parent_questions'][0]['group_question']['id']  ?>
    <?php } ?>


    Answer.takeId = '<?=$take_id?>';

    jQuery("#btnAttachmentFrameMove").on('mouseenter', function (e) {
        console.log('mousentered');
        jQuery("#attachmentContainer").draggable({handle: '#btnAttachmentFrameMove'});
    });


    <?php
    if($this->Session->read('Auth.User.has_text2speech') == 1){
    ?>
    function toggleBrowseAloud() {
        if (typeof BrowseAloud == 'undefined') {
            var s = document.createElement('script');
            s.src = 'https://www.browsealoud.com/plus/scripts/3.1.0/ba.js';
            s.integrity="sha256-VCrJcQdV3IbbIVjmUyF7DnCqBbWD1BcZ/1sda2KWeFc= sha384-k2OQFn+wNFrKjU9HiaHAcHlEvLbfsVfvOnpmKBGWVBrpmGaIleDNHnnCJO4z2Y2H sha512-gxDfysgvGhVPSHDTieJ/8AlcIEjFbF3MdUgZZL2M5GXXDdIXCcX0CpH7Dh6jsHLOLOjRzTFdXASWZtxO+eMgyQ=="
            s.crossOrigin = 'anonymous';
            document.getElementsByTagName('BODY')[0].appendChild(s);
                waitForBrowseAloudAndThenRun();
        } else {
            _toggleBA();
        }
    }

    function hideBrowseAloudButtons() {
        var shadowRoot = document.querySelector('div#__bs_entryDiv').querySelector('div').shadowRoot
        var elementsToHide = ['th_translate','th_mp3Maker', 'ba-toggle-menu']
        elementsToHide.forEach(function(id) {
            shadowRoot.getElementById(id).setAttribute('style', 'display:none');
        });
        shadowRoot.getElementById('th_toolbar').setAttribute('style', 'background-color: #fff');
        [... shadowRoot.querySelectorAll('.th-browsealoud-toolbar-button__icon')].forEach(function(item) {
            item.setAttribute('style', 'fill : #515151');
        });
    }

    var _baTimer;
    var tryIterator = 0;

    function waitForBrowseAloudAndThenRun() {
        if (typeof BrowseAloud == 'undefined' || BrowseAloud.panel == 'undefined' || typeof BrowseAloud.panel.toggleBar == 'undefined' || typeof $jqTm == 'undefined') {
            _baTimer = setTimeout(function () {
                    waitForBrowseAloudAndThenRun();
                },
                150);
        } else {
            clearTimeout(_baTimer);
            try {
                _toggleBA();
            } catch(e) {
                tryIterator ++;
                if (tryIterator < 10) { // just stop when it still fails after 10 tries;
                    setTimeout(function () {
                            waitForBrowseAloudAndThenRun();
                        },
                        150);
                }
            }
        }
    }

    function _toggleBA() {
        BrowseAloud.panel.toggleBar(!0);
        setTimeout(function() {
            hideBrowseAloudButtons();
        }, 300);
    }

    <?php
    }
    ?>

</script>



