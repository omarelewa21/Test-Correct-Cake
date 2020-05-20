<div class="popup-head">Registreer voor Test-correct.nl</div>
<div class="popup-content">
    <?= $this->Form->create('User') ?>
    <table class="table">
        <tr>
            <th width="400">
                Schoollocatie
            </th>
            <td>
                <?= $this->Form->input('school_location', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->school_location)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
                Voornaam
            </th>
            <td>
                <?= $this->Form->input('name_first', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->name_first)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
                Tussenvoegsel
            </th>
            <td>
                <?= $this->Form->input('name_suffix', array('style' => 'width: 440px', 'label' => false, 'value' => $user->name_suffix)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
                Achternaam
            </th>
            <td>
                <?= $this->Form->input('name', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->name)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
                Leerlingnummer
            </th>
            <td>
                <?= $this->Form->input('externalId', array('style' => 'width: 440px', 'label' => false, 'value' => '')) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
                E-mailadres
            </th>
            <td>
                <?= $this->Form->input('username', array('style' => 'width: 440px', 'label' => false, 'verify' => 'email', 'value' => $user->username)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
                Extra E-mailadres (kunnen we nu nog niet opslaan en mee werken overleggen hoe en wat.
            </th>
            <td>
                <?= $this->Form->input('email_extra', array('style' => 'width: 440px', 'label' => false, 'verify' => 'email', 'value' => $user->email_extra)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
                Geslacht
            </th>
            <td>
                <?= $this->Form->input('gender', array(
                    'options' => ['Other' => 'Anders', 'Male' => 'Man', 'Female' => 'Vrouw'], 'label' => false
                )) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
                Wachtwoord
            </th>
            <td>
                <?= $this->Form->input('password', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'type' => 'password')) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
                Wachtwoord herhaal
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
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
        Aanmaken
    </a>
</div>

<script type="text/javascript">
    $('#UserRegistereduixForm').formify(
        {
            confirm: $('#btnAddUser'),
            onsuccess: function (result) {
                Popup.closeLast();
                Popup.message({title: 'Account aangemaakt', message: 'Je account is aangemaakt, klik op Oke om naar het loginscherm te gaan'}, ()=>window.location.href='/');
                Notify.notify("Account aangemaakt", "info");

            },
            onfailure: function (result) {
                Notify.notify("Gebruiker kon niet worden aangemaakt", "error");
            }
        }
    );
</script>