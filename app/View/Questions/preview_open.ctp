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

<h1><?=$question['subtype'] == 'long' ? 'Wiskunde vraag' : 'Open vraag'?></h1>
<div style="font-size: 20px;">
    <?
    if(isset($question['question_group']['text']) && !empty($question['question_group']['text'])) {
        echo '<p>'. $question['question_group']['text'].'</p>';
    }
    ?>
    <?=$question['question']?>
</div>

<br clear="all" />
<? if(!$hideExtra) { ?>
    <?=$this->Form->create('Answer')?>
    <? if($question['subtype'] == 'short') { ?>
        <?=$this->Form->input('answer'.$question['id'], ['type' => 'text', 'style' => 'width:97%; margin-top:20px;', 'maxlength' => 140, 'label' => false])?>
    <? }else{ ?>
        <?=$this->Form->input('answer'.$question['id'], ['type' => 'textarea', 'style' => 'width:99%; height:70px; margin-top:20px;', 'label' => false])?>
    <? } ?>
    <?=$this->Form->end();?>
<? } ?>

<? if(isset($next_question)) { ?>
    <br />
    <center>
        <a href="#" class="btn highlight large" onclick="TestPreview.loadQuestionPreview(<?=$test_id?>, <?=$next_question?>);">
            <span class="fa fa-check"></span>
            Volgende vraag
        </a>
    </center>
<? } ?>

<script type="text/javascript">
    if('<?=$question['subtype']?>' == 'long') {
        $('#AnswerAnswer<?=$question['id']?>').ckeditor({toolbar : [ [ 'EqnEditor', 'Bold', 'Italic' ] ]} );
    }else {
        $('#AnswerAnswer<?=$question['id']?>').redactor({
            buttons: ['bold', 'italic', 'orderedlist'],
            pastePlainText: true,
            plugins: ['table', 'scriptbuttons']
        });
    }
</script>