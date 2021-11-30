<?= $this->element('teacher_question_edit_header', ['question_type' =>  __("Juist / Onjuist"), 'test_name' => $test_name]) ?>
<!--<div class="popup-head">--><?//= __("Juist / Onjuist")?><!--</div>-->
<div class="popup-content" style="margin: 0 auto; max-width:1000px;padding-bottom: 80px;">
    <?=$this->Form->create('Question', ['class' => 'add_question_form'])?>

        <table class="table mb15">
            <tr>
                <th width="10%">
                <?= __("Punten")?>
                </th>
                <td width="110">
                    <?=$this->Form->input('score', array('style' => 'width:50px;', 'value' => 5, 'label' => false, 'verify' => 'notempty'))?>
                </td>
                <td>
                    <?=$this->Form->input('closeable', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __("Deze vraag afsluiten")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/closeable_info', 500);" style="cursor:pointer"></span><br />
                    <?=$this->Form->input('discuss', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => 'checked'))?>  <?= __("Bespreken in de klas")?> <br />
                    <? if($owner == 'test') { ?><?=$this->Form->input('maintain_position', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __("Deze vraag vastzetten")?><br /><? } ?>
                    <?=$this->Form->input('decimal_score', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __("Halve punten mogelijk")?><br />
                    <?php if(!$is_open_source_content_creator): ?>
                        <?=$this->Form->input('add_to_database', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'checked' => true, 'div' => false))?> <?= __("Openbaar maken")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/public_info', 500);" style="cursor:pointer"></span><br/>
                    <?php endif; ?>
                </td>

                <td width="230">
                    <?=$this->Form->input('note_type', array('type' => 'select', 'label' => false, 'div' => false, 'options' => [
                        'NONE' => __("Geen kladblok"),
                        'TEXT' => __("Tekstvlak"),
                        'DRAWING' => __("Tekenvlak")
                    ]))?>
                </td>
            </tr>
        </table>

        <?=$this->element('teacher_add_question_tabs') ?>

        <div page="question" class="page active" tabs="add_question">
            <span class="title"><?=__('Vraag')?></span>
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false)); ?>
        </div>

        <div page="question" class="page" tabs="add_question">
            <span class="title"><?=__('Antwoord')?></span>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100">
                    <?= __("Antwoord is:")?>
                    </td>
                    <td>
                        <?=$this->Form->input('answer', [
                            'options' => [
                                1 => __("Juist"),
                                0 => __("Onjuist")
                            ],
                            'label' => false
                        ])?>
                    </td>
                </tr>
            </table>
        </div>

        <div page="settings" class="page" tabs="add_question">
            <span class="title"><?= __('Eindtermen') ?></span>
            <?=$this->element('attainments', ['attainments' => $attainments, 'selectedAttainments' => $selectedAttainments]) ?>
        </div>

        <?=$this->element('question_tab_rtti',[]); ?>

        <?=$this->element('question_editor_tags') ?>

        <?=$this->Form->end();?>

        <?=$this->element('question_editor_attachments') ?>
</div>
<?= $this->element('teacher_question_edit_footer', ['saveAction' =>"Questions.add('TrueFalseQuestion', '$owner', '$owner_id');"]) ?>

<script type="text/javascript">
    <? if($owner != 'group') { ?>
        Questions.loadAddAttachments();
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

    $('#QuestionAttainments').select2();

    $('#QuestionQuestion').ckeditor({});
</script>
