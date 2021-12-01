<?= $this->element('teacher_question_edit_header', ['question_type' =>  __("Rubriceervraag"), 'test_name' => $test_name]) ?>
<!--<div class="popup-head">--><?//= __("Rubriceervraag")?><!--</div>-->
<div class="popup-content" style="margin: 0 auto; max-width:1000px;padding-bottom: 80px;">
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
        <span class="title"><?= __('Vraag') ?></span>
        <?= $this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'value' => $question['question']['question'])); ?>
    </div>

    <div page="question" class="page" tabs="edit_question">
        <span class="title"><?= __('Antwoord')?></span>
        <table class="table" id="tableMatchingOptions">
            <thead>
            <tr>
                <th>&nbsp;</th>
                <th><?= __("Onderwerp") ?></th>
                <th><?= __("Mogelijkheden") ?></th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?
            for ($i = 0; $i < 50; $i++) {

                $left = "";
                $right = [];

                $display = false;

                if (isset($question['question']['matching_question_answers'][$i]['answer']) && $question['question']['matching_question_answers'][$i]['type'] == 'LEFT') {
                    $left = $question['question']['matching_question_answers'][$i];

                    if (isset($left['answer']) && $left['answer'] != '') {
                        $display = true;
                    }

                    for ($a = 0; $a < 100; $a++) {

                        if (isset($question['question']['matching_question_answers'][$a]['answer']) &&
                            $question['question']['matching_question_answers'][$a]['type'] == 'RIGHT' &&
                            $question['question']['matching_question_answers'][$a]['correct_answer_id'] == $left['id']) {
                            $right[] = $question['question']['matching_question_answers'][$a]['answer'];
                        }
                    }
                }

                $right = implode("\n", $right);

                ?>
                <tr style="<?= $display ? '' : 'display:none;' ?>">
                    <td valign="top">
                        <span class="fa fa-arrows"></span>
                    </td>
                    <td valign="top">
                        <?= $this->Form->input('', array('type' => 'hidden', 'label' => false, 'name' => 'data[Question][answers][' . $i . '][order]', 'value' => $i, 'class' => 'order')) ?>
                        <?= $this->Form->input('', array('style' => 'width: 300px;', 'label' => false, 'name' => 'data[Question][answers][' . $i . '][left]', 'value' => isset($left['answer']) ? $left['answer'] : '')) ?>
                    </td>
                    <td valign="top">
                        <?= $this->Form->input('', array('style' => 'width: 300px; height:65px;', 'label' => false, 'type' => 'textarea', 'name' => 'data[Question][answers][' . $i . '][right]', 'value' => $right)) ?>
                    </td>
                    <td valign="top">
                        <? if ($editable) { ?>
                            <a href="#" class="btn red small" onclick="Questions.removeMatchingOption(this);">
                                <span class="fa fa-remove"></span>
                            </a>
                        <? } ?>
                    </td>
                </tr>
                <?
            }
            ?>
            </tbody>
        </table>
        <? if ($editable) { ?>
            <center>
                <a href="#" class="btn highlight small inline-block" onclick="Questions.addMatchingOption();">
                    <span class="fa fa-plus"></span>
                    <?= __("Optie toevoegen") ?>
                </a>
            </center>
        <? } ?>
    </div>

    <div page="settings" class="page" tabs="edit_question">
        <span class="title"><?= __('Eindtermen')?></span>
        <?= $this->element('attainments', ['attainments' => $attainments, 'selectedAttainments' => $selectedAttainments]) ?>
    </div>

    <?= $this->element('question_tab_rtti', ['question' => $question]); ?>

    <div page="settings" class="page" tabs="edit_question">
        <span class="title"><?= __('Tags')?></span>
        <?= $this->Form->input('tags', array('label' => false, 'type' => 'select', 'multiple' => true, 'style' => 'width:750px;', 'options' => $question['question']['tags'], 'value' => $question['question']['tags'])) ?>
    </div>


    <div page="info" class="page" tabs="edit_question">
        <span class="title"><?= __('Info')?></span>
        <?= $this->element('question_info', ['question' => $question]) ?>
    </div>

    <?= $this->Form->end(); ?>

    <? if ($owner != 'group') { ?>
        <?= $this->element('question_editor_attachments', ['edit' => true]) ?>
    <? } ?>
</div>
<? if ($is_clone_request) { ?>
    <?= $this->element('teacher_question_edit_footer', ['saveAction' =>"Questions.add('ClassifyQuestion', '$owner', '$owner_id');"]) ?>
<? } else { ?>
    <? if ($editable) { ?>
        <?= $this->element('teacher_question_edit_footer', ['saveAction' => "Questions.edit('$owner', '$owner_id', 'ClassifyQuestion', '".getUUID($question, 'get')."')"]) ?>
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

    $('#tableMatchingOptions tbody').sortable({
        delay: 150,
        handle: "span",
        stop: function( event, ui ) {
            Questions.updateMatchingOrder();
        }
    });

    $('#QuestionQuestion').ckeditor({});
</script>
