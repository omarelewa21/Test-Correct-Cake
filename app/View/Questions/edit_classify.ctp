<div class="popup-head">Rubriceervraag</div>
<div class="popup-content">
    <?=$this->Form->create('Question')?>

        <table class="table mb15">
            <tr>
                <th width="10%">
                    Punten
                </th>
                <td width="110">
                    <?=$this->Form->input('score', array('style' => 'width:50px;', 'value' => $question['question']['score'], 'label' => false, 'verify' => 'notempty'))?>
                </td>
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
            <table class="table" id="tableMatchingOptions">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Onderwerp</th>
                        <th>Mogelijkheden</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    for($i = 0; $i < 50; $i++) {

                        $left = "";
                        $right = [];

                        $display = false;

                        if(isset($question['question']['matching_question_answers'][$i]['answer']) && $question['question']['matching_question_answers'][$i]['type'] == 'LEFT') {
                            $left = $question['question']['matching_question_answers'][$i];

                            if(isset($left['answer']) && $left['answer'] != '') {
                                $display = true;
                            }

                            for($a = 0; $a < 100; $a++) {

                                if (isset($question['question']['matching_question_answers'][$a]['answer']) &&
                                    $question['question']['matching_question_answers'][$a]['type'] == 'RIGHT' &&
                                    $question['question']['matching_question_answers'][$a]['correct_answer_id'] == $left['id'])
                                {
                                    $right[] = $question['question']['matching_question_answers'][$a]['answer'];
                                }
                            }
                        }

                        $right = implode("\n", $right);

                        ?>
                        <tr style="<?=$display ? '' : 'display:none;' ?>">
                            <td valign="top">
                                <span class="fa fa-arrows"></span>
                            </td>
                            <td valign="top">
                                <?=$this->Form->input('', array('type' => 'hidden','label' => false, 'name' => 'data[Question][answers]['.$i.'][order]', 'value' => $i, 'class' => 'order'))?>
                                <?=$this->Form->input('', array('style' => 'width: 300px;', 'label' => false, 'name' => 'data[Question][answers]['.$i.'][left]', 'value' => isset($left['answer']) ? $left['answer'] : ''))?>
                            </td>
                            <td valign="top">
                                <?=$this->Form->input('', array('style' => 'width: 300px; height:65px;', 'label' => false, 'type' => 'textarea', 'name' => 'data[Question][answers]['.$i.'][right]', 'value' => $right))?>
                            </td>
                            <td valign="top">
                                <? if($editable) { ?>
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
            <? if($editable) { ?>
                <center>
                    <a href="#" class="btn highlight small inline-block" onclick="Questions.addMatchingOption();">
                        <span class="fa fa-plus"></span>
                        Optie toevoegen
                    </a>
                </center>
            <? } ?>
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
        <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.edit('<?=$owner?>', <?=$owner_id?>, 'MatchingQuestion', <?=$question['id']?>);">
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

    $('#tableMatchingOptions tbody').sortable({
        delay: 150,
        handle: "span",
        stop: function( event, ui ) {
            Questions.updateMatchingOrder();
        }
    });

    $('#QuestionQuestion').ckeditor({});
</script>