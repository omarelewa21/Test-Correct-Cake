<div class="popup-head">Koepelorganisatie</div>
<div class="popup-content">
    <?=$this->Form->create('UmbrellaOrganisation') ?>
    <table class="table">
        <tr>
            <th width="130">
                Naam
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>

            <th width="130">
                Accountmanager
            </th>
            <td>
                <?=$this->Form->input('user_id', array('style' => 'width: 185px', 'label' => false, 'options' => $accountmanagers, 'selected' => getUUID($organisation['user'], 'get'))); ?>
            </td>
        </tr>
        <tr>
            <th width="130">
                Klantcode
            </th>
            <td >
                <?=$this->Form->input('customer_code', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>

            
            <th width="130">
                Brin code
            </th>
            <td>
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
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddOrganisation">
        Wijzigen
    </a>
</div>

<script type="text/javascript">
    $('#UmbrellaOrganisationEditForm').formify(
        {
            confirm : $('#btnAddOrganisation'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Organisatie gewijzigd", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify(result.join('<br />'), 'error');
            }
        }
    );
</script>