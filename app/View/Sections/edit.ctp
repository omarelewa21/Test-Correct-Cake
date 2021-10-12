<div class="popup-head"><?= __("Sectie")?></div>
<div class="popup-content">
    <?=$this->Form->create('Section') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("Naam")?>
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th><?= __("Locaties")?></th>
            <td>
                <?
                $selectedLocations = [];
                foreach($this->request->data['Section']['school_locations'] as $location) {
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
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave" onclick="editSection()">
        <?= __("Wijzigen")?>
    </a>
</div>

<script type="text/javascript">
    function editSection(){
        if($('#SectionName').val() === ""){
            Notify.notify('<?= __("schoolnaam moet ingevuld zijn")?>', "error");
        }
        else if($('#SectionSchoolLocations').val() === null){
            Notify.notify('<?= __("schoollocatie moet ten minste één waarde hebben")?>', "error");
        }
        else{
            $.post('/sections/edit/<?=$section_uuid?>',
            $('#SectionEditForm').serialize(),
            function(response) {
                response = JSON.parse(response);
                if(response['status'] == 1) {
                    Popup.closeLast();
                    Navigation.refresh();
                    Notify.notify('<?= __("Sectie gewijzigd")?>');
                }
                else{
                    Notify.notify(response['data'].join('<br />'), 'error');
                }
            });
        }
    }

    $('#SectionSchoolLocations').select2();
</script>