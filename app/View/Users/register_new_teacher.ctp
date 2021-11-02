<div class="popup-head">
    <?php if (isset($in_app) && $in_app) { ?>
        <?= __("We hebben nog wat gegevens van je nodig")?>
    <?php } else { ?>
        <?= __("Registreer voor Test-Correct")?>
    <?php } ?>
</div>

<div class="popup-content" style="padding-top:0">
    <?= $this->element('register_new_teacher_form'); ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn highlight mt5 mr5 pull-right blue" id="btnAddUser">
    <?= __("Opslaan")?>
    </a>
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="window.location.href='/'">
    <?= __("Annuleer")?>
    </a>
</div>

<script type="text/javascript">
    updateAndClosePopup = {
        confirm: $('#btnAddUser'),
        onsuccess: function (result) {
            // console.dir(result);
            Popup.closeLast();
            Notify.notify('<?= __("Je gegevens zijn opgeslagen")?>', "info");
        },
        onfailure: function (result) {
            this.hideAllErrors();
            var that = this;
            Object.keys(result.errors).forEach(function (key) {
                that.showError(key);
            });

            Notify.notify('<?= __("Gebruiker kon niet worden aangemaakt")?>', "error");
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
                        title: '<?= __("Account aangemaakt")?>',
                        message: '<?= __("Je account is aangemaakt, klik op Oke om naar het loginscherm te gaan")?>'
                    }, () => window.location.href = '/');
                    Notify.notify('<?= __("Account aangemaakt")?>', "info");

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

                    Notify.notify('<?= __("Gebruiker kon niet worden aangemaakt")?>', "error");
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
            // console.log('keyUp');
            if ($(this).val() == '') {
                $(this).removeClass().addClass('verify');
            }
        });


        $('#UserGender').trigger('change');
    });

</script>
