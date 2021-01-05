<?php
$shortcodeUrl = $url . $shortcode;
$hasErrors = false;
if (isset($email_addresses) && !$stepback) {
    $hasErrors = true;
} ?>

<div class="popup-head tat-head border-radius-bottom-0">
    <div class="close">
        <a href="#" onclick="Popup.closeLast()">
            <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg">
                <g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-width="3">
                    <path d="M1.5 12.5l11-11M12.5 12.5l-11-11"/>
                </g>
            </svg>
        </a>
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
                <span class="body1">Gebruikmaken van elkaars toetsen en toetsvragen.</span>
            </div>
        </div>
    </div>
</div>
<div class="tat-top-img">
    <img src="img/Collegas-aan-tafel.svg" width="295px" height="209px" alt="">
</div>
<?= $this->Form->create('User') ?>
<div class="popup-content tat-content body1" style="padding-top: 16px!important;">
    <span class="mb4 display-block">Wij sturen jouw collega('s) een e-mail uitnodiging om een account aan te maken.</span>

    <div class="input-group <?php if ($hasErrors) {
        echo 'error';
    } ?>">
        <textarea id="lotsOfEmailAddresses" width="200px" height="200px" autofocus><?php
            echo $email_addresses;
            ?></textarea>
        <label for="lotsOfEmailAddresses">School e-mailadressen van jouw collega's</label>
    </div>
    <div>
        <input id="message" type="hidden" value="<?php echo $message ?>">
    </div>
    <div>
        <span class="display-block tip">Separeer meerdere e-mailadressen met komma's of puntkomma's.</span>
    </div>
    <?php if ($hasErrors): ?>
        <div class="notification error mb16">
            <?php if ($email_addresses != null): ?>
                <span class="body"><?php echo $errors ?></span>
            <?php else: ?>
                <span class="body">Geen e-mailadressen ingevuld.</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="body2">
        <span class="display-block"> Stuur je liever zelf een e-mail? Deel een link:
            <a id="copyBtn" onclick="setClipboard('<?php echo $shortcodeUrl ?>')"
               class="text-button"><?php echo $shortcodeUrl ?>
                <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg">
                    <g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-width="3">
                        <path d="M1.5 9.5V3A1.5 1.5 0 013 1.5h6.5M7 5.5h4A1.5 1.5 0 0112.5 7v4a1.5 1.5 0 01-1.5 1.5H7A1.5 1.5 0 015.5 11V7A1.5 1.5 0 017 5.5z"/>
                    </g>
                </svg>
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
            <circle class="system-secondary" cx="7" cy="7" r="7"/>
        </svg>
    </div>
    <button id="sendEmailAddresses" class="button button-md primary-button pull-right" style="cursor: pointer;">Bekijk
        e-mailvoorbeeld<svg style="margin-left: 10px" width="9" height="13" xmlns="http://www.w3.org/2000/svg">
            <path stroke="white" stroke-width="3" d="M1.5 1.5l5 5-5 5" fill="none" fill-rule="evenodd"
                  stroke-linecap="round"/>
        </svg>
    </button>
</div>
<?= $this->Form->end(); ?>

<script type="text/javascript">

    $(document).ready(function () {
        $('#sendEmailAddresses').click(function (e) {
            e.preventDefault();
            $.ajax({
                    url: '/users/tell_a_teacher',
                    data: {emailAddresses: $('#lotsOfEmailAddresses').val(), message: $('#message').val(), step: 1},
                    method: 'POST',
                    success: function (data) {
                        $('#popup_' + Popup.index).html(data);
                    },
                    onfailure: function (data) {
                        alert('nah');
                    },
                }
            );
        });
    })

    function setClipboard(value) {
        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = value;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        Notify.notify("Link gekopieerd", "info");
    }

</script>
