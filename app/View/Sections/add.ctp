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
                <?=$this->Form->input('school_locations', ['options' => $locations, 'style' => 'width:200px;', 'multiple' => true, 'label' => false]) ?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave" onclick="addSection()">
        Aanmaken
    </a>
</div>

<script type="text/javascript">
    function addSection(){
        if($('#SectionName').val() === ""){
            Notify.notify("schoolnaam moet ingevuld zijn", "error");
        }
        else if($('#SectionSchoolLocations').val() === null){
            Notify.notify("schoollocatie moet ten minste één waarde hebben", "error");
        }
        else{
                $('#SectionAddForm').formify(
                {
                    confirm : $('#btnSave'),
                    onsuccess : function(result) {
                        Popup.closeLast();
                        Notify.notify("Sectie aangemaakt", "info");
                        Navigation.refresh();
                    },
                    onfailure : function(result) {
                        Notify.notify("Sectie kon niet worden aangemaakt", "error");
                    }
                }
            );
        }
    }
    
    

    $('#SectionSchoolLocations').select2();
</script>