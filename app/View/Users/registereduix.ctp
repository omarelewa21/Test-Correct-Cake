<div class="popup-head"><?= __("Registreer voor Test-Correct.nl")?></div>
<div class="popup-content">
    <?= $this->Form->create('User') ?>
    <table class="table">
        <tr>
            <th width="400">
            <?= __("Schoollocatie")?>
            </th>
            <td>
                <?= $this->Form->input('school_location', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->school_location)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
            <?= __("Voornaam")?>
            </th>
            <td>
                <?= $this->Form->input('name_first', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->name_first)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
            <?= __("Tussenvoegsel")?>
            </th>
            <td>
                <?= $this->Form->input('name_suffix', array('style' => 'width: 440px', 'label' => false, 'value' => $user->name_suffix)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
            <?= __("Achternaam")?>
            </th>
            <td>
                <?= $this->Form->input('name', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->name)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
            <?= __("Leerlingnummer")?>
            </th>
            <td>
                <?= $this->Form->input('externalId', array('style' => 'width: 440px', 'label' => false, 'value' => '')) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
            <?= __("E-mailadres")?>
            </th>
            <td>
                <?= $this->Form->input('username', array('style' => 'width: 440px', 'label' => false, 'verify' => 'email', 'value' => $user->username)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
            <?= __("Geslacht")?>
            </th>
            <td>
                <?= $this->Form->input('gender', array(
                    'options' => ['Other' => __("Anders"), 'Male' => __("Man"), 'Female' => __("Vrouw")], 'label' => false
                )) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
            <?= __("Wachtwoord")?>
            </th>
            <td>
                <?= $this->Form->input('password', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'type' => 'password')) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
            <?= __("Wachtwoord herhaal")?>
            </th>
            <td>
                <?= $this->Form->input('password_confirm', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'type' => 'password')) ?>
            </td>
        </tr>
        <?= $this->Form->input('edu-ix-signature', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'type' => 'hidden', 'value' => $_GET['signature'])) ?>


    </table>
    <?= $this->Form->end(); ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="window.location.href='/'">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
    <?= __("Aanmaken")?>
    </a>
</div>

<script type="text/javascript">
    $('#UserRegistereduixForm').formify(
        {
            confirm: $('#btnAddUser'),
            onsuccess: function (result) {
                Popup.closeLast();
                Popup.message({title: 'Account aangemaakt', message: '<?= __("Je account is aangemaakt, klik op Oke om naar het loginscherm te gaan")?>'}, ()=>window.location.href='/');
                Notify.notify('<?= __("Account aangemaakt")?>', "info");

            },
            onfailure: function (result) {
                Notify.notify('<?= __("Gebruiker kon niet worden aangemaakt")?>', "error");
            }
        }
    );
</script>