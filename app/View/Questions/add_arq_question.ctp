<?= $this->element('teacher_question_edit_header', ['question_type' =>  __("ARQ Choice"), 'test_name' => $test_name]) ?>
<!--<div class="popup-head">--><?//= __("ARQ Choice")?><!--</div>-->
<div style="margin: 0 auto; max-width:1000px;padding-bottom: 80px;">
    <?=$this->Form->create('Question', ['class' => 'add_question_form'])?>

        <table class="table mb15">
            <tr>
                <td>
                    <?=$this->Form->input('closeable', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __("Deze vraag afsluiten")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/closeable_info', 500);" style="cursor:pointer"></span><br />
                    <?=$this->Form->input('discuss', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => 'checked'))?>  <?= __("Bespreken in de klas")?> <br />
                    <? if($owner == 'test') { ?><?=$this->Form->input('maintain_position', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __(" Deze vraag vastzetten")?><br /><? } ?>
                    <?=$this->Form->input('decimal_score', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __("Halve punten mogelijk")?><br />

                    <?php if(!$is_open_source_content_creator): ?>
                        <?=$this->Form->input('add_to_database', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'checked' => true, 'div' => false))?> <?= __("Openbaar maken")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/public_info', 500);" style="cursor:pointer"></span><br />
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

    <?= $this->element('teacher_add_question_tabs') ?>

        <div page="question" class="page active" tabs="add_question">
            <span class="title"><?=__('Vraag')?></span>
            <?= $this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false)); ?>
        </div>

        <div page="question" class="page" tabs="add_question">
            <span class="title"><?=__('Antwoord')?></span>
            <table class="table" id="tableMultiChoiceOptions">
                <thead>
                    <tr>
                        <th width="40">&nbsp;</th>
                        <th width="40">St. 1</th>
                        <th width="40">St. 2</th>
                        <th><?= __("Reden")?></th>
                        <th width="40"><?= __("Score ")?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>A</td>
                        <td><?= __('J') ?></td>
                        <td><?= __('J') ?></td>
                        <td><?= __("Juiste reden")?></td>
                        <td>
                            <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][0][score]', 'value' => 0))?>
                        </td>
                    </tr>
                    <tr>
                        <td>B</td>
                        <td><?= __('J') ?></td>
                        <td><?= __('J') ?></td>
                        <td><?= __("Onjuiste reden")?></td>
                        <td>
                            <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][1][score]', 'value' => 0))?>
                        </td>
                    </tr>
                    <tr>
                        <td>C</td>
                        <td><?= __('J') ?></td>
                        <td><?= __('O') ?></td>
                        <td>-</td>
                        <td>
                            <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][2][score]', 'value' => 0))?>
                        </td>
                    </tr>
                    <tr>
                        <td>D</td>
                        <td><?= __('O') ?></td>
                        <td><?= __('J') ?></td>
                        <td>-</td>
                        <td>
                            <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][3][score]', 'value' => 0))?>
                        </td>
                    </tr>
                    <tr>
                        <td>E</td>
                        <td><?= __('O') ?></td>
                        <td><?= __('O') ?></td>
                        <td>-</td>
                        <td>
                            <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][4][score]', 'value' => 0))?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div page="settings" class="page" tabs="add_question">
            <span class="title"><?= __('Eindtermen') ?></span>
            <?=$this->element('attainments', ['attainments' => $attainments, 'selectedAttainments' => $selectedAttainments]) ?>
        </div>

        <?= $this->element('question_tab_rtti',[]); ?>

        <?= $this->element('question_editor_tags') ?>

        <?= $this->Form->end();?>

        <?= $this->element('question_editor_attachments') ?>
</div>
<?= $this->element('teacher_question_edit_footer', ['saveAction' =>"Questions.add('ARQQuestion', '$owner', '$owner_id');"]) ?>


<script type="text/javascript">
    <? if($owner != 'group') { ?>
        Questions.loadAddAttachments();
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

    $('#tableMultiChoiceOptions tbody').sortable({
        delay: 150,
        handle: "span",
        stop: function( event, ui ) {
            Questions.updateMultiChoiceOrder();
        }
    });

    $('#QuestionQuestion').ckeditor({});
</script>
