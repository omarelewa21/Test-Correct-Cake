    <?= $this->element('preview_attachments2019',['questions' => $questions, 'hideExtra' => $hideExtra]);?>

<h1><?= __("Open vraag")?> <? if($question['subtype'] == 'short') { ?>- <?= __("kort")?><?}else {?>- <?= __("lang")?><?}?><?=AppHelper::showExternalId($question);?></h1>
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
        <?=$this->Form->input('answer'.getUUID($question, 'get'), ['type' => 'text', 'style' => 'width:97%; margin-top:20px;', 'maxlength' => 140, 'label' => false])?>
    <? }else{ ?>
        <?=$this->Form->input('answer'.getUUID($question, 'get'), ['type' => 'textarea', 'style' => 'width:99%; height:70px; margin-top:20px;', 'label' => false])?>
    <? } ?>
    <?=$this->Form->end();?>
<? } ?>

<? if(isset($next_question)) { ?>
    <br />
    <center>
        <a href="#" class="btn highlight large" onclick="TestPreview.loadQuestionPreview('<?=$test_id?>', '<?=$next_question?>');">
            <span class="fa fa-check"></span>
            <?= __("Volgende vraag")?>
        </a>
    </center>
<? } ?>

<script type="text/javascript">
    if('<?=$question['subtype']?>' == 'long') {
        $('#AnswerAnswer<?=getUUID($question, 'get');?>').ckeditor({toolbar : [ [ 'Bold', 'Italic' ] ]} );
    }else {
        $('#AnswerAnswer<?=getUUID($question, 'get');?>').redactor({
            buttons: ['bold', 'italic', 'orderedlist'],
            pastePlainText: true,
            plugins: ['table', 'scriptbuttons']
        });
    }
</script>
<?=$this->element('question_styling',['question' => $question]);?>