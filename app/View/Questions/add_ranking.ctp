<?= $this->element('teacher_question_edit_header', ['question_type' =>  __("Rangschik vraag"), 'test_name' => $test_name]) ?>
<!--<div class="popup-head">--><?//= __("Rangschik vraag")?><!--</div>-->
<div class="popup-content" style="margin: 0 auto; max-width:1000px;padding-bottom: 80px;">
    <?=$this->Form->create('Question')?>
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

        <div class="tabs">
            <a href="#" class="btn grey highlight" page="question" tabs="add_question">
            <?= __("Vraag")?>
            </a>

            <a href="#" class="btn grey" page="options" tabs="add_question">
            <?= __("Antwoorden")?>
            </a>

            <? if($owner == 'test') { ?>
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
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false)); ?>
        </div>

        <div page="options" class="page" tabs="add_question">
            <table class="table" id="tableRankingOptions">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th><?= __("Antwoord")?></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    for($i = 0; $i < 10; $i++) {
                        ?>
                        <tr style="<?=($i == 0) ? '' : 'display:none;' ?>">
                            <td>
                                <span class="fa fa-arrows"></span>
                            </td>
                            <td>
                                <?=$this->Form->input('', array('type' => 'hidden','label' => false, 'name' => 'data[Question][answers]['.$i.'][order]', 'value' => $i, 'class' => 'order'))?>
                                <?=$this->Form->input('', array('style' => 'width: 640px;', 'label' => false, 'name' => 'data[Question][answers]['.$i.'][answer]'))?>
                            </td>
                            <td>
                                <a href="#" class="btn red small" onclick="Questions.removeRankingOption(this);">
                                    <span class="fa fa-remove"></span>
                                </a>
                            </td>
                        </tr>
                        <?
                    }
                    ?>
                </tbody>
            </table>

            <center>
                <a href="#" class="btn highlight small inline-block" onclick="Questions.addRankingOption();">
                    <span class="fa fa-plus"></span>
                    <?= __("Optie toevoegen")?>
                </a>
            </center>
        </div>

        <div page="attainments" class="page" tabs="add_question">
            <?=$this->element('attainments', ['attainments' => $attainments, 'selectedAttainments' => $selectedAttainments]) ?>
        </div>

        <?=$this->element('question_tab_rtti',[]); ?>

        <div page="tags" class="page" tabs="add_question">
            <?=$this->Form->input('tags', array('label' => false, 'type' => 'select', 'multiple' => true, 'style' => 'width:750px;'))?>
        </div>

        <?=$this->Form->end();?>

        <div page="sources" class="page" tabs="add_question"></div>
</div>
<?= $this->element('teacher_question_edit_footer', ['saveAction' =>"Questions.add('RankingQuestion', '$owner', '$owner_id');"]) ?>

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

    $('#tableRankingOptions tbody').sortable({
        delay: 150,
        handle: "span",
        stop: function( event, ui ) {
            Questions.updateRankingOrder();
        }
    });
    $('#QuestionQuestion').ckeditor({});
</script>
