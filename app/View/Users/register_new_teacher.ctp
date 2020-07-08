<div class="popup-head">
    <?php if (isset($in_app) && $in_app) { ?>
        We hebben nog wat gegevens van je nodig
    <?php } else { ?>
        Registreer voor Test-Correct
    <?php } ?>
</div>

<div class="popup-content" style="padding-top:0">
    <?= $this->Form->create('User') ?>
    <table class="table">
        <table class="table">
            <?php if (isset($in_app) && $in_app) { ?>
                <tr>
                    <th colspan="4">
                        <p style="background-color: #d9edf7; padding:15px; margin-top:0">
                            We hebben nog wat gegevens van je nodig voordat we je klassen en toetsen kunnen aanmaken in
                            Test-Correct:
                        </p>
                    </th>
                </tr>
            <?php } ?>
            <tr>
                <th colspan="2"><h2 style="margin-top: 0px; margin-bottom: 0px;">School gegevens</h2></th>
            </tr>
            <tr>
                <th width="170">
                    Naam
                </th>
                <td>
                    <?= $this->Form->input('school_location', array('style' => 'width: 180px', 'label' => false, 'verify' => 'notempty', 'value' => $user->school_location)) ?>
                </td>

                <th width="80">
                    Website
                </th>
                <td>
                    <?= $this->Form->input('website_url', array('placeholder' => 'https://www.mijn-school.nl', 'style' => 'width: 210px', 'label' => false, 'verify' => 'notempty', 'value' => $user->website_url)) ?>
                </td>
            </tr>

            <tr>
                <th width="170">
                    Adres
                </th>
                <td colspan="3">
                    <?= $this->Form->input('address', array('placeholder' => 'Straatnaam en huisnummer', 'style' => 'width: 580px', 'label' => false, 'verify' => 'notempty', 'value' => $user->address)) ?>
                </td>
            </tr>

            <tr>
                <th width="170">
                    Postcode
                </th>
                <td>
                    <?= $this->Form->input('postcode', array('style' => 'width: 180px', 'label' => false, 'verify' => 'notempty', 'value' => $user->postcode)) ?>
                </td>

                <th width="80">
                    Plaats
                </th>
                <td>
                    <?= $this->Form->input('city', array('style' => 'width: 210px', 'label' => false, 'verify' => 'notempty', 'value' => $user->city)) ?>
                </td>


            </tr>
            <tr>
                <th colspan="2"><h2 style="margin:0">Uw gegevens</h2></th>
            </tr>

            <tr>
                <th width="170">
                    Aanhef
                </th>
                <td>
                    <?= $this->Form->input('gender', array(
                        'style'   => 'width: 191px',
                        'options' => ['Mr' => 'Meneer', 'Mrs' => 'Mevrouw', 'Other' => 'Anders'], 'label' => false, 'value' => $user->gender
                    )) ?>

                </td>

                <th width="80">
                    Voornaam
                </th>
                <td>
                    <?= $this->Form->input('name_first', array('style' => 'width: 210px', 'label' => false, 'verify' => 'notempty', 'value' => $user->name_first)) ?>
                </td>

            </tr>

            <tr class="hide" id="GenderDifferent">
                <th width="170">
                    Aanhef anders
                </th>
                <td>
                    <?= $this->Form->input('gender_different', array('style' => 'width: 180px', 'label' => false, 'verify' => 'notempty', 'value' => $user->gender_different)) ?>
                </td>

            </tr>

            <tr>
                <th width="170">
                    Tussenvoegsel
                </th>

                <td>

                    <?= $this->Form->input('name_suffix', array('style' => 'width: 180px', 'label' => false, 'value' => $user->name_suffix)) ?>
                </td>
                <th width="80">
                    Achternaam
                </th>

                <td>
                    <?= $this->Form->input('name', array('style' => 'width: 210px', 'label' => false, 'verify' => 'notempty', 'value' => $user->name)) ?>
                </td>


            </tr>

            <tr>
                <th width="170">
                    E-mailadres
                </th>
                <td colspan="3">
                    <?= $this->Form->input('username', array('placeholder' => 'uw schoolmail', 'style' => 'width: 580px', 'label' => false, 'verify' => 'notempty', 'value' => $user->username)) ?>
                </td>
            </tr>
            <tr>
                <th width="170">
                    Afkorting
                </th>
                <td colspan="3">
                    <?= $this->Form->input('abbreviation', array('placeholder' => 'Welke afkorting wordt voor u gebruikt binnen uw school?', 'style' => 'width: 580px', 'label' => false, 'verify' => 'notempty', 'value' => $user->username)) ?>
                </td>
            </tr>
            <tr>
                <th width="170">
                    Mobiel nummer
                </th>
                <td colspan="2">
                    <?= $this->Form->input('mobile', array('style' => 'width: 340px', 'label' => false, 'verify' => 'notempty', 'value' => $user->mobile)) ?>
                </td>
                <th><small> (nodig ter verificatie)</small></th>
            </tr>
            <tr>
                <th width="170">
                    Vakken/niveaus
                </th>
                <td colspan="3">
                    <?= $this->Form->input('subjects', array('style' => 'width: 580px', 'label' => false, 'verify' => 'notempty', 'value' => $user->subjects)) ?>
                </td>
                <th colspan="2"><small></small></th>
            </tr>

            <tr>
                <th width="240">
                    Hoe ben je bij ons terecht gekomen?
                </th>
                <td colspan="3">
                    <?= $this->Form->textarea('how_did_you_hear_about_test_correct', array('style' => 'width: 580px;height:30px', 'label' => false, 'value' => $user->how_did_you_hear_about_test_correct)) ?>
                </td>
            </tr>
            <tr>

                <th width="240">
                    Eventuele opmerkingen
                </th>
                <td colspan="3">
                    <?= $this->Form->textarea('remarks', array('style' => 'width: 580px;height:30px', 'label' => false, 'value' => $user->remarks)) ?>
                </td>
            </tr>

        </table>
        <?= $this->Form->end(); ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn highlight mt5 mr5 pull-right blue" id="btnAddUser">
        Opslaan
    </a>
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="window.location.href='/'">
        Annuleer
    </a>
