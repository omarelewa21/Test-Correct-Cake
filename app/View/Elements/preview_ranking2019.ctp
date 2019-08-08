<? if(count($question['attachments']) > 0 && !$hideExtra) { ?>
    <div style="width:250px; padding:20px; background: #294409; margin-left: 30px; float: right;">
        <div style="color: white; text-align: center; font-size: 22px; margin-bottom: 10px;">
            Bronnen
        </div>
        <?
        $i = 0;
        foreach($question['attachments'] as $attachemnt) {
            $i++;
            ?>
            <a href="#" class="btn white" style="margin-bottom: 2px;" onclick="Answer.loadAttachment(<?=$attachemnt['id']?>);">
                Bijlage #<?=$i?>
            </a>
        <?
        }
        ?>
    </div>
<? } ?>

<h1>Rangschikvraag</h1>

<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }
    ?>
    <?=$question['question']?>
</div>

<div id="answers" style="">
    <?
    $answers = $question['ranking_question_answers'];
    shuffle($answers);

    foreach($answers as $answer) {
        ?>
        <div style="padding:10px; margin-bottom: 2px; background: grey;" class="ranking_answer">
            <?=$answer['answer']?>
        </div>
        <?
    }
    ?>
</div>
<script type="text/javascript">
    $('#answers').sortable();
</script>

<? if(isset($next_question)) { ?>
    <br />
    <center>
        <a href="#" class="btn highlight large" onclick="TestPreview.loadQuestionPreview(<?=$test_id?>, <?=$next_question?>);">
            <span class="fa fa-check"></span>
            Volgende vraag
        </a>
    </center>
<? } ?>