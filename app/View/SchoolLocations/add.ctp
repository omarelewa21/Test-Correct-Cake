<div class="popup-head">Schoollocatie</div>
<div class="popup-content">
    <?=$this->Form->create('SchoolLocation') ?>
    <table class="table">
        <tr>
            <th width="130">
                Naam
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
            <th width="130">
                Scholengemeenschap
            </th>
            <td>
                <?=$this->Form->input('school_id', array('style' => 'width: 185px', 'label' => false, 'options' => $schools)) ?>
            </td>
            <th width="130">
                Niveau
            </th>
            <td>
                <?=$this->Form->input('education_levels', array('style' => 'width: 185px', 'label' => false, 'options' => $eduction_levels, 'multiple' => true)) ?>
            </td>
        </tr>
        <tr>
            <th>
                Klantcode
            </th>
            <td>
                <?=$this->Form->input('customer_code', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
            <th width="130">
                Accountmanager
            </th>
            <td>
                <?=$this->Form->input('user_id', array('style' => 'width: 185px', 'label' => false, 'options' => $accountmanagers)) ?>
            </td>
            <th width="130">
                Cijfermodel
            </th>
            <td>
                <?=$this->Form->input('grading_scale_id', array('style' => 'width: 185px', 'label' => false, 'options' => $grading_scales)) ?>
            </td>
        </tr>
        <tr>
            <th>Contact actief</th>
            <td>
                <?=$this->Form->input('activated', array('style' => 'width: 185px', 'label' => false, 'type' => 'checkbox', 'value' => 1, 'div' => false, 'style' => 'width:20px;')) ?>
                Contract is actief
            </td>
            <th>
                Aantal Studenten
            </th>
            <td>
                <?=$this->Form->input('number_of_students', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
            <th>
                Aantal docenten
            </th>
            <td>
                <?=$this->Form->input('number_of_teachers', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>

        <tr>
            <th>Brin code</th>
            <td>
                <?=$this->Form->input('external_main_code',array('style' => 'width: 185px', 'label' => false, 'maxLength' => 4, 'verify' => 'length-0-or-4'));?>
            </td>
            <th>Locatie brin code (Max. 2 karakters)</th>
            <td>
                <?=$this->Form->input('external_sub_code',array('style' => 'width: 185px', 'label' => false, 'maxLength' => 2, 'verify' => 'max-length-2'));?>
            </td>

            <th>Is rtti school location</th>
            <td>
                <?=$this->Form->input('is_rtti_school_location', array('style' => 'width: 185px', 'label' => false, 'type' => 'checkbox', 'value' => 1, 'div' => false, 'style' => 'width:20px;')) ?>
                Is RTTI school
            </td>
        </tr>

        <tr>
          <th>Open source content creator</th>
          <td>
              <?=$this->Form->input('is_open_source_content_creator',array('type'=>'checkbox','label'=>false)); ?>
          </td>
          <th>Mag open source content bekijken</th>
          <td>
              <?=$this->Form->input('is_allowed_to_view_open_source_content',array('type'=>'checkbox','label'=>false)); ?>
          </td>
        </tr>

        <tr>
            <th colspan="2" style="text-align: center"><br />Vestigingsadres</th>
            <th colspan="2" style="text-align: center"><br />Factuuradres</th>
            <th colspan="2" style="text-align: center"><br />Bezoekadres</th>
        </tr>
        <tr>
            <th>Adres</th>
            <td><?=$this->Form->input('main_address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th>Adres</th>
            <td><?=$this->Form->input('invoice_address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th>Adres</th>
            <td><?=$this->Form->input('visit_address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th>Postcode</th>
            <td><?=$this->Form->input('main_postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th>Postcode</th>
            <td><?=$this->Form->input('invoice_postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th>Postcode</th>
            <td><?=$this->Form->input('visit_postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th>Stad</th>
            <td><?=$this->Form->input('main_city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th>Stad</th>
            <td><?=$this->Form->input('invoice_city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th>Stad</th>
            <td><?=$this->Form->input('visit_city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th>Land</th>
            <td><?=$this->Form->input('main_country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th>Land</th>
            <td><?=$this->Form->input('invoice_country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th>Land</th>
            <td><?=$this->Form->input('visit_country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th>LVS Koppeling type</th>
            <td>
                <?= $this->Form->input('lvs_type', array('style' => 'width: 185px', 'label' => false, 'options' => $lvs_types)); ?>
            </td>

            <th>Single Sign On type</th>
            <td>
                <?= $this->Form->input('sso_type', array('style' => 'width: 185px', 'label' => false, 'options' => $sso_types)); ?>
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
        Aanmaken
    </a>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        $("#SchoolLocationSchoolId").on('change',function(){
            if($(this).val() == "0") {
                $("#SchoolLocationExternalMainCode").removeAttr('disabled');
            }else{
                $("#SchoolLocationExternalMainCode").attr('disabled','disabled');
            }
            console.log($(this).val());
        });
    });

    $('#SchoolLocationAddForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("School aangemaakt", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                // Notify.notify("School kon niet worden aangemaakt", "error");
                Notify.notify(result.join('<br />'), 'error');
            }
        }
    );

    $('#SchoolLocationEducationLevels').select2();

    $("#SchoolLocationIsOpenSourceContentCreator").on('change',function(){
      if($(this).is(':checked')) {
          $("#SchoolLocationIsAllowedToViewOpenSourceContent").removeAttr('checked');
          $("#SchoolLocationIsAllowedToViewOpenSourceContent").attr('disabled','disabled');
      } else {
          $("#SchoolLocationIsAllowedToViewOpenSourceContent").removeAttr('disabled','disabled');
      }
    });

    $("#SchoolLocationIsAllowedToViewOpenSourceContent").on('change',function(){
      if($(this).is(':checked')) {
          $("#SchoolLocationIsOpenSourceContentCreator").removeAttr('checked');
          $("#SchoolLocationIsOpenSourceContentCreator").attr('disabled','disabled');
      } else {
          $("#SchoolLocationIsOpenSourceContentCreator").removeAttr('disabled','disabled');
      }
    });

    $("#SchoolLocationExternalMainCode").on('change', function() {
        if ($("#SchoolLocationExternalMainCode").val().length > 0 && $("#SchoolLocationExternalMainCode").val().length < 4) {
            Notify.notify('De BRIN code moet uit 4 karakters bestaan.', 'error');
        }
    });
</script>
