<div class="popup-head"><?= __("Schoollocatie")?></div>
<div class="popup-content">
    <?=$this->Form->create('SchoolLocation') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("Naam")?>
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
            <th width="130">
            <?= __("Scholengemeenschap")?>
            </th>
            <td>
                <?=$this->Form->input('school_id', array('style' => 'width: 185px', 'label' => false, 'options' => $schools, 'selected' => getUUID($school_location['school'], 'get'))); ?>
            </td>
            <th width="130">
            <?= __("Niveau")?>
            </th>
            <td>
                <?=$this->Form->input('education_levels', array('style' => 'width: 185px', 'label' => false, 'options' => $eduction_levels, 'multiple' => true)) ?>
            </td>
        </tr>
        <tr>
            <th>
            <?= __("Klantcode")?>
            </th>
            <td>
                <?=$this->Form->input('customer_code', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
            <th width="130">
            <?= __("Accountmanager")?>
            </th>
            <td>
                <?=$this->Form->input('user_id', array('style' => 'width: 185px', 'label' => false, 'options' => $accountmanagers, 'selected' => getUUID($school_location['user'], 'get'))) ?>
            </td>
            <th width="130">
            <?= __("Cijfermodel")?>
            </th>
            <td>
                <?=$this->Form->input('grading_scale_id', array('style' => 'width: 185px', 'label' => false, 'options' => $grading_scales)) ?>
            </td>
        </tr>
        <tr>
            <th><?= __("Contact actief")?></th>
            <td>
                <?=$this->Form->input('activated', array('style' => 'width: 185px', 'label' => false, 'type' => 'checkbox', 'value' => 1, 'div' => false, 'style' => 'width:20px;')) ?>
                <?= __("Contract is actief")?>
            </td>
            <th>
            <?= __("Aantal Studenten")?>
            </th>
            <td>
                <?=$this->Form->input('number_of_students', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
            <th>
            <?= __("Aantal docenten")?>
            </th>
            <td>
                <?=$this->Form->input('number_of_teachers', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>

        <tr>
            <th><?= __("Brin code")?></th>
            <td>
                <?=$this->Form->input('external_main_code',array('style' => 'width: 185px', 'label' => false));?>
            </td>
            <th><?= __("Locatie brin code")?></th>
            <td>
                <?=$this->Form->input('external_sub_code',array('style' => 'width: 185px', 'label' => false));?>
            </td>


            <th><?= __("Is rtti school location")?></th>

            <td>
                <?=$this->Form->input('is_rtti_school_location', array('style' => 'width: 185px', 'label' => false, 'type' => 'checkbox', 'value' => 1, 'div' => false, 'style' => 'width:20px;')) ?>
                <?= __("Is RTTI school")?>
            </td>
        </tr>
        <tr>
          <th><?= __("Open source content creator")?></th>
          <td>
              <?=$this->Form->input('is_open_source_content_creator',array('type'=>'checkbox','label'=>false)); ?>
          </td>
          <th><?= __("Mag open source content bekijken")?></th>
          <td>
              <?=$this->Form->input('is_allowed_to_view_open_source_content',array('type'=>'checkbox','label'=>false)); ?>
          </td>

          <th width="130"><?= __("Taal Test-Correct")?></th>
          <td>
          <?=$this->Form->input('school_language', array('style' => 'width: 185px', 'label' => false, 'options' => array('eng'=>'English', 'nl' => 'Dutch'))) ?>
          </td>
        </tr>

        <tr>
            <th colspan="2" style="text-align: center"><br /><?= __("Vestigingsadres")?></th>
            <th colspan="2" style="text-align: center"><br /><?= __("Factuuradres")?></th>
            <th colspan="2" style="text-align: center"><br /><?= __("Bezoekadres")?></th>
        </tr>
        <tr>
            <th><?= __("Adres")?></th>
            <td><?=$this->Form->input('main_address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Adres")?></th>
            <td><?=$this->Form->input('invoice_address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Adres")?></th>
            <td><?=$this->Form->input('visit_address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th><?= __("Postcode")?></th>
            <td><?=$this->Form->input('main_postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Postcode")?></th>
            <td><?=$this->Form->input('invoice_postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Postcode")?></th>
            <td><?=$this->Form->input('visit_postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th><?= __("Stad")?></th>
            <td><?=$this->Form->input('main_city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Stad")?></th>
            <td><?=$this->Form->input('invoice_city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Stad")?></th>
            <td><?=$this->Form->input('visit_city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th><?= __("Land")?></th>
            <td><?=$this->Form->input('main_country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Land")?></th>
            <td><?=$this->Form->input('invoice_country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Land")?></th>
            <td><?=$this->Form->input('visit_country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
    <?= __("Wijzigen")?>
    </a>
</div>

<script type="text/javascript">

    $(document).ready(function(){

        var revert = true;

        if($("#SchoolLocationSchoolId").val() != '0') {
            $("#SchoolLocationExternalMainCode").attr('disabled','disabled');
        }

        $("#SchoolLocationSchoolId").on('change',function(){

            var currentVal = $("#SchoolLocationSchoolId").val();

            if(currentVal == "0") {
                $("#SchoolLocationExternalMainCode").removeAttr('disabled');
            }else if(currentVal != "0" && $("#SchoolLocationExternalMainCode").val() != ""){
                Popup.message({
                    btnOk: '<?= __("Ja")?>',
                    btnCancel: '<?= __("Annuleer")?>',
                    title: '<?= __("Weet u het zeker?")?>',
                    message: '<?= __("School gemeenschap aanpassen verwijderd de RTTI code")?>'
                }, function() {
                    $("#SchoolLocationSchoolId").val(currentVal);
                    $("#SchoolLocationExternalMainCode").attr('disabled','disabled');
                    revert = false;
                });
            }
        });

    });


    $('#SchoolLocationEditForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("School gewijzigd")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                // console.log(result);
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





</script>
