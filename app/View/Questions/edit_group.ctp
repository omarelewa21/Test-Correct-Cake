<div class="popup-head">Vraag-groep aanmaken</div>
<div class="popup-content">

    <?=$this->Form->create('QuestionGroup')?>

        <table class="table mb15">
            <tr>
                <th width="10%">
                    Naam
                </th>
                <td>
                    <?=$this->Form->input('name', array('style' => 'width: 400px', 'label' => false, 'verify' => 'notempty')) ?>
                </td>
            </tr>
            <tr>
                <th width="10%" valign="top">
                    Omschrijving
                </th>
                <td>
                    <?=$this->Form->input('question', array('style' => 'width: 400px', 'type' => 'textarea', 'label' => false)) ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?=$this->Form->input('closeable', array('type' => 'checkbox', 'value' => 1, 'label' => false, 'div' => false, 'checked' => $question['question']['closeable'] == 1 ? 'checked' : ''))?> Deze vraaggroep afsluiten <span class="fa fa-info-circle" onclick="Popup.load('/questions/group_closeable_info', 500);" style="cursor:pointer"></span><br /
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?=$this->Form->input('maintain_position', array('type' => 'checkbox', 'label' => false, 'div' => false, 'value' => 1, 'checked' => $this->request->data['maintain_position'] == 1)) ?>
                    Deze vraaggroep vastzetten
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?=$this->Form->input('shuffle', array('type' => 'checkbox', 'label' => false, 'div' => false)) ?>
                    Vragen in deze groep shuffelen
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?=$this->Form->input('add_to_database', array('type' => 'checkbox', 'label' => false, 'div' => false)) ?>
                    Delen Openbaar maken <span class="fa fa-info-circle" onclick="Popup.load('/questions/public_info', 500);" style="cursor:pointer"></span>
                </td>
            </tr>
        </table>

    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btbEditQuestionGroup">
        Vraag-groep opslaan
    </a>
</div>


<script type="text/javascript">

    $('#QuestionGroupAttainments').select2();

    $('#QuestionGroupQuestion').ckeditor({});

    $('#QuestionGroupEditGroupForm').formify(
        {
            confirm : $('#btbEditQuestionGroup'),
            onsuccess : function(result) {
                Popup.closeLast();
                Navigation.refresh();
                Notify.notify("Vraag-groep opgeslagen", "info");
            },
            onfailure : function(result) {}
        }
    );

        $('form input').keydown(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
</script>
