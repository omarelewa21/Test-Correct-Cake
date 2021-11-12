<div class="popup-head"><?= __("Gatentekst")?></div>
<div class="popup-content">

    <?=$this->Form->create('Question', array('id' => $is_clone_request ? 'QuestionAddForm' : 'QuestionEditForm'))?>

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

        <div class="tabs">
            <a href="#" class="btn grey highlight" page="question" tabs="edit_question">
            <?= __("Tekst")?>
            </a>
            <? if($owner != 'group') { ?>
                <a href="#" class="btn grey" page="sources" tabs="edit_question">
                <?= __("Bronnen")?>
                </a>
            <? } ?>

            <a href="#" class="btn grey" page="attainments" tabs="edit_question">
            <?= __("Eindtermen")?>
            </a>

            <a href="#" class="btn grey" page="tags" tabs="edit_question">
            <?= __("Tags")?>
            </a>

            <a href="#" class="btn grey" page="rtti" tabs="edit_question">
            <?= __("Taxonomie")?>
            </a>

            <a href="#" class="btn grey" page="owners" tabs="edit_question">
            <?= __("Info")?>
            </a>
            <br clear="all" />

        </div>
        <div page="question" class="page active" tabs="edit_question">
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px; margin-bottom:0px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'value' => $question['question']['question'])); ?>
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
    <?= __("Annuleer")?>
    </a>

    <? if($is_clone_request){ ?>
        <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.add('CompletionQuestion', '<?=$owner?>', '<?=$owner_id?>');">
            <?= __("Vraag opslaan")?>
        </a>
    <? }else{ ?>
        <? if($editable) { ?>
            <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="checkBeforeCompletion('<?=$owner?>', '<?=$owner_id?>', 'CompletionQuestion', '<?=getUUID($question, 'get');?>');">
                <?= __("Vraag opslaan")?>
            </a>
        <? } ?>
    <? } ?>
</div>


<style type="text/css">
    .redactor-toolbar li a.re-advanced {
        background: var(--menu-blue);
        color: white;
    }

    .cke_button__advanced {
        background-color:var(--primary) !important;
    }

</style>

<script type="text/javascript">

    if (!RedactorPlugins) var RedactorPlugins = {};

    RedactorPlugins.advanced = function()
    {
        return {
            init: function ()
            {
                var button = this.button.add('advanced', '<?= __("Vierkante haakjes toevoegen")?>');
                this.button.setAwesome('advanced', 'fa-plus');
                this.button.addCallback(button, this.advanced.testButton);
            },
            testButton: function(buttonName)
            {
                /*window.caretOffset = $('#QuestionQuestion').redactor('caret.getOffset');
                 Popup.load('/questions/add_completion_item', 500);*/
                var selection = $('#QuestionQuestion').redactor('selection.getText');

                if(selection != '' && selection.slice(-1) == ' ') {
                    selection = '[' + selection.substr(0, selection.length - 1) + '] ';
                }else{
                    selection = '[' + selection + ']';
                }

                $('#QuestionQuestion').redactor('selection.replaceSelection', selection);
            }
        };
    };
</script>
<script type="text/javascript">

    <? if(!$editable) { ?>
    $('.popup-content input, .popup-content select, .popup-content textarea').not('.disable_protect').attr({'disabled' : true});
    <? } ?>

    <? if($is_clone_request){ ?>
        Questions.loadAddAttachments(true);
    <? }else{ ?>
        <? if($owner != 'group') { ?>
            Questions.loadEditAttachments('<?=$owner?>', '<?=$owner_id?>', '<?=getUUID($question, 'get');?>');
        <? }?>
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

    var editor = $('#QuestionQuestion').ckeditor({
        toolbar : [
            { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
            // { name: 'clipboard', items: [ 'PasteFromWord', '-', 'Undo', 'Redo' ] },
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'Subscript', 'Superscript' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
            { name: 'insert', items: [ 'addImage', 'Table' ] },
            //{ name: 'editing', items: [ 'EqnEditor' ] },
            // { name: 'editing', items: [ 'Scayt', 'EqnEditor' ] },
            '/',
            { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor', 'CopyFormatting' ] },
            { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
            { name: 'extra', items: ['advanced']},
            {name: 'wirisplugins', items: ['ckeditor_wiris_formulaEditor', 'ckeditor_wiris_formulaEditorChemistry']}
        ]
    });

    function checkBeforeCompletion(owner, owner_id, type, question_id) {
        content = editor.val();
        if(content.indexOf('|') == -1) {
            Questions.edit(owner, owner_id, type, question_id);
        } else {
            Notify.notify('<?= __("Geen verticale streepjes (|) toegestaan in gatentekst")?>', 'error');
        }

        return false;
    }
</script>
