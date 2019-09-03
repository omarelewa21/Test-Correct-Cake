<div class="popup-head">Selectievraag</div>
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
                Tekst
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
            <?=$this->Form->input('question', array('style' => 'width:737px; height: 100px; margin-bottom:0px;', 'type' => 'textarea', 'div' => false, 'label' => false)); ?>
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
    <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="Questions.add('MultiCompletionQuestion', '<?=$owner?>', <?=$owner_id?>);">
        Vraag opslaan
    </a>
</div>


<style type="text/css">
    .redactor-toolbar li a.re-advanced {
        background: #197cb4;
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

    $('#QuestionQuestion').redactor({
        buttons: ['html', 'formatting', 'bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist', 'outdent', 'indent', 'image', 'link', 'alignment', 'horizontalrule'],
        pastePlainText: true,
        plugins: ['table', 'scriptbuttons', 'advanced']
    });

</script>
