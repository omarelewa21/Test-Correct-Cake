<?= $this->element('teacher_question_edit_header', ['question_type' =>  __("Infoscherm"), 'test_name' => $test_name]) ?>
<!--<div class="popup-head">--><?//= __("Infoscherm")?><!--</div>-->
<div class="popup-content" style="margin: 0 auto; max-width:1000px;padding-bottom: 80px;">

    <?=$this->Form->create('Question')?>

    <?=$this->Form->input('closeable', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __("Deze vraag afsluiten")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/closeable_info', 500);" style="cursor:pointer"></span><br />
    
    <?=$this->Form->input('discuss', array('value' => '0','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('decimal_score', array('value' => '0','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('score', array('value' => '0','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('note_type', array('value' => 'NONE','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('subtype', array('label' => false, 'value' => 'none','type' => 'hidden'))?>
    <?=$this->Form->input('is_opensource_content', array('label' => false, 'value' => '0','type' => 'hidden'))?>

        <div class="tabs">
            <a href="#" class="btn grey highlight" page="question" tabs="add_question">
            <?= __("Info")?>
            </a>

            <a href="#" class="btn grey" page="options" tabs="add_question">
            <?= __("Antwoord")?>

            </a>
            <? if($owner != 'group') { ?>
                <a href="#" class="btn grey" page="sources" tabs="add_question">
                <?= __("Bronnen")?>
                </a>
            <? } ?>

            <a href="#" class="btn grey" page="attainments" tabs="add_question">
            <?= __("Eindtermen")?>
            </a>

            <a href="#" class="btn grey" page="tags" tabs="add_question">
            <?= __("Tags")?>
            </a>

            <a href="#" class="btn grey" page="rtti" tabs="add_question">
            <?= __("Taxonomie")?>
            </a>

            <br clear="all" />
        </div>

        <div page="question" class="page active" tabs="add_question">
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'autocorrect' => 'off', 'spellcheck' => 'false', 'autocomplete' => 'off')); ?>
        </div>

        <div page="options" class="page" tabs="add_question">
            <?=$this->Form->input('answer', array('value' => __("niet van toepassing"),'label' => false, 'type' => 'hidden'))?>
            <?= __("Niet van toepassing")?>
        </div>

        <div page="attainments" class="page" tabs="add_question">
            <?=$this->element('attainments', ['attainments' => $attainments]) ?>
        </div>

        <?=$this->element('question_tab_rtti',[]); ?>

        <div page="tags" class="page" tabs="add_question">
            <?=$this->Form->input('tags', array('label' => false, 'type' => 'select', 'multiple' => true, 'style' => 'width:750px;'))?>
        </div>

    <?=$this->Form->end();?>

    <div page="sources" class="page" tabs="add_question"></div>

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
