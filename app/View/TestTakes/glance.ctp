<?php if ($guest) { ?>
<a href="#" onclick="User.returnToLaravelLogin('<?= $loginUrl?>')" class="btn highlight" id="btnHandIn">
    Sluiten
</a>
<?php } else if (isset($laravelUrl)) { ?>
    <a href="#" onclick="User.goToLaravel('<?= $laravelUrl ?>')" class="btn highlight" id="btnHandIn">
        Sluiten
    </a>
<?php } else { ?>
<a href="#" class="btn highlight" id="btnHandIn" onclick="Navigation.load('/test_takes/discussed_glance');">
<?= __("Sluiten")?>
</a>
<?php } ?>
<div id="test_progress">
    <?
    $i = 0;
    foreach($questions as $index => $questions_item) {

        if($question_index == $i) {
            $class = 'highlight';
        }else{
            $class = 'grey';
        }

        ?>
        <div onclick="Navigation.load('/test_takes/glance/<?=$take_id?>/<?=$i?>');" class="question <?=$class?>"><?=($i + 1)?></div>
        <?
        $i++;
    }
    ?>
    <br clear="all" />
</div>

<br clear="all" />
<?= $this->element("attachment_popup"); ?>
<div style="margin: 0px auto; width:200px; float:right;">
    <div class="block" style="width:100%;">
        <div class="block-head">Score</div>
        <div class="block-content">

            <?

            $firstStudent = null;
            $studentScore = null;
            $teachterScore = null;
            $systemScore = null;

            foreach($answer[0]['answer_ratings'] as $rating) {
                if($rating['type'] == 'SYSTEM' && !empty($rating['rating'])) {
                    $systemScore = $rating['rating'];
                }

                if($rating['type'] == 'TEACHER' && !empty($rating['rating'])) {
                    $teachterScore = $rating['rating'];
                }

                if($rating['type'] == 'STUDENT' && !empty($rating['rating'])) {
                    if(empty($firstStudent)) {
                        $firstStudent = $rating['rating'];
                    }elseif($firstStudent == $rating['rating']) {
                        $studentScore = $rating['rating'];
                    }
                }
            }

            if(!empty($teachterScore)) {
                $score = $teachterScore;
            }else if(!empty($systemScore)) {
                $score = $systemScore;
            }else if(!empty($studentScore)) {
                $score = $studentScore;
            }

            ?>

            <div style='font-size:40px; text-align: center;'><?=!empty($score) ? $score : ' - '?> / <?=$questions[$question_index]['question']['score']?></div>
            <div style="text-transform: uppercase; text-align: center; opacity:.7; font-size:13px; font-weight: bold;">
            <?= __("SCORE")?>
            </div>
        </div>
    </div>

    <div style="width: 100%; text-align: center">
        <a href="#" class="btn highlight mb15 feedback"
            <? if($answer[0]['has_feedback']){ ?>
                style="border-radius: 10px; background-image: linear-gradient(to right, #004df5 0%, #4781ff 100%)"
                onclick="Popup.load('/test_takes/getFeedbackByAnswerId/read/<?=getUUID($answer[0], 'get')?>/<?= $question_index ?>', 700);"
            <? }else{ ?>
                style="border-radius: 10px; background: #f0f2f5; color: #929daf; cursor: default;)"
            <? } ?>
        >
            <i class="fa fa-eye" style="margin-right:2%" aria-hidden="true"></i>
            <span style="position:relative; bottom:1px"><?= __('Bekijk feedback') ?></span>
        </a>
    </div>
</div>

<div class="block" style="float:left; width:calc(100% - 220px)">
    <div class="block-head"><?= __("Vraag")?></div>
    <div class="questionContainer">
        <div class="block-content" id="questionQuestion">
    </div>

    </div>
</div>

<br clear="all" />

<div class="block">
    <div class="block-head"><?= __("Antwoordmodel")?></div>
    <div class="block-content" id="questionQuestion_correct">

    </div>
</div>

<div class="block">
    <div class="block-head"><?= __("Jouw antwoord")?></div>
    <div class="block-content" id="questionAnswer">

    </div>
</div>

<a href="#" class="btn red" id="btnAttachmentFrame">
    <span class="fa fa-remove"></span>
</a>
<iframe id="attachmentFrame" frameborder="0"></iframe>
<div id="attachmentFade"></div>


<script type="text/javascript">
    <?php if (isset($guest_exit)) {?>
        User.returnToLaravelLogin('<?= $loginUrl?>')
    <?php } ?>

    $('#questionQuestion').load('/questions/preview_single_load/<?=getUUID($questions[$question_index]['question'], 'get')?>/<?=isset($group) ? $group : ''?>');
    $('#questionQuestion_correct').load('/questions/preview_answer_load/<?=getUUID($questions[$question_index]['question'], 'get')?>');
    $('#questionAnswer').load('/test_takes/glance_answer/<?=$take_id?>/<?=$question_index?>');
</script>