<?= $this->element('teacher_question_edit_header', ['question_type' =>  __("Gatentekst"), 'test_name' => $test_name]) ?>
<!--<div class="popup-head">--><?//= __("Gatentekst")?><!--</div>-->
<div style="margin: 0 auto; max-width:1000px;padding-bottom: 80px;">
    <?=$this->Form->create('Question', ['class' => 'add_question_form', 'selid' => 'tabcontainer'])?>

    <table class="table mb15">
        <tr>
            <th width="10%">
            <?= __("Punten")?>
            </th>
            <td width="110">
                <?=$this->Form->input('score', array('style' => 'width:50px;', 'value' => 2, 'label' => false, 'verify' => 'notempty'))?>
            </td>
            <td>
                <?=$this->Form->input('closeable', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __("Deze vraag afsluiten")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/closeable_info', 500);" style="cursor:pointer"></span><br />
                <?=$this->Form->input('discuss', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => 'checked'))?>  <?= __("Bespreken in de klas")?> <br />
                <? if($owner == 'test') { ?><?=$this->Form->input('maintain_position', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false))?> <?= __("Deze vraag vastzetten")?><br /><? } ?>
                <?=$this->Form->input('decimal_score', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => 'checked'))?> <?= __("Halve punten mogelijk")?><br />
                <?php if(!$is_open_source_content_creator): ?>
                    <?=$this->Form->input('add_to_database', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'checked' => true, 'div' => false, 'selid' => 'open-source-switch'))?> <?= __("Openbaar maken")?> <span class="fa fa-info-circle" onclick="Popup.load('/questions/public_info', 500);" style="cursor:pointer"></span><br />
                <?php endif; ?>
            </td>

            <td width="230">
                <?=$this->Form->input('note_type', array('type' => 'select', 'label' => false, 'div' => false, 'options' => [
                    'NONE' => __("Geen kladblok"),
                    'TEXT' => __("Tekstvlak"),
                    'DRAWING' => __("Tekenvlak")
                ]))?>
                <?=$this->Form->input('auto_check_answer', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => false, 'onclick' => "enableDisableAutoCheckCaseSensitive();"))?>  <?= __("Automatisch nakijken")?> <br />
                <?=$this->Form->input('auto_check_answer_case_sensitive', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => 'checked'))?>  <?= __("Hoofdletter gevoelig nakijken")?> <br />

            </td>
        </tr>
    </table>

        <?=$this->element('teacher_add_question_tabs') ?>

        <div page="question" class="page active" tabs="add_question">
            <span class="title" selid="header"><?=__('Vraag')?></span>
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px; margin-bottom:0px;', 'type' => 'textarea', 'div' => false, 'label' => false)); ?>
        </div>

        <div page="settings" class="page" tabs="add_question">
            <span class="title" selid="header"><?= __('Eindtermen') ?></span>
            <?=$this->element('attainments', ['attainments' => $attainments, 'selectedAttainments' => $selectedAttainments]) ?>
        </div>

        <?=$this->element('question_tab_rtti',[]); ?>

        <?=$this->element('question_editor_tags') ?>

        <?=$this->Form->end();?>

        <?=$this->element('question_editor_attachments', ['owner' => $owner]) ?>
    </div>
    <div class="popup-footer">
        <?= $this->element('teacher_question_edit_footer', ['saveAction' =>"Questions.add('CompletionQuestion', '$owner', '$owner_id');"]) ?>


        <style type="text/css">
        .redactor-toolbar li a.re-advanced {
            background: var(--menu-blue);
            color: white;
        }

        .cke_button__advanced {
            background-color:var(--primary) !important;
        }
    </style>

    <!--
    <script type="text/javascript">

        if (!RedactorPlugins) var RedactorPlugins = {};

        RedactorPlugins.advanced = function()
        {
            return {
                init: function ()
                {
                    var button = this.button.add('advanced', 'Vierkante haakjes toevoegen');
                    this.button.setAwesome('advanced', 'fa-tag');
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
                    $('#QuestionQuestion').redactor('code.sync');
                }
            };
        };
    </script>
    -->
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

        var editor = $('#QuestionQuestion, #QuestionAnswer').ckeditor({
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

    function checkBeforeCompletion(type, owner, id) {
        content = editor.val();
        if(content.indexOf('|') == -1) {
            Questions.add(type, owner, id);
        } else {
            Notify.notify('<?= __("Geen verticale streepjes (|) toegestaan in gatentekst")?>', 'error');
        }

        return false;
    }

    function enableDisableAutoCheckCaseSensitive(){
        if(jQuery("#QuestionAutoCheckAnswer").is(":checked")){
            jQuery("#QuestionAutoCheckAnswerCaseSensitive").removeAttr('disabled');
        } else {
            jQuery("#QuestionAutoCheckAnswerCaseSensitive").attr('disabled',true);
        }
    }

    enableDisableAutoCheckCaseSensitive();

    </script>
