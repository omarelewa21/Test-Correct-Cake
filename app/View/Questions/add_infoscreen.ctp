<?= $this->element('teacher_question_edit_header', ['question_type' =>  __("Infoscherm"), 'test_name' => $test_name]) ?>
<!--<div class="popup-head">--><?//= __("Infoscherm")?><!--</div>-->
<div style="margin: 0 auto; max-width:1000px;padding-bottom: 80px;">

    <?=$this->Form->create('Question', ['class' => 'add_question_form'])?>
    <div style="display: flex; margin-bottom: 20px;">
        <?=$this->Form->input('closeable', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __("Deze vraag afsluiten")?> <span class="fa fa-info-circle ml10" onclick="Popup.load('/questions/closeable_info', 500);" style="cursor:pointer"></span><br />
    </div>

    <?=$this->Form->input('discuss', array('value' => '0','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('decimal_score', array('value' => '0','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('score', array('value' => '0','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('note_type', array('value' => 'NONE','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('subtype', array('label' => false, 'value' => 'none','type' => 'hidden'))?>
    <?=$this->Form->input('is_opensource_content', array('label' => false, 'value' => '0','type' => 'hidden'))?>

    <?=$this->element('teacher_add_question_tabs', ['infoscreen' => true]) ?>

    <div page="question" class="page active" tabs="add_question">
            <span class="title"><?=__('Infoscherm')?></span>
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'autocorrect' => 'off', 'spellcheck' => 'false', 'autocomplete' => 'off')); ?>
        </div>

        <div page="question" class="page" tabs="add_question">
            <span class="title"><?=__('Antwoord')?></span>
            <?=$this->Form->input('answer', array('value' => __("niet van toepassing"),'label' => false, 'type' => 'hidden'))?>
            <?= __("Niet van toepassing")?>
        </div>

        <div page="settings" class="page" tabs="add_question">
            <span class="title"><?= __('Eindtermen') ?></span>
            <?=$this->element('attainments', ['attainments' => $attainments]) ?>
        </div>

    <?=$this->element('question_tab_rtti',[]); ?>

    <?=$this->element('question_editor_tags') ?>

    <?=$this->Form->end();?>

    <?=$this->element('question_editor_attachments', ['owner' => $owner]) ?>

</div>
<?= $this->element('teacher_question_edit_footer', ['saveAction' =>"Questions.add('InfoscreenQuestion', '$owner', '$owner_id');"]) ?>

<script type="text/javascript">

    $('#QuestionSubtype').val(Questions.openType);
    $('#QuestionAttainments').select2();
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

    <? if($owner != 'group') : ?>
        Questions.loadAddAttachments();
    <? endif; ?>

    $('#QuestionQuestion').ckeditor({});

</script>
