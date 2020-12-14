<div class="popup-head tat-head">
    <div class="close">
        <a href="#" onclick="Popup.closeLast()"><img src="img/ico/close.svg" alt=""></a>
    </div>
    <div class="tat-top-text">
        <img class="inline-block" src="img/ico/send-blue-big.svg" width="53px" height="41px" alt="">
        <h1 class="inline-block">Nodig een collega uit!</h1>
        <div class="tat-usp">
            <h6 class="">Samen met je collega's kun je:</h6>
            <div>
                <img class="tat-check" src="img/ico/checkmark-small.svg" width="16px" height="16px" alt="">
                <span class="body1">Overleggen over de voortgang van jouw studenten en ervaringen delen.</span>
            </div>
            <div>
                <img class="tat-check" src="img/ico/checkmark-small.svg" width="16px" height="16px" alt="">
                <span class="body1">Gebruikmaken van elkaars toetsen en toetsvragen</span>
            </div>
        </div>
    </div>
</div>
<div class="tat-top-img">
    <img src="img/Collegas-aan-tafel.svg" width="295px" height="209px" alt="">
</div>
<?= $this->Form->create('User') ?>
<div class="popup-content tat-content body1">
    <span class="mb-4 display-block">Wij sturen jouw collega('s) een e-mail uitnodiging om een account aan te maken.</span>

    <div class="input-group">
        <textarea id="lotsOfEmailAddresses" width="200px" height="200px" autofocus></textarea>
        <label for="lotsOfEmailAddresses">School e-mailadressen van jouw collega's</label>
    </div>
    <div>
        <span class="display-block tip">Separeer meerdere e-mailadressen met komma's</span>
    </div>
    <div class="body2">
        <span class="display-block"> Stuur je liever zelf een e-mail? Deel een link:
            <a class="bold" href="#">email url
            <img class="inline-block" src="img/ico/copy.svg" alt="">
            </a>
        </span>
    </div>
</div>

<div class="popup-footer tat-footer">
    <div class="indicator">
        <svg style="margin-right: 5px" height="14px" width="14px" xmlns="http://www.w3.org/2000/svg">
            <circle class="primary" cx="7" cy="7" r="7"/>
        </svg>
        <svg height="14px" width="14px" xmlns="http://www.w3.org/2000/svg">
            <circle class="light-grey" cx="7" cy="7" r="7"/>
        </svg>
    </div>
    <button id="sendEmailAddresses" class="button button-md primary-button pull-right">Bekijk e-mailvoorbeeld<img style="margin-left: 10px" src="img/ico/chevron.svg" alt=""></button>
</div>
<?= $this->Form->end(); ?>

<script type="text/javascript">

    $(document).ready(function () {
        $('#sendEmailAddresses').click(function (e) {
            e.preventDefault();
            $.ajax({
                    url: '/users/tell_a_teacher',
                    data: {emailAddresses: $('#lotsOfEmailAddresses').val()},
                    method: 'POST',
                    success: function (data) {
                        alert('bravo');
                        console.dir(data);
                    },
                    onfailure: function (data) {
                        alert('nah');
                        console.dir(data);
                    },
                }
            );
        });
    })


    $('#tellATeacherTableBody').on('keydown', 'input', function () {
        var tr = $(this).parents('tr:first');
        if (tr.is(':last-child')) {
            appendRow();
        }
    });
    tellATeacherTableJs = true;

    function appendRow() {
        $('#tellATeacherTableBody').append($('#rowTemplate tr:first').clone());
    }

    $('#UserTellATeacherForm').formify(
        {
            confirm: $('#btnAddUser'),
            onbeforesubmit: function () {
                $('#tellATeacherTableBody tr').each(function () {
                    var allEmpty = true;
                    $(this).find(':input').each(function () {
                        if ($(this).val() !== '') {
                            allEmpty = false;
                        }
                    });
                    if (allEmpty == false) {
                        $(this).find('.verify-email').attr('verify', 'email');
                        $(this).find('.verify-notempty').attr('verify', 'notempty');
                    } else {
                        $(this).remove();
                    }
                });
            },
            onaftersubmit: function () {
                $('#tellATeacherTableBody .verify-email').attr('verify', '');
                $('#tellATeacherTableBody .verify-notempty').attr('verify', '');
                appendRow();
            },
            onsuccess: function (result) {
                Popup.closeLast();
                var n = [];
                $('#UserTellATeacherForm .name_first').each(function () {
                    if ($(this).val().length > 0) {
                        n.push($(this).val());
                    }
                });

                var removeTags = function (str) {
                    if ((str === null) || (str === ''))
                        return false;
                    else
                        str = str.toString();
                    return str.replace(/(<([^>]+)>)/ig, '');
                }


                if (n.length == 1) {
                    Notify.notify("Super bedankt!<br />We hebben " + removeTags(n[0]) + " uitgenodigd voor Test-Correct", "info");
                } else {
                    Notify.notify("Super bedankt!<br />We hebben " + removeTags(n.join(' en ')) + " uitgenodigd voor Test-Correct", "info");
                }
                Navigation.refresh();
            },
            onfailure: function (result) {
                if (result.error == 'username') {
                    Notify.notify("Er is al een collega met dit e-mailadres bij ons bekend", "error");
                } else if (result.error.includes('e-mail')) {
                    Notify.notify(result.error, "error");
                } else if (result.error == 'user_roles') {
                    Notify.notify('U kunt een collega pas uitnodigen nadat er een actuele periode is aangemaakt.', 'error')
                } else {
                    Notify.notify("Collega kon niet worden uitgenodigd", "error");
                }
            }
        }
    );
</script>
