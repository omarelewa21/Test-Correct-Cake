<div class="popup-head">Registreer voor Test-Correct.nl</div>
<div class="popup-content" style="padding-top:0">
    <?= $this->Form->create('User') ?>
    <table class="table">
        <tr>
            <th colspan="2"><h2 style="margin-top: 0px; margin-bottom: 0px;">School gegevens</h2></th>
        </tr>
        <tr>
            <th width="400">
                Naam van de school
            </th>
            <td>
                <?= $this->Form->input('school_location', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->school_location)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
                Website URL
            </th>
            <td>
                <?= $this->Form->input('website_url', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->website_url)) ?>
            </td>
        </tr>
        <tr>
            <th colspan="2">Bezoekadres van uw school</th>
        </tr>
        <tr>
            <th width="400" style="padding-left:20px">
                Straat en huisnummer
            </th>
            <td>
                <?= $this->Form->input('address', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->address)) ?>
            </td>
        </tr>

        <tr>
            <th width="400"  style="padding-left:20px">
              Postcode
            </th>
            <td>
                <?= $this->Form->input('postcode', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->postcode)) ?>
            </td>
        </tr>
        <tr>
            <th width="400" style="padding-left:20px" >
               Plaats
            </th>
            <td>
                <?= $this->Form->input('city', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->city)) ?>
            </td>
        </tr>
        <tr>
            <th colspan="2"><h2>Uw gegevens</h2></th>
        </tr>

        <tr>
            <th width="400">
               Gewenste aanhef
            </th>
            <td>
                <?= $this->Form->input('gender', array(
                    'options' => [ 'Male' => 'Man', 'Female' => 'Vrouw', 'Other' => 'Anders'], 'label' => false
                )) ?>

            </td>
        </tr>

        <tr class="hide" id="GenderDifferent">
            <th width="400">
                Aanhef anders
            </th>
            <td>
                <?= $this->Form->input('gender_different', array('style' => 'width: 440px', 'label' => false,'verify' => 'notempty', 'value' => $user->name_first)) ?>
            </td>
        </tr>

        <tr>
            <th width="400">
               Voornaam
            </th>
            <td>
                <?= $this->Form->input('name_first', array('style' => 'width: 440px', 'label' => false,  'verify' => 'notempty', 'value' => $user->name_first)) ?>
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
                E-mailadres
            </th>
            <td>
                <?= $this->Form->input('username', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->username)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
                Mobielnummer <small> nodig ter verificatie</small>
            </th>
            <td>
                <?= $this->Form->input('mobile', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->username)) ?>
            </td>
        </tr>
        <tr>
            <th width="400">
                Welke vakken geeft u op welk niveau
            </th>
            <td>
                <?= $this->Form->input('subjects', array('style' => 'width: 440px', 'label' => false, 'verify' => 'notempty', 'value' => $user->subjects)) ?>
            </td>
        </tr>

        <tr>
            <th width="400">
                Hoe ben je bij ons terecht gekomen?
            </th>
            <td>
                <?= $this->Form->textarea('how_did_you_hear_about_test_correct', array('style' => 'width: 440px;height:80px', 'label' => false, 'value' => $user->remarks )) ?>
            </td>
        </tr>

        <tr>
            <th width="400">
                Eventuele opmerkingen
            </th>
            <td>
                <?= $this->Form->textarea('remarks', array('style' => 'width: 440px;height:80px', 'label' => false, 'value' => $user->remarks )) ?>
            </td>
        </tr>

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
    $('#UserRegisterNewTeacherForm').formify(
        {
            confirm: $('#btnAddUser'),
            onsuccess: function (result) {
                Popup.closeLast();
                Popup.message({title: 'Account aangemaakt', message: 'Je account is aangemaakt, klik op Oke om naar het loginscherm te gaan'}, ()=>window.location.href='/');
                Notify.notify("Account aangemaakt", "info");

                var redirectTo = 'https://www.test-correct.nl/bedankt-aanmelding-docent';

                if (window.location.href.indexOf('portal.test-correct.nl') === 12) {
                    redirectTo = 'http://3780499.hs-sites.com/bedankpagina-test';
                }

                window.location.href = redirectTo;

            },
            onfailure: function (result) {
                this.hideAllErrors();
                var that = this;
                Object.keys(result.errors).forEach(function(key) {
                    that.showError(key);
                });

                Notify.notify("Gebruiker a kon niet worden aangemaakt", "error");
            },
            hideAllErrors: function() {
                var that = this;
                var fields = [
                    'school_location',
                    'website_url',
                    'address',
                    'postcode',
                    'city',
                    'gender',
                    'gender_different',
                    'name_first',
                    'name',
                    'username',
                    'subjects',
                ].forEach(function(field) {
                    var el = +that.toId(field);
                    if ($(el).length !== 0) {
                        $(el).removeClass('verify-error');
                    }
                })
            },
            showError(field) {
                var el = this.toId(field);
                if ($(el).length !== 0) {
                    $(el).addClass('verify-error');
                }
            },
            toId(field) {
                var camelCase = field.replace(/([-_]\w)/g, function(g){ return g[1].toUpperCase()});
                var newFieldName = camelCase.charAt(0).toUpperCase() + camelCase.slice(1);

                var id = '#User'+ newFieldName;
                return id;
            }
        }
    );
    $(document).ready(function() {
        $(document).on('change', '#UserGender', function(e) {
            $('#GenderDifferent').css('display', 'none');
            if (e.target.value === 'Other') {
                $('#GenderDifferent').css('display', 'table-row');
                $('#UserGenderDifferent').trigger('keyup');
            }
        }).on('keyup', '#UserGenderDifferent', function(e) {
            console.log('keyUp');
            if($(this).val() == '') {
                $(this).removeClass().addClass('verify');
            }
        });


        $('#UserGender').trigger('change');
    });

</script>