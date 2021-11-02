<div class="popup-head">Juist / Onjuist</div>
<div class="popup-content">
    <?=$this->Form->create('Question', array('id' => $is_clone_request ? 'QuestionAddForm' : 'QuestionEditForm'))?>

        <table class="table mb15">
            <tr>
                <th width="10%">
                    Punten
                </th>
                <td width="110">
                    <?=$this->Form->input('score', array('style' => 'width:50px;', 'value' => $question['question']['score'], 'label' => false, 'verify' => 'notempty'))?>
                </td>
                <td>
                    <?=$this->Form->input('closeable', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['question']['closeable'] == 1 ? 'checked' : ''))?> Deze vraag afsluiten <span class="fa fa-info-circle" onclick="Popup.load('/questions/closeable_info', 500);" style="cursor:pointer"></span><br />
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

        <?
        function isJuist($answer){
            return strtolower($answer['answer']) == 'juist' || strtolower(strip_tags($answer['answer'])) == 'waar';
        }

        function isOnjuist($answer){
            return strtolower($answer['answer']) == 'onjuist' || strtolower(strip_tags($answer['answer'])) == 'niet waar';
        }

        $value = 0;
        foreach($question['question']['multiple_choice_question_answers'] as $answer) {

            if(isJuist($answer) && $answer['score'] > 0) {
                $value = 1;
            }

            if(isOnjuist($answer) && $answer['score'] > 0) {
                $value = 0;
            }
        }
        ?>

        <div class="tabs">
            <a href="#" class="btn grey highlight" page="question" tabs="edit_question">
                Vraag
            </a>

            <a href="#" class="btn grey" page="answer" tabs="edit_question">
                Antwoord
            </a>

            <a href="#" class="btn grey" page="sources" tabs="edit_question">
                Bronnen
            </a>

            <a href="#" class="btn grey" page="attainments" tabs="edit_question">
                Eindtermen
            </a>

            <a href="#" class="btn grey" page="tags" tabs="edit_question">
                Tags
            </a>

            <a href="#" class="btn grey" page="rtti" tabs="edit_question">
                Taxonomie
            </a>

            <?php if(!$is_clone_request) { ?>
            <a href="#" class="btn grey" page="owners" tabs="edit_question">
                Info
            </a>
            <?php } ?>
            <br clear="all" />
        </div>

        <div page="question" class="page active" tabs="edit_question">
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'value' => $question['question']['question'], 'div' => false, 'label' => false)); ?>
        </div>

        <div page="answer" class="page" tabs="edit_question">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100">
                        Antwoord is:
                    </td>
                    <td>
                        <?=$this->Form->input('answer', [
                            'options' => [
                                1 => 'Juist',
                                0 => 'Onjuist'
                            ],
                            'value' => $value,
                            'label' => false
                        ])?>
                    </td>
                </tr>
            </table>
        </div>

        <div page="attainments" class="page" tabs="edit_question">
            <?=$this->element('attainments', ['attainments' => $attainments, 'selectedAttainments' => $selectedAttainments]) ?>
        </div>

    <?=$this->element('question_tab_rtti',['question' => $question]); ?>

        <div page="tags" class="page" tabs="edit_question">
            <?=$this->Form->input('tags', array('label' => false, 'type' => 'select', 'multiple' => true, 'style' => 'width:750px;', 'options' => $question['question']['tags'], 'value' => $question['question']['tags']))?>
        </div>

    <?php  if(!$is_clone_request) { ?>
    <div page="owners" class="page" tabs="edit_question">
        <?=$this->element('question_info', ['question' => $question])?>
    </div>
    <?php } ?>
    <?=$this->Form->end();?>

    <div page="sources" class="page" tabs="edit_question"></div>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    
    <? if($is_clone_request){ ?>
        <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.add('TrueFalseQuestion', '<?=$owner?>', '<?=$owner_id?>');">
            Vraag opslaan
        </a>
    <? }else{ ?>
        <? if($editable) { ?>
            <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.edit('<?=$owner?>', '<?=$owner_id?>', 'TrueFalseQuestion', '<?=getUUID($question, 'get');?>');">
                Vraag opslaan
            </a>
        <? } ?>
    <? } ?>
</div>

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
