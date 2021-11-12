<div class="popup-head"><?= __("Registratie voor Test-Correct.nl")?></div>
<div class="popup-content">

    <?= $this->element('register_new_teacher_form'); ?>

</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
    <?= __("Wijzigen")?>
    </a>
</div>

<script type="text/javascript">
    $('#UserEditRegisterNewTeacherForm').formify(
        {
            confirm: $('#btnAddUser'),
            onsuccess: function (result) {
                Popup.closeLast();
                Notify.notify('<?= __("Account gewijzigd")?>', "info");
            },
            onfailure: function (result) {
                this.hideAllErrors();
                var that = this;
                Object.keys(result.errors).forEach(function(key) {
                    that.showError(key);
                });

                Notify.notify('<?= __("Gebruiker a kon niet worden Gewijzigd")?>', "error");
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
                    'abbreviation',
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
            if (e.target.value === 'Other' || e.target.value === 'different') {
                $('#GenderDifferent').css('display', 'table-row');
                $('#UserGenderDifferent').trigger('keyup');
            }
        }).on('keyup', '#UserGenderDifferent', function(e) {
            // console.log('keyUp');
            if($(this).val() == '') {
                $(this).removeClass().addClass('verify');
            }
        });


        $('#UserGender').trigger('change');
    });

</script>