<div class="popup-head">Schooljaar</div>
<div class="popup-content">
    <?=$this->Form->create('SchoolYear') ?>

    <table class="table">
        <tr>
            <th width="130">
                Jaar
            </th>
            <td>
                <?=$this->Form->input('year', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th>Locaties</th>
            <td>
                <?
                $selectedLocations = [];
                foreach($this->request->data['SchoolYear']['school_locations'] as $location) {
                    $selectedLocations[] = $location['id'];
                }
                ?>
                <?=$this->Form->input('school_locations', ['options' => $locations, 'style' => 'width:200px;', 'multiple' => true, 'label' => false, 'value' => $selectedLocations]) ?>
            </td>
        </tr>
       </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
        Wijzigen
    </a>
</div>

<script type="text/javascript">
    $('#SchoolYearEditForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("SchoolYear gewijzigd", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify("SchoolYear kon niet worden aangemaakt", "error");
            }
        }
    );

    $('#SchoolYearSchoolLocations').select2();
</script>