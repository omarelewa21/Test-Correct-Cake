<div class="popup-head"><?= $title ?></div>
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
                    <?=$this->Form->input('text', array('style' => 'width: 400px', 'type' => 'textarea', 'label' => false)) ?>
                </td>
            </tr>
            <?
                if($groupquestion_type=='carousel') {
            ?>
            <tr>
                <th width="10%" valign="top">
                    Aantal vragen
                </th>
                <td>
                    <?=$this->Form->input('number_of_subquestions', array('style' => 'width: 400px', 'type' => 'text', 'label' => false)) ?>
                </td>
            </tr>

            <?
            }
            ?>
            <tr>
                <td colspan="2">
                    <?=$this->Form->input('maintain_position', array('type' => 'checkbox', 'label' => false, 'div' => false)) ?>
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
                    <?=$this->Form->input('add_to_database', array('type' => 'checkbox', 'label' => false, 'checked' => true, 'div' => false)) ?>
                    Openbaar maken <span class="fa fa-info-circle" onclick="Popup.load('/questions/public_info', 500);" style="cursor:pointer"></span>
                    <?=$this->Form->input('groupquestion_type', array('type' => 'hidden', 'label' => false, 'name' => 'groupquestion_type', 'value' => $groupquestion_type))?>
                </td>
            </tr>
        </table>

    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btbAddQuestionGroup">
        Vraag-groep opslaan
    </a>
</div>


<script type="text/javascript">

    $('#QuestionGroupAttainments').select2();

    $('#QuestionGroupText').ckeditor({});

    $('#QuestionGroupAddGroupForm').formify(
        {
            confirm : $('#btbAddQuestionGroup'),
            onsuccess : function(result) {
                Navigation.load('/questions/view_group/<?=$test_id?>/' + result.uuid);
                Popup.closeLast();
                Notify.notify("Vraag-groep aangemaakt", "info");
                setTimeout(function() {
                    Popup.load('/questions/add_custom/group/' + result.uuid, 800);
                }, 1000);
            },
            onfailure : function(result) {
                Notify.notify("Inloggegevens incorrect", "error");
            }
        }
    );

    $('form input').keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });


</script>