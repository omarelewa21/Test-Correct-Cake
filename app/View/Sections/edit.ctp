<div class="popup-head">Sectie</div>
<div class="popup-content">
    <?=$this->Form->create('Section') ?>

    <table class="table">
        <tr>
            <th width="130">
                Naam
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th>Locaties</th>
            <td>
                <?
                $selectedLocations = [];
                foreach($this->request->data['Section']['school_locations'] as $location) {
                    $selectedLocations[] = getUUID($location, 'get');
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
    $('#SectionEditForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Sectie gewijzigd", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify("Sectie kon niet worden aangemaakt", "error");
            }
        }
    );

    $('#SectionSchoolLocations').select2();
</script>