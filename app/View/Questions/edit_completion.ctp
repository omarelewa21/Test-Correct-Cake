<div class="popup-head">Gatentekst</div>
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
                    <? if($owner != 'group') { ?><?=$this->Form->input('maintain_position', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['maintain_position'] == 1 ? 'checked' : ''))?> Deze vraag vast zetten <br /><? }?>
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
                Tekst
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
                RTTI
            </a>

            <a href="#" class="btn grey" page="owners" tabs="edit_question">
                Info
            </a>
            <br clear="all" />

        </div>
        <div page="question" class="page active" tabs="edit_question">
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px; margin-bottom:0px;', 'type' => 'textarea', 'div' => false, 'label' => false, 'value' => $question['question']['question'])); ?>
        </div>

        <div page="attainments" class="page" tabs="edit_question">
            <?=$this->element('attainments', ['attainments' => $attainments, 'selectedAttainments' => $selectedAttainments]) ?>
        </div>


        <div page="rtti" class="page" tabs="edit_question">
            Selecteer tot welke categorie deze vraag hoort binnen de RTTI-methode<br />
            <?=$this->Form->input('rtti', array('label' => false, 'type' => 'select', 'value' => $question['question']['rtti'],  'options' => ['null' => 'Geen', 'R' => 'R', 'T1' => 'T1', 'T2' => 'T2', 'I' => 'I'], 'style' => 'width:750px;'))?>
        </div>

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
        <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="checkBeforeCompletion('<?=$owner?>', <?=$owner_id?>, 'CompletionQuestion', <?=$question['id']?>);">
            Vraag opslaan
        </a>
    <? } ?>
</div>


<style type="text/css">
    .redactor-toolbar li a.re-advanced {
        background: #197cb4;
        color: white;
    }

    .cke_button__advanced {
        background-color:#1585C5 !important;
    }

</style>

<script type="text/javascript">

    if (!RedactorPlugins) var RedactorPlugins = {};

    RedactorPlugins.advanced = function()
    {
        return {
            init: function ()
            {
                var button = this.button.add('advanced', 'Vierkante haakjes toevoegen');
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
    $('.popup-content input, .popup-content select, .popup-content textarea').attr({'disabled' : true});
    <? } ?>

    <? if($owner != 'group') { ?>
        Questions.loadEditAttachments('<?=$owner?>', <?=$owner_id?>, <?=$question['id']?>);
    <? }?>

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
            { name: 'editing', items: [ 'EqnEditor' ] },
            // { name: 'editing', items: [ 'Scayt', 'EqnEditor' ] },
            '/',
            { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor', 'CopyFormatting' ] },
            { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
            { name: 'extra', items: ['advanced']}
        ]
    });

    function checkBeforeCompletion(owner, owner_id, type, question_id) {
        content = editor.val();
        if(content.indexOf('|') == -1) {
            Questions.edit(owner, owner_id, type, question_id);
        } else {
            Notify.notify('Geen verticale streepjes (|) toegestaan in gatentekst', 'error');
        }

        return false;
    }
</script>
