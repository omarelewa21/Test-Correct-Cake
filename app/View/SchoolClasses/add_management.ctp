<div class="popup-head">Koppelen</div>
<div class="popup-content">
    <?=$this->Form->create('Manager') ?>
    <table class="table">
        <tr>
            <th width="130">
                Gebruiker
            </th>
            <td>
                <?=$this->Form->input('manager_id', array('style' => 'width: 185px', 'label' => false, 'options' => $managements)) ?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
        Aanmaken
    </a>
</div>

<script type="text/javascript">
    $('#ManagerAddManagementForm').formify(
        {
            confirm : $('#btnAddUser'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify("Gebruiker gekoppeld", "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                Notify.notify("Gebruiker kon niet worden aangemaakt", "error");
            }
        }
    );

    $('select').select2();
</script>