<?= $this->element('teacher_question_edit_header', ['question_type' =>  __("Infoscherm"), 'test_name' => $test_name]) ?>
<!--<div class="popup-head">--><?//= __("Infoscherm")?><!--</div>-->
<div style="margin: 0 auto; max-width:1000px;padding-bottom: 80px;">

    <?=$this->Form->create('Question', array('id' => $is_clone_request ? 'QuestionAddForm' : 'QuestionEditForm', 'class' => 'add_question_form'))?>


    <?=$this->Form->input('closeable', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __("Deze vraag afsluiten")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/closeable_info', 500);" style="cursor:pointer"></span><br />
    
                <?=$this->Form->input('discuss', array('value' => '0','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('decimal_score', array('value' => '0','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('score', array('value' => '0','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('note_type', array('value' => 'NONE','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('subtype', array('label' => false, 'value' => 'none','type' => 'hidden'))?>
    <?=$this->Form->input('is_opensource_content', array('label' => false, 'value' => '0','type' => 'hidden'))?>

    <?= $this->element('teacher_add_question_tabs', ['cloneRequest' => $is_clone_request, 'edit' => true]) ?>

        <div page="question" class="page active" tabs="edit_question">
            <span class="title"><?= __('Vraag')?></span>
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'value' => $question['question']['question'])); ?>
        </div>

        <div page="question" class="page" tabs="edit_question">
            <span class="title"><?= __('Antwoord')?></span>
            <?=$this->Form->input('answer', array('value' => $question['question']['answer'],'label' => false, 'type' => 'hidden'))?>
            <?= __("Niet van toepassing")?>
        </div>

        <div page="settings" class="page" tabs="edit_question">
            <span class="title"><?= __('Eindtermen')?></span>
            <?=$this->element('attainments', ['attainments' => $attainments, 'selectedAttainments' => $selectedAttainments]) ?>
        </div>

        <div page="info" class="page" tabs="edit_question">
            <span class="title"><?= __('Info')?></span>
            <?=$this->element('question_info', ['question' => $question])?>
        </div>

        <?=$this->element('question_tab_rtti',['question' => $question]); ?>

        <div page="settings" class="page" tabs="edit_question">
            <span class="title"><?= __('Tags')?></span>
            <?=$this->Form->input('tags', array('label' => false, 'type' => 'select', 'multiple' => true, 'style' => 'width:750px;', 'options' => $question['question']['tags'], 'value' => $question['question']['tags']))?>
        </div>

        <?=$this->Form->end();?>

        <? if($owner != 'group') { ?>
            <?= $this->element('question_editor_attachments', ['edit' => true]) ?>
        <? } ?>
</div>
<? if ($is_clone_request) { ?>
    <?= $this->element('teacher_question_edit_footer', ['saveAction' =>"Questions.add('InfoscreenQuestion', '$owner', '$owner_id');"]) ?>
<? } else { ?>
    <? if ($editable) { ?>
        <?= $this->element('teacher_question_edit_footer', ['saveAction' => "Questions.edit('$owner', '$owner_id', 'InfoscreenQuestion', '".getUUID($question, 'get')."')"]) ?>
    <? } else { ?>
        <?= $this->element('teacher_question_edit_footer', ['saveAction' => '', 'withSaving' => false]) ?>
    <? } ?>
<? } ?>
<script type="text/javascript">

    <? if(!$editable) { ?>
        $('.popup-content input, .popup-content select, .popup-content textarea').not('.disable_protect').attr({'disabled' : true});
    <? } ?>

    <? if($is_clone_request){ ?>
        Questions.loadAddAttachments(true,'<?=$owner?>', '<?=$owner_id?>', '<?=getUUID($question, 'get');?>');
    <? }else{ ?>
        <? if($owner != 'group') { ?>
            Questions.loadEditAttachments('<?=$owner?>', '<?=$owner_id?>', '<?=getUUID($question, 'get');?>');
        <? } ?>
    <? } ?>

    $('#QuestionTags').select2({
        tags : true,
        ajax: {
            url: "/questions/tags",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data, page) {
                return {
                    results: data.items
                };
            },
            cache: true
        }
    });

    $('#QuestionQuestion').ckeditor({});
</script>