</div>

<script type="text/javascript">
    updateAndClosePopup = {
        confirm: $('#btnAddUser'),
        onsuccess: function (result) {
            console.dir(result);
            Popup.closeLast();
            Notify.notify("Je gegevens zijn opgeslagen", "info");
        },
        onfailure: function (result) {
            this.hideAllErrors();
            var that = this;
            Object.keys(result.errors).forEach(function (key) {
                that.showError(key);
            });

            Notify.notify("Gebruiker kon niet worden aangemaakt", "error");
        },
        hideAllErrors: function () {
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
                'abbreviation',
            ].forEach(function (field) {
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
            var camelCase = field.replace(/([-_]\w)/g, function (g) {
                return g[1].toUpperCase()
            });
            var newFieldName = camelCase.charAt(0).toUpperCase() + camelCase.slice(1);

            var id = '#User' + newFieldName;
            return id;
        }
    }

    if ($('#UserUploadTestForm').length) {
        $('#UserUploadTestForm').formify(updateAndClosePopup);
    }
    if ($('#UserUploadClassForm').length) {
        $('#UserUploadClassForm').formify(updateAndClosePopup);
    }


    if ($('#UserRegisterNewTeacherForm').length) {
        $('#UserRegisterNewTeacherForm').formify(
            {
                confirm: $('#btnAddUser'),
                onsuccess: function (result) {
                    Popup.closeLast();
                    Popup.message({
                        title: 'Account aangemaakt',
                        message: 'Je account is aangemaakt, klik op Oke om naar het loginscherm te gaan'
                    }, () => window.location.href = '/');
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
                    Object.keys(result.errors).forEach(function (key) {
                        that.showError(key);
                    });

                    Notify.notify("Gebruiker kon niet worden aangemaakt", "error");
                },
                hideAllErrors: function () {
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
                        'abbreviation',
                    ].forEach(function (field) {
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
                    var camelCase = field.replace(/([-_]\w)/g, function (g) {
                        return g[1].toUpperCase()
                    });
                    var newFieldName = camelCase.charAt(0).toUpperCase() + camelCase.slice(1);

                    var id = '#User' + newFieldName;
                    return id;
                }
            }
        );
    }
    $(document).ready(function () {
        $(document).on('change', '#UserGender', function (e) {
            $('#GenderDifferent').css('display', 'none');
            if (e.target.value === 'Other') {
                $('#GenderDifferent').css('display', 'table-row');
                $('#UserGenderDifferent').trigger('keyup');
            }
        }).on('keyup', '#UserGenderDifferent', function (e) {
            console.log('keyUp');
            if ($(this).val() == '') {
                $(this).removeClass().addClass('verify');
            }
        });


        $('#UserGender').trigger('change');
    });

</script>
