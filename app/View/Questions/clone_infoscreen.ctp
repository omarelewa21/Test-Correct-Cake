<div class="popup-head">Infoscherm</div>
<div class="popup-content">

    <?=$this->Form->create('Question', array('id' => 'QuestionAddForm'))?>


    <?=$this->Form->input('closeable', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> Deze vraag afsluiten <span class="fa fa-info-circle" onclick="Popup.load('/questions/closeable_info', 500);" style="cursor:pointer"></span><br />
    
                <?=$this->Form->input('discuss', array('value' => '0','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('decimal_score', array('value' => '0','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('score', array('value' => '0','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('note_type', array('value' => 'NONE','label' => false, 'type' => 'hidden'))?>
    <?=$this->Form->input('subtype', array('label' => false, 'value' => 'none','type' => 'hidden'))?>
    <?=$this->Form->input('is_opensource_content', array('label' => false, 'value' => '0','type' => 'hidden'))?>

        <div class="tabs">
            <a href="#" class="btn grey highlight" page="question" tabs="edit_question">
                Info
            </a>

            <a href="#" class="btn grey" page="options" tabs="edit_question">
                Antwoord
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
            <?=$this->Form->input('answer', array('value' => $question['question']['answer'],'label' => false, 'type' => 'hidden'))?>
            Niet van toepassing
        </div>

        <div page="attainments" class="page" tabs="edit_question">
            <?=$this->element('attainments', ['attainments' => $attainments, 'selectedAttainments' => $selectedAttainments]) ?>
        </div>

        <div page="owners" class="page" tabs="edit_question">
            <?=$this->element('question_info', ['question' => $question])?>
        </div>

    <?=$this->element('question_tab_rtti',['question' => $question]); ?>

        <div page="tags" class="page" tabs="edit_question">
            <?=$this->Form->input('tags', array('label' => false, 'type' => 'select', 'multiple' => true, 'style' => 'width:750px;', 'options' => $question['question']['tags'], 'value' => $question['question']['tags']))?>
        </div>

        <?=$this->Form->end();?>

        <? if($owner != 'group') { ?>
            <div page="sources" class="page" tabs="edit_question"></div>
        <? } ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();" selid="cancel-btn">
        Annuleer
    </a>
    <? if($editable) { ?>
        <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.add('InfoscreenQuestion', '<?=$owner?>', '<?=$owner_id?>');">
            Vraag opslaan
        </a>
    <? } ?>
</div>

<script type="text/javascript">

    <? if(!$editable) { ?>
        $('.popup-content input, .popup-content select, .popup-content textarea').not('.disable_protect').attr({'disabled' : true});
    <? } ?>

    <? if($owner != 'group') { ?>
        Questions.loadEditAttachments('<?=$owner?>', '<?=$owner_id?>', '<?=getUUID($question, 'get');?>');
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

    $('#QuestionQuestion').ckeditor({});
</script>
