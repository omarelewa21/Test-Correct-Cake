<?= $this->element('teacher_question_edit_header', ['question_type' =>  __("Tekenvraag"), 'test_name' => $test_name, 'icon' => $editable ? 'edit' : 'preview', 'editable' => $editable]) ?>
<!--<div class="popup-head">--><?//= __("Tekenvraag")?><!--</div>-->
<div class="<?= $editable ? '' : 'popup-content non-edit' ; ?>" style="margin: 0 auto; max-width:1000px; <?= $editable ? 'padding-bottom: 80px;' : '' ; ?>">

    <?=$this->Form->create('Question', array('id' => $is_clone_request ? 'QuestionAddForm' : 'QuestionEditForm', 'class' => 'add_question_form'))?>

    <table class="table mb15">
        <tr>
            <th width="10%">
            <?= __("Punten")?>
            </th>
            <td width="110">
                <?=$this->Form->input('score', array('style' => 'width:50px;', 'value' => $question['question']['score'], 'label' => false, 'verify' => 'notempty'))?>
            </td>
            <td>
                <?=$this->Form->input('closeable', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['question']['closeable'] == 1 ? 'checked' : ''))?> <?= __("Deze vraag afsluiten")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/closeable_info', 500);" style="cursor:pointer"></span><br />
                <?=$this->Form->input('discuss', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['discuss'] == 1 ? 'checked' : ''))?> <?= __("Bespreken in de klas")?><br />
                <? if($owner != 'group') { ?><?=$this->Form->input('maintain_position', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['maintain_position'] == 1 ? 'checked' : ''))?> <?= __("Deze vraag vastzetten")?> <br /><? }?>
                <?=$this->Form->input('decimal_score', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['question']['decimal_score'] == 1 ? 'checked' : ''))?> <?= __("Halve punten mogelijk")?><br />
                <?php if(!$is_open_source_content_creator): ?>
                        <?=$this->Form->input('add_to_database', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['question']['add_to_database'] == 1 ? 'checked' : ''))?> <?= __("Openbaar maken")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/public_info', 500);" style="cursor:pointer"></span>
                    <?php endif; ?>
            </td>
            <td width="230">
                <?=$this->Form->input('note_type', array('type' => 'select', 'value' => $question['question']['note_type'], 'label' => false, 'div' => false, 'options' => [
                    'NONE' => __("Geen kladblok"),
                    'TEXT' => __("Tekstvlak"),
                    'DRAWING' => __("Tekenvlak")
                ]))?>
            </td>
        </tr>
    </table>

    <?= $this->element('teacher_add_question_tabs', ['cloneRequest' => $is_clone_request, 'edit' => true]) ?>

    <div page="question" class="page active" tabs="edit_question">
        <span class="title"><?= __('Vraag')?></span>
        <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'value' => $question['question']['question'])); ?>
    </div>

    <div page="question" class="page active" tabs="edit_question">
        <span class="title"><?= __('Antwoord')?></span>
        <? if($editable) { ?>
            <center>
                <a href="#" class="btn highlight" onclick="Popup.load('/questions/add_drawing_answer', 1220)">
                <?= __("Antwoord wijzigen")?>
                </a>
            </center>
        <? } ?>
    </div>

    <div page="settings" class="page" tabs="edit_question">
        <span class="title"><?= __('Info') ?></span>
        <?= $this->element('question_info', ['question' => $question]) ?>
    </div>

    <div page="settings" class="page" tabs="edit_question">
        <span class="title"><?= __('Eindtermen')?></span>
        <?=$this->element('attainments', ['attainments' => $attainments, 'selectedAttainments' => $selectedAttainments]) ?>
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
    <?= $this->element('teacher_question_edit_footer', ['saveAction' =>"Questions.add('DrawingQuestion', '$owner', '$owner_id');"]) ?>
<? } else { ?>
    <? if ($editable) { ?>
        <?= $this->element('teacher_question_edit_footer', ['saveAction' => "Questions.edit('$owner', '$owner_id', 'DrawingQuestion', '".getUUID($question, 'get')."')"]) ?>
    <? } else { ?>
        <?= $this->element('teacher_question_edit_footer', ['saveAction' => '', 'editable' => $editable]) ?>
    <? } ?>
<? } ?>

<script type="text/javascript">

    <?php if(!$editable) { ?>
        $('.popup-content input, .popup-content select, .popup-content textarea').not('.disable_protect').attr({'disabled' : true});
    <?php } ?>

    <? if($is_clone_request){ ?>
        Questions.loadAddAttachments(true,'<?=$owner?>', '<?=$owner_id?>', '<?=getUUID($question, 'get');?>');
    <? }else{ ?>
        <? if($owner != 'group') { ?>
            Questions.loadEditAttachments('<?=$owner?>', '<?=$owner_id?>', '<?=getUUID($question, 'get');?>');
        <? } ?>
    <? } ?>

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

    $('#QuestionQuestion').ckeditor({});
</script>
