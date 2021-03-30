<div class="popup-head"></div>
<div class="popup-content">

    <?=$this->Form->create('Question')?>
        <table class="table mb15">
            <tr>
                <th width="10%">
                    Punten
                </th>
                <td width="110">
                    <?=$this->Form->input('score', array('style' => 'width:50px;', 'value' => 5, 'label' => false, 'verify' => 'notempty'))?>
                </td>
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

                <?=$this->Form->input('subtype', array('label' => false, 'type' => 'hidden'))?>
            </tr>
        </table>

        <div class="tabs">
            <a href="#" class="btn grey highlight" page="question" tabs="add_question">
                Vraag
            </a>

            <a href="#" class="btn grey" page="options" tabs="add_question">
                Antwoord

            </a>
            <? if($owner != 'group') { ?>
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
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'autocorrect' => 'off', 'spellcheck' => 'false', 'autocomplete' => 'off')); ?>
        </div>

        <div page="options" class="page" tabs="add_question">
            <?=$this->Form->input('answer', array('style' => 'width:737px; height: 100px;', 'type' => 'textarea', 'div' => false, 'label' => false)); ?>
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
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.add('OpenQuestion', '<?=$owner?>', '<?=$owner_id?>');">
        Vraag opslaan
    </a>
</div>

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

    $('#QuestionQuestion, #QuestionAnswer').ckeditor({});

    $('.popup-head').html('Open vraag');
</script>
