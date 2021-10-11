<div class="popup-head">Selectievraag</div>
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
                Taxonomie
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
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>

    <? if($is_clone_request){ ?>
        <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.add('MultiCompletionQuestion', '<?=$owner?>', '<?=$owner_id?>');">
            Vraag opslaan
        </a>
    <? }else{ ?>
        <? if($editable) { ?>
            <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.edit('<?=$owner?>', '<?=$owner_id?>', 'CompletionQuestion', '<?=getUUID($question, 'get');?>');">
                Vraag opslaan
            </a>
        <? } ?>
    <? } ?>
</div>


<style type="text/css">
    .redactor-toolbar li a.re-advanced {
        background: var(--menu-blue);
        color: white;
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
                this.button.setAwesome('advanced', 'fa-tags');
                this.button.addCallback(button, this.advanced.testButton);
            },
            testButton: function(buttonName)
            {
                var selection = $('#QuestionQuestion').redactor('selection.getText');

                if(selection == "") {
                    Popup.message({
                        'title' : 'Selecteer juiste antwoord',
                        'message' : 'Selecteer eerst het juiste woord in uw tekst waar u de keuze uit wilt opbouwen.',
                        'btnOk' : 'Oke'
                    });
                }else {
                    Popup.load('/questions/add_multi_completion_item', 500);
                }
            }
        };
    };
</script>


<script type="text/javascript">

    <?php if(!$editable) { ?>
    $('.popup-content input, .popup-content select, .popup-content textarea').not('.disable_protect').attr({'disabled' : true});
    <?php } ?>

    <? if($is_clone_request){ ?>
        Questions.loadAddAttachments('true');
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

    $('#QuestionQuestion').redactor({
        buttons: ['html', 'formatting', 'bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist', 'outdent', 'indent', 'image', 'link', 'alignment', 'horizontalrule'],
        pastePlainText: true,
        plugins: ['table', 'scriptbuttons', 'advanced']
    });
</script>
