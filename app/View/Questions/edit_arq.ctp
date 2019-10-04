<div class="popup-head">ARQ</div>
<div class="popup-content">
    <?=$this->Form->create('Question')?>

        <?
        $options = [];
        for($i = 1; $i <= count($question['question']['multiple_choice_question_answers']); $i++) {
            $options[$i] = $i;
        }
        ?>

        <table class="table mb15">
            <tr>
                <td>
                    <?=$this->Form->input('discuss', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['discuss'] == 1 ? 'checked' : ''))?> Bespreken in de klas<br />
                    <? if($owner != 'group') { ?><?=$this->Form->input('maintain_position', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['maintain_position'] == 1 ? 'checked' : ''))?> Deze vraag vastzetten <br /><? }?>
                    <?=$this->Form->input('decimal_score', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['question']['decimal_score'] == 1 ? 'checked' : ''))?> Halve punten mogelijk<br />
                    <?php if(!$is_open_source_content_creator): ?>
                        <?=$this->Form->input('add_to_database', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['question']['add_to_database'] == 1 ? 'checked' : ''))?> Openbaar maken <span class="fa fa-info-circle" onclick="Popup.load('/questions/public_info', 500);" style="cursor:pointer"></span>
                    <?php endif; ?>
                </td>
                <td width="230">
                    <?=$this->Form->input('note_type', array('type' => 'select', 'value' => $question['question']['note_type'], 'label' => false, 'div' => false, 'options' => [
                        'NONE' => 'Geen kladblok',
                        'TEXT' => 'Tekstvlak',
                        'DRAWING' => 'Tekenvlak'
                    ]))?>
                </td>
            </tr>
        </table>

        <div class="tabs">
            <a href="#" class="btn grey highlight" page="question" tabs="edit_question">
                Vraag
            </a>

            <a href="#" class="btn grey" page="options" tabs="edit_question">
                Antwoorden
            </a>

            <? if($owner != 'group') { ?>
                <a href="#" class="btn grey" page="sources" tabs="edit_question">
                    Bronnen
                </a>
            <? } ?>

            <a href="#" class="btn grey" page="attainments" tabs="edit_question">
                Eindtermen
            </a>


            <a href="#" class="btn grey" page="tags" tabs="edit_question">
                Tags
            </a>

            <a href="#" class="btn grey" page="rtti" tabs="edit_question">
                Taxonomie
            </a>

            <a href="#" class="btn grey" page="owners" tabs="edit_question">
                Info
            </a>
            <br clear="all" />
        </div>

        <div page="question" class="page active" tabs="edit_question">
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'value' => $question['question']['question'])); ?>
        </div>

        <div page="options" class="page" tabs="edit_question">
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
                        <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][0][score]', 'value' => $question['question']['multiple_choice_question_answers'][0]['score']))?>
                    </td>
                </tr>
                <tr>
                    <td>B</td>
                    <td>J</td>
                    <td>J</td>
                    <td>Onjuiste reden</td>
                    <td>
                        <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][1][score]', 'value' => $question['question']['multiple_choice_question_answers'][1]['score']))?>
                    </td>
                </tr>
                <tr>
                    <td>C</td>
                    <td>J</td>
                    <td>O</td>
                    <td>-</td>
                    <td>
                        <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][2][score]', 'value' => $question['question']['multiple_choice_question_answers'][2]['score']))?>
                    </td>
                </tr>
                <tr>
                    <td>D</td>
                    <td>O</td>
                    <td>J</td>
                    <td>-</td>
                    <td>
                        <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][3][score]', 'value' => $question['question']['multiple_choice_question_answers'][3]['score']))?>
                    </td>
                </tr>
                <tr>
                    <td>E</td>
                    <td>O</td>
                    <td>O</td>
                    <td>-</td>
                    <td>
                        <?=$this->Form->input('', array('style' => 'width: 30px;', 'label' => false, 'name' => 'data[Question][answers][4][score]', 'value' => $question['question']['multiple_choice_question_answers'][4]['score']))?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div page="attainments" class="page" tabs="edit_question">
            <?=$this->element('attainments', ['attainments' => $attainments, 'selectedAttainments' => $selectedAttainments]) ?>
        </div>

        <?=$this->element('question_tab_rtti',['question' => $question]); ?>

    <div page="tags" class="page" tabs="edit_question">
        <?=$this->Form->input('tags', array('label' => false, 'type' => 'select', 'multiple' => true, 'style' => 'width:750px;', 'options' => $question['question']['tags'], 'value' => $question['question']['tags']))?>
    </div>


    <div page="owners" class="page" tabs="edit_question">
        <?=$this->element('question_info', ['question' => $question])?>
    </div>

        <?=$this->Form->end();?>

        <? if($owner != 'group') { ?>
            <div page="sources" class="page" tabs="edit_question"></div>
        <? } ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <? if($editable) { ?>
        <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.edit('<?=$owner?>', <?=$owner_id?>, 'ARQQuestion', <?=$question['id']?>);">
            Vraag opslaan
        </a>
    <? } ?>
</div>

<script type="text/javascript">

    <? if(!$editable) { ?>
    $('.popup-content input, .popup-content select, .popup-content textarea').attr({'disabled' : true});
    <? } ?>

    <? if($owner != 'group') { ?>
        Questions.loadEditAttachments('<?=$owner?>', <?=$owner_id?>, <?=$question['id']?>);
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
