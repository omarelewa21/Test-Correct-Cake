<div class="popup-head"><?= __("Scholengemeenschap")?></div>
<div class="popup-content">
    <?=$this->Form->create('School') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("Naam")?>
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
            <th width="130">
            <?= __("Organisatie")?>
            </th>
            <td>
                <?=$this->Form->input('umbrella_organization_id', array('style' => 'width: 185px', 'label' => false, 'options' => $organisations)) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Accountmanager")?>
            </th>
            <td>
                <?=$this->Form->input('user_id', array('style' => 'width: 185px', 'label' => false, 'options' => $accountmanagers)) ?>
            </td>
            <th width="130">
            <?= __("Klantcode")?>
            </th>
            <td colspan="3">
                <?=$this->Form->input('customer_code', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>

        <tr>
            <th><?= __("Brin code")?></th>
            <td>
                <?=$this->Form->input('external_main_code',array('style' => 'width: 185px', 'label' => false));?>
            </td>

        </tr>
        <tr>
            <th colspan="2" style="text-align: center"><br /><?= __("Vestigingsadres")?></th>
            <th colspan="2" style="text-align: center"><br /><?= __("Factuuradres")?></th>
        </tr>
        <tr>
            <th><?= __("Adres")?></th>
            <td><?=$this->Form->input('main_address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Adres")?></th>
            <td><?=$this->Form->input('invoice_address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th><?= __("Postcode")?></th>
            <td><?=$this->Form->input('main_postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Postcode")?></th>
            <td><?=$this->Form->input('invoice_postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th><?= __("Stad")?></th>
            <td><?=$this->Form->input('main_city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Stad")?></th>
            <td><?=$this->Form->input('invoice_city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th><?= __("Land")?></th>
            <td><?=$this->Form->input('main_country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th><?= __("Land")?></th>
            <td><?=$this->Form->input('invoice_country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnSave">
    <?= __("Aanmaken")?>
    </a>
</div>

<script type="text/javascript">


    $(document).ready(function(){
        $("#SchoolUmbrellaOrganizationId").on('change', function() {

            if($(this).val() != "0") {
                $("#SchoolExternalMainCode").attr('disabled', 'disabled');
            } else {
                $("#SchoolExternalMainCode").removeAttr('disabled');
            }

        });
    });

    $('#SchoolAddForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("School aangemaakt")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify('<?= __("School kon niet worden aangemaakt")?>', "error");
            }
        }
    );
</script>