<div class="popup-head">Schoolgemeenschap</div>
<div class="popup-content">
    <?=$this->Form->create('School') ?>

    <table class="table">
        <tr>
            <th width="130">
                Naam
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
            <th width="130">
                Organisatie
            </th>
            <td>
                <?=$this->Form->input('umbrella_organization_id', array('style' => 'width: 185px', 'label' => false, 'options' => $organisations)) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Accountmanager
            </th>
            <td>
                <?=$this->Form->input('user_id', array('style' => 'width: 185px', 'label' => false, 'options' => $accountmanagers)) ?>
            </td>
            <th width="130">
                Klantcode
            </th>
            <td colspan="3">
                <?=$this->Form->input('customer_code', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">Brin code</th>
            <td colspan="3">
                <?=$this->Form->input('external_main_code', array('style' => 'width: 185px', 'label' => false)) ?>
            </td>
        </tr>
        <tr>
            <th colspan="2" style="text-align: center"><br />Vestigingsadres</th>
            <th colspan="2" style="text-align: center"><br />Factuuradres</th>
        </tr>
        <tr>
            <th>Adres</th>
            <td><?=$this->Form->input('main_address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th>Adres</th>
            <td><?=$this->Form->input('invoice_address', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th>Postcode</th>
            <td><?=$this->Form->input('main_postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th>Postcode</th>
            <td><?=$this->Form->input('invoice_postal', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th>Stad</th>
            <td><?=$this->Form->input('main_city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th>Stad</th>
            <td><?=$this->Form->input('invoice_city', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
        </tr>
        <tr>
            <th>Land</th>
            <td><?=$this->Form->input('main_country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
            <th>Land</th>
            <td><?=$this->Form->input('invoice_country', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?></td>
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

    $(document).ready(function(){
        if( $("#SchoolUmbrellaOrganizationId").val() != '0' ) {
            $("#SchoolExternalMainCode").attr('disabled','disabled');   
        }

        $("#SchoolUmbrellaOrganizationId").on('change', function(){
            if($(this).val() == 0) {
                $("#SchoolExternalMainCode").removeAttr('disabled');
            } else {
                $("#SchoolExternalMainCode").attr('disabled','disabled');
            }
        });

    });

    $('#SchoolEditForm').formify(
        {
            confirm : $('#btnSave'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("School gewijzigd", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify(result.join('<br />'), 'error');
            }
        }
    );
</script>