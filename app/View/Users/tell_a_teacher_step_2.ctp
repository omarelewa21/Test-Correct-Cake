<?php
$firstname = AuthComponent::user('name_first');
$lastname = AuthComponent::user('name');
$fullname = $firstname . ' ' . $lastname;
if (!empty(AuthComponent::user('name_suffix'))) {
    $suffix = AuthComponent::user('name_suffix');
    $fullname = $firstname . ' ' . $suffix . ' ' . $lastname;
}
?>

<?= $this->Form->create('User') ?>
<div class="popup-head email-preview padding-20">
    <div class="close">
        <a href="#" onclick="Popup.closeLast()"><svg width="14" height="14" xmlns="http://www.w3.org/2000/svg">
                <g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-width="3">
                    <path d="M1.5 12.5l11-11M12.5 12.5l-11-11"/>
                </g>
            </svg>
        </a>
    </div>
    <div class="email-preview-body">
        <div class="overlay"></div>
        <div class="fixed-notification notification notification-shadow warning">
            <span class="body">
                <svg style="align-self: center;margin-right: .5rem;" width="4" height="14"
                     xmlns="http://www.w3.org/2000/svg">
                    <g class="attention" fill-rule="evenodd">
                        <path d="M1.615 0h.77A1.5 1.5 0 013.88 1.61l-.45 6.06a1.436 1.436 0 01-2.863 0L.12 1.61A1.5 1.5 0 011.615 0z"/>
                        <circle cx="2" cy="12" r="2"/>
                    </g>
                </svg>
                Voorbeeld van de e-mail aan jouw collega's. Pas eventueel het bericht aan</span>
        </div>
        <div class="tat-head border-radius-bottom-0">
            <div class="tat-top-logo">
                <img width="164px" height="30px" src="img/Logo-Test-Correct-wit.svg" alt="">
            </div>
            <div class="">
                <h5 class="inline-block" style="margin-bottom: 16px!important;">Je collega <?php echo $fullname ?> heeft
                    je uitgenodigd voor Test-Correct</h5>
                <div class="tat-top-text tat-usp">
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
        <div class="tat-content body1">
            <div class="input-group">
                <textarea onkeyup="countCharacters()" id="message" width="200px" height="200px" autofocus maxlength="640"><?php echo $message ?></textarea>
                <label for="message">Het bericht aan jouw collega's </label>
            </div>
            <div>
                <span id="maxCharacters" class="tip"></span>
            </div>

            <?php if ($errorMessage): ?>
                <div class="notification error mt8">
                    <span class="body"><?php echo $errorMessage ?></span>
                </div>
            <?php endif; ?>
            <div>
                <input id="lotsOfEmailAddresses" type="hidden" value="<?php echo $email_addresses ?>">
            </div>
            <div style="opacity: 50%;">
                <button class="button stretched cta-button button-md" style="width: 100%;margin-top: .5rem" disabled>Maak
                    jouw gratis account
                </button>
            </div>
        </div>
    </div>
</div>
<div class="popup-footer tat-footer">
    <a id="backToStep1" class="text-button button pull-left terug-btn"><i class="fa fa-chevron-left mr10"></i>Terug naar
        e-mailadressen invullen</a>
    <div class="indicator">
        <svg style="margin-right: 5px" height="14px" width="14px" xmlns="http://www.w3.org/2000/svg">
            <circle class="primary" cx="7" cy="7" r="7"/>
        </svg>
        <svg height="14px" width="14px" xmlns="http://www.w3.org/2000/svg">
            <circle class="primary" cx="7" cy="7" r="7"/>
        </svg>
    </div>
    <button id="sendInvitations" class="button button-md primary-button pull-right" style="cursor: pointer;">Stuur
        uitnodiging<svg style="margin-left: 10px" width="14" height="13" xmlns="http://www.w3.org/2000/svg">
            <g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke="#ffffff" stroke-width="3">
                <path d="M1.5 6.5h10M6.5 1.5l5 5-5 5"/>
            </g>
        </svg>

    </button>
</div>
<?= $this->Form->end(); ?>

<script type="text/javascript">
    function countCharacters(){
        var max = $('#message').attr('maxlength');
        var chars = $('#message').val().length

        $('#maxCharacters').html( chars + ' van '+ max + ' karakters');

        if (chars >= max) {
            $('#maxCharacters').parent().addClass('notification error ');
            $('#maxCharacters').addClass('black');
        } else {
            $('#maxCharacters').parent().removeClass('notification error black');
            $('#maxCharacters').removeClass('black');
        }
    }

    $(document).ready(function () {
        countCharacters();
        $('#sendInvitations').click(function (e) {
            e.preventDefault();
            $.ajax({
                    url: '/users/tell_a_teacher',
                    data: {emailAddresses: $('#lotsOfEmailAddresses').val(), message: $('#message').val(), step: 2},
                    method: 'POST',
                    success: function (data) {
                        $('#popup_' + Popup.index).html(data);
                    },
                }
            );
        });



        $('#backToStep1').click(function (e) {
            e.preventDefault();
            $.ajax({
                url: '/users/tell_a_teacher',
                data: {
                    emailAddresses: $('#lotsOfEmailAddresses').val(),
                    message: $('#message').val(),
                    step: 1,
                    stepback: true
                },
                method: 'POST',
                success: function (data) {
                    // console.log(data);
                    $('#popup_' + Popup.index).html(data);
                }
            })
        });
    });


</script>
