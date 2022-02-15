<div class="popup-head"><?= __("Docent")?></div>
<div class="popup-content">
    <?=$this->Form->create('User') ?>
    <table class="table">
        <tr>
            <th></th>
        </tr>
        <tr>
            <th width="130">
            <?= __("Voornaam")?>
            </th>
            <td>
                <?=$this->Form->input('name_first', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Tussenvoegsel")?>
            </th>
            <td>
                <?=$this->Form->input('name_suffix', array('style' => 'width: 185px', 'label' => false)) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Achternaam")?>
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Afkorting")?>
            </th>
            <td>
                <?=$this->Form->input('abbreviation', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Geverifieerd")?>
            </th>
            <td><a style="cursor:pointer" class="btn <?= $this->request->data['account_verified']?  'blue': 'grey' ?>" id="updateVerified">
                    <?= $this->request->data['account_verified'] ?  $this->request->data['account_verified'] : 'Niet geverifieerd'?>
                </a>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("E-mailadres")?>
            </th>
            <td>
                <?=$this->Form->input('username', array('style' => 'width: 185px', 'label' => false, 'verify' => 'email')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Stamnummer")?>
            </th>
            <td>
                <?=$this->Form->input('teacher_external_id', array('type' => 'text','style' => 'width: 185px', 'label' => false)) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Nieuw wachtwoord")?>
            </th>
            <td>
                <?=$this->Form->input('password', array('style' => 'width: 185px', 'label' => false)) ?>
            </td>
        </tr>
        <tr>
            <th colspan="2"><?= __("Notities")?></th>
        </tr>
        <tr>
            <td colspan="2">
                <?=$this->Form->input('note', [
                    'style' => 'width:330px; height:100px',
                    'type' => 'textarea',
                    'label' => false
                ])?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
    <?= __("Opslaan")?>
    </a>
</div>

<script type="text/javascript">
    $('#UserEditForm').formify(
        {
            confirm : $('#btnAddUser'),
            onbeforesubmit: function(e) {
                var password = $('#UserPassword').val();
                if(password !== '' && password.length < 8) {
                    Notify.notify($.i18n('Het nieuwe wachtwoord moet minimaal 8 karakters bevatten.'), 'error');
                    return 'cancelSubmit';
                }
            },
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("Gebruiker gewijzigd")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                if (result.length != 0)  {
                    Notify.notify(result[0], "error");
                } else {
                    Notify.notify('<?= __("Gebruiker kon niet worden aangemaakt")?>', "error");
                }

            }
        }
    );

    $('#updateVerified').click(function(e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        var uuid = '<?= getUUID($this->request->data, "get") ?>';
        $.ajax({
            url: '/users/toggle_verified/' + uuid,
            type: 'put',
            dataType: 'json',
            contentType: 'application/json',
            success: function (data) {
                if (data.account_verified) {
                    $('#updateVerified').text(data.account_verified).addClass('blue').removeClass('grey');
                } else {
                    $('#updateVerified').text('<?= __("Niet geverifieerd")?>').addClass('grey').removeClass('blue');
                }
            },
            failure: function(data) {
                alert('error');
                // console.dir(data);
            }
        });

    });


</script>
