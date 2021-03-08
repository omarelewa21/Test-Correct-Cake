<div class="popup-head">ARQ Choice</div>
<div class="popup-content">
    <?=$this->Form->create('Question')?>

        <table class="table mb15">
            <tr>
                <td>
                    <?=$this->Form->input('closeable', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> Deze vraag afsluiten <span class="fa fa-info-circle" onclick="Popup.load('/questions/closeable_info', 500);" style="cursor:pointer"></span><br />
                    <?=$this->Form->input('discuss', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => 'checked'))?>  Bespreken in de klas <br />
                    <? if($owner == 'test') { ?><?=$this->Form->input('maintain_position', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> Deze vraag vastzetten<br /><? } ?>
                    <?=$this->Form->input('decimal_score', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> Halve punten mogelijk<br />

                    <?php if(!$is_open_source_content_creator): ?>
                        <?=$this->Form->input('add_to_database', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'checked' => true, 'div' => false))?> Openbaar maken <span class="fa fa-info-circle" onclick="Popup.load('/questions/public_info', 500);" style="cursor:pointer"></span><br />
                    <?php endif; ?>
                </td>

                <td width="230">
                    <?=$this->Form->input('note_type', array('type' => 'select', 'label' => false, 'div' => false, 'options' => [
                        'NONE' => 'Geen kladblok',
                        'TEXT' => 'Tekstvlak',
                        'DRAWING' => 'Tekenvlak'
                    ]))?>
                </td>
            </tr>
        </table>

        <div class="tabs">
            <a href="#" class="btn grey highlight" page="question" tabs="add_question">
                Vraag
            </a>

            <a href="#" class="btn grey" page="options" tabs="add_question">
                Antwoorden
            </a>

            <? if($owner == 'test') { ?>
                <a href="#" class="btn grey" page="sources" tabs="add_question">
                    Bronnen
                </a>
            <? } ?>

            <a href="#" class="btn grey" page="attainments" tabs="add_question">
                Eindtermen
            </a>

            <a href="#" class="btn grey" page="tags" tabs="add_question">
                Tags
            </a>

            <a href="#" class="btn grey" page="rtti" tabs="add_question">
                Taxonomie
            </a>
            <br clear="all" />
        </div>

        <div page="question" class="page active" tabs="add_question">
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false)); ?>
        </div>

        <div page="options" class="page" tabs="add_question">
            <table class="table" id="tableMultiChoiceOptions">
                <thead>
                    <tr>
                        <th width="40">&nbsp;</th>
                        <th width="40">St. 1</th>
                        <th width="40">St. 2</th>
                        <th>Reden</th>
                        <th width="40">Score</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>A</td>
                        <td>J</td>
                        <td>J</td>
                        <td>Juiste reden</td>
                        <td>
                            <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][0][score]', 'value' => 0))?>
                        </td>
                    </tr>
                    <tr>
                        <td>B</td>
                        <td>J</td>
                        <td>J</td>
                        <td>Onjuiste reden</td>
                        <td>
                            <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][1][score]', 'value' => 0))?>
                        </td>
                    </tr>
                    <tr>
                        <td>C</td>
                        <td>J</td>
                        <td>O</td>
                        <td>-</td>
                        <td>
                            <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][2][score]', 'value' => 0))?>
                        </td>
                    </tr>
                    <tr>
                        <td>D</td>
                        <td>O</td>
                        <td>J</td>
                        <td>-</td>
                        <td>
                            <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][3][score]', 'value' => 0))?>
                        </td>
                    </tr>
                    <tr>
                        <td>E</td>
                        <td>O</td>
                        <td>O</td>
                        <td>-</td>
                        <td>
                            <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][4][score]', 'value' => 0))?>
                        </td>
                    </tr>
                </tbody>
            </table>
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
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.add('ARQQuestion', '<?=$owner?>', '<?=$owner_id?>');">
        Vraag opslaan
    </a>
</div>

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
