<div class="popup-head"><?= __("Schoollocatie")?></div>
<div class="popup-content">
    <?= $this->Form->create('SchoolLocation') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("Naam")?>
            </th>
            <td>
                <?= $this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
            <th width="130">
            <?= __("Scholengemeenschap")?>
            </th>
            <td>
                <?= $this->Form->input('school_id', array('style' => 'width: 185px', 'label' => false, 'options' => $schools, 'selected' => getUUID($school_location['school'], 'get'))); ?>
            </td>
            <th width="130">
            <?= __("Niveau")?>
            </th>
            <td>
                <?= $this->Form->input('education_levels', array('style' => 'width: 185px', 'label' => false, 'options' => $eduction_levels, 'multiple' => true)) ?>
            </td>
        </tr>
        <tr>
            <th>
            <?= __("Klantcode")?>
            </th>
            <td>
                <?= $this->Form->input('customer_code', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
            <th width="130">
            <?= __("Accountmanager")?>
            </th>
            <td>
                <?= $this->Form->input('user_id', array('style' => 'width: 185px', 'label' => false, 'options' => $accountmanagers, 'selected' => getUUID($school_location['user'], 'get'))) ?>
            </td>
            <th width="130">
            <?= __("Cijfermodel")?>
            </th>
            <td>
                <?= $this->Form->input('grading_scale_id', array('style' => 'width: 185px', 'label' => false, 'options' => $grading_scales)) ?>
            </td>
        </tr>
        <tr>
            <th><?= __("Contact actief")?></th>
            <td>
                <label class="switch" style="display:flex;">
                    <?= $this->Form->input('activated', array('style' => 'width: 185px', 'label' => false, 'type' => 'checkbox', 'value' => 1, 'div' => false, 'style' => 'width:20px;')) ?>
                    <span class="slider round"></span>
                </label>
            </td>
            <th>
            <?= __("Aantal Studenten")?>
            </th>
            <td>
                <?= $this->Form->input('number_of_students', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
            <th>
            <?= __("Aantal docenten")?>
            </th>
            <td>
                <?= $this->Form->input('number_of_teachers', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>

        <tr>
            <th><?= __("Brin code")?></th>
            <td>
                <?= $this->Form->input('external_main_code', array('style' => 'width: 185px', 'label' => false, 'maxLength' => 4, 'verify' => 'length-0-or-4')); ?>
            </td>
            <th><?= __("Locatie brin code (Max. 2 karakters)")?></th>
            <td>
                <?= $this->Form->input('external_sub_code', array('style' => 'width: 185px', 'label' => false, 'maxLength' => 2)); ?>
            </td>


            <th><?= __("Is rtti school")?></th>

            <td>
                <label class="switch" style="display:flex;">
                    <?= $this->Form->input('is_rtti_school_location', array('style' => 'width: 185px', 'label' => false, 'type' => 'checkbox', 'value' => 1, 'div' => false, 'style' => 'width:20px;')) ?>
                    <span class="slider round"></span>
                </label>
            </td>
        </tr>
        <tr>
            <th><?= __("Open source content creator")?></th>
            <td>
                <label class="switch" style="display:flex;">
                    <?= $this->Form->input('is_open_source_content_creator', array('type' => 'checkbox', 'label' => false, 'value' => 1, 'div' => false)); ?>
                    <span class="slider round"></span>
                </label>
            </td>
            <th><?= __("Mag open source content bekijken")?></th>
            <td>
                <label class="switch" style="display:flex;">
                    <?= $this->Form->input('is_allowed_to_view_open_source_content', array('type' => 'checkbox', 'label' => false, 'value' => 1, 'div' => false)); ?>
                    <span class="slider round"></span>
                </label>
            </td>

            <th width="130"><?= __("Taal Test-Correct")?></th>
            <td>
                <?=$this->Form->input('school_language', array('style' => 'width: 185px', 'label' => false, 'options' => array('en'=>'English', 'nl' => 'Dutch'))) ?>
            </td>
        </tr>

        <tr>
            <th><?= __("Hubspot ID")?></th>
            <td>
                <?=$this->Form->input('company_id', array('style' => 'width: 185px', 'type' => 'text', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
            <th></th>
            <td>

            </td>
            <th width="130"></th>
            <td>

            </td>
        </tr>

        <tr>
            <th colspan="2" style="text-align: center"><br/><?= __("Vestigingsadres")?></th>
            <th colspan="2" style="text-align: center"><br/><?= __("Factuuradres")?></th>
            <th colspan="2" style="text-align: center"><br/><?= __("Bezoekadres")?></th>
        </tr>
        <tr>
            <th><?= __("Adres")?></th>
            <td><?= $this->Form->input('main_address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Adres")?></th>
            <td><?= $this->Form->input('invoice_address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Adres")?></th>
            <td><?= $this->Form->input('visit_address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th><?= __("Postcode")?></th>
            <td><?= $this->Form->input('main_postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Postcode")?></th>
            <td><?= $this->Form->input('invoice_postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Postcode")?></th>
            <td><?= $this->Form->input('visit_postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th><?= __("Stad")?></th>
            <td><?= $this->Form->input('main_city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Stad")?></th>
            <td><?= $this->Form->input('invoice_city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Stad")?></th>
            <td><?= $this->Form->input('visit_city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th><?= __("Land")?></th>
            <td><?= $this->Form->input('main_country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Land")?></th>
            <td><?= $this->Form->input('invoice_country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Land")?></th>
            <td><?= $this->Form->input('visit_country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th><?= __("LVS Koppeling type")?></th>
            <td>
                <?= $this->Form->input('lvs_type', array('style' => 'width: 185px', 'label' => false, 'options' => $lvs_types)); ?>
            </td>

            <th><?= __("LVS koppeling actief")?></th>
            <td>
                <label id="lvs_toggle" class="switch" style="display:flex;">
                    <?= $this->Form->checkbox('lvs_active', array('type' => 'checkbox', 'value' => 1, 'label' => false)) ?>
                    <span class="slider round"></span>
                </label>

            </td>

        </tr>
        <tr>
            <th><?= __("Single Sign On type")?></th>
            <td>
                <?= $this->Form->input('sso_type', array('style' => 'width: 185px', 'label' => false, 'options' => $sso_types)); ?>
            </td>

            <th><?= __("Single Sign On actief")?></th>
            <td>
                <label id="sso_toggle" class="switch" style="display:flex;">
                    <?= $this->Form->checkbox('sso_active', array('type' => 'checkbox', 'value' => 1, 'label' => false)) ?>
                    <span class="slider round"></span>
                </label>
            </td>
        </tr>
    </table>
    <?= $this->Form->end(); ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <?php if (strtolower($school_location['customer_code']) !== 'tc-tijdelijke-docentaccounts') { ?>
        <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
        <?= __("Wijzigen")?>
        </a>
    <?php } ?>
</div>

<script type="text/javascript">
    var hasRunImport = "<?php echo !!$school_location_has_run_manual_import ?>";

    $(document).ready(function () {

        var revert = true;

        $("#SchoolLocationExternalMainCode").on('change', function () {
            if ($("#SchoolLocationExternalMainCode").val().length > 0 && $("#SchoolLocationExternalMainCode").val().length < 4) {
                Notify.notify('<?= __("De BRIN code moet uit 4 karakters bestaan.")?>', 'error');
            }
        });
        $("#SchoolLocationExternalSubCode, #SchoolLocationExternalMainCode").on('input', function () {
            checkSchoolLocationLvsType();
        });

        $("#SchoolLocationSchoolId").on('change', function () {
            var currentVal = $("#SchoolLocationSchoolId").val();

            if (currentVal == "0") {
                $("#SchoolLocationExternalMainCode").removeAttr('disabled');
            } else if (currentVal != "0" && $("#SchoolLocationExternalMainCode").val() != "") {
                Popup.message({
                    btnOk: '<?= __("Ja")?>',
                    btnCancel: '<?= __("Annuleer")?>',
                    title: '<?= __("Weet u het zeker?")?>',
                    message: '<?= __("School gemeenschap aanpassen verwijderd de RTTI code")?>'
                }, function () {
                    $("#SchoolLocationSchoolId").val(currentVal);
                    $("#SchoolLocationExternalMainCode").attr('disabled', 'disabled');
                    revert = false;
                });
            }
        });
        checkSchoolLocationLvsType();
    });


    $('#SchoolLocationEditForm').formify(
        {
            confirm: $('#btnSave'),
            onsuccess: function (result) {
                Popup.closeLast();
                Notify.notify('<?= __("School gewijzigd")?>', "info");
                Navigation.refresh();
            },
            onfailure: function (result) {
                // Notify.notify("School kon niet worden aangemaakt", "error");
                if (typeof result[0] == "string" && result[0].toLowerCase().includes('<?= __("locatie brin code")?>')) {
                    $("#SchoolLocationExternalSubCode").removeClass('verify-ok').addClass('verify-error');
                }
                
                Notify.notify(result.join('<br />'), 'error');
            }
        }
    );

    $('#SchoolLocationEducationLevels').select2();

    $("#SchoolLocationIsOpenSourceContentCreator").on('change', function () {
        if ($(this).is(':checked')) {
            $("#SchoolLocationIsAllowedToViewOpenSourceContent").removeAttr('checked');
            $("#SchoolLocationIsAllowedToViewOpenSourceContent").attr('disabled', 'disabled');
        } else {
            $("#SchoolLocationIsAllowedToViewOpenSourceContent").removeAttr('disabled', 'disabled');
        }
    });

    $("#SchoolLocationIsAllowedToViewOpenSourceContent").on('change', function () {
        if ($(this).is(':checked')) {
            $("#SchoolLocationIsOpenSourceContentCreator").removeAttr('checked');
            $("#SchoolLocationIsOpenSourceContentCreator").attr('disabled', 'disabled');
        } else {
            $("#SchoolLocationIsOpenSourceContentCreator").removeAttr('disabled', 'disabled');
        }
    });


    function checkSchoolLocationLvsType() {
        var lvs_toggle = document.querySelector('#SchoolLocationLvsActive')
        var sso_toggle = document.querySelector('#SchoolLocationSsoActive')

        if (document.querySelector('#SchoolLocationLvsType').value === '') {
            $('#SchoolLocationLvsActive').prop('checked', false);
            lvs_toggle.setAttribute('disabled', 'disabled');
        } else {
            lvs_toggle.setAttribute('disabled', 'disabled');
            if (!!hasRunImport === true) {
                lvs_toggle.removeAttribute('disabled');
            }
            if ($("#SchoolLocationExternalSubCode").val() === '' || $("#SchoolLocationExternalMainCode").val() === '') {
                $('#SchoolLocationLvsActive').prop('checked', false);
                lvs_toggle.setAttribute('disabled', 'disabled');
            }
        }

        if (document.querySelector('#SchoolLocationSsoType').value === '') {
            $('#SchoolLocationSsoActive').prop('checked', false);
            $('#SchoolLocationLvsActive').prop('checked', false);
            sso_toggle.setAttribute('disabled', 'disabled');
        } else {
            sso_toggle.removeAttribute('disabled');
            if ($("#SchoolLocationExternalSubCode").val() === '' || $("#SchoolLocationExternalMainCode").val() === '') {
                $('#SchoolLocationSsoActive').prop('checked', false);
                sso_toggle.setAttribute('disabled', 'disabled');
            }
        }
    }

    $("#SchoolLocationLvsType, #SchoolLocationSsoType").on('change', function () {
        checkSchoolLocationLvsType();
    });

    $('#SchoolLocationLvsActive').on('change', function () {
        if (document.querySelector('#SchoolLocationSsoType').value === '') {
            if (document.querySelector('#SchoolLocationLvsActive').checked) {
                $('#SchoolLocationLvsActive').prop('checked', false);
                Notify.notify('<?= __("Selecteer eerst een Single Sign On type.")?>', 'error');
            }
        } else {
            $('#SchoolLocationSsoActive').prop('checked', true);
        }
    });

    $('#SchoolLocationSsoActive').on('change', function () {
        if (!document.querySelector('#SchoolLocationSsoActive').checked && document.querySelector('#SchoolLocationLvsActive').checked) {
            $('#SchoolLocationSsoActive').prop('checked', true);
            Notify.notify('<?= __("Single Sign On mag niet uit gezet worden als de LVS koppeling actief is.")?>', 'error');
        }
    });

    $('#sso_toggle, #lvs_toggle').click(function() {
        if ($('#SchoolLocationSsoActive').prop('disabled') && ($("#SchoolLocationExternalSubCode").val() === '' || $("#SchoolLocationExternalMainCode").val() === '')){
            Notify.notify('<?= __("BRIN/Locatie code mag niet leeg zijn als je LVS of SSO wilt activeren")?>', 'error');
        }
    });

    $('#lvs_toggle').click(function() {
        if ($('#SchoolLocationLvsActive').prop('disabled') && !!hasRunImport === false){
            Notify.notify('<?= __("De LVS koppeling kan pas actief gezet worden als de koppeling  minimaal één keer handmatig heeft gedraaid.")?>', 'error');
        }
    });
</script>
