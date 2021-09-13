<div id="prevent_logout_div" class="tat-content border-radius-bottom-0" style="padding-bottom: 0!important;padding-top: 2rem!important;">
    <div style="display:flex;align-items: center">
        <div style="flex-grow:1">
            <h2 style="margin:0">
                Lees en accepteer onze algemene voorwaarden
            </h2>
        </div>
        <?php if ($closeable) { ?>
        <div class="close" style="flex-shrink: 1">
            <a href="#" onclick="Popup.closeLast(); User.postponeAutoUserLogout()">
                <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg">
                    <g stroke="currentColor" fill-rule="evenodd" stroke-linecap="round" stroke-width="3">
                        <path d="M1.5 12.5l11-11M12.5 12.5l-11-11"/>
                    </g>
                </svg>
            </a>
        </div>
        <?php } ?>
    </div>
    <div class="divider mb16 mt16"></div>
    <div class="body1">
        <div>
            <p>Op de dienst Test-Correct zijn de algemene voorwaarden van The Teach & Learn Company B.V./Test-Correct van toepassing. Wij vragen je onze algemene voorwaarden te lezen en accepteren. Dit is nodig om van onze producten gebruik te kunnen blijven maken.</p>
        </div>
        <?php if(!$closeable) {?>
            <div class="dashboard" style="margin: 0">
                <div class="notification warning" style="padding: 4px;border-radius: 5px;">
                    <div style="display: flex;align-items: center;justify-content: center;">
                        <?= $this->element('warning') ?>
                        <span style="font-size: 16px; font-weight: bold;margin-left: 10px; line-height: 22px">14 dagen zijn verstreken. Accepteer de algemene voorwaarden om Test-Correct verder te gebruiken</span>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div style="border-radius: 10px; border:solid 1px var(--blue-grey);height: 400px; overflow: hidden;box-sizing: border-box">
            <iframe id="terms_iframe" src="https://support.test-correct.nl/hubfs/Downloads%20Documenten%20Website/Algemene-Voorwaarden-2021-The-Teach-and-Learn-Company-BV-Test-Correct-versie-20210618.pdf" frameborder="0" width="100%" height="100%"></iframe>
        </div>
        <div>
            <p class="body2 bold">Door middel van het accepteren bevestigt u dat u de <a href="https://support.test-correct.nl/hubfs/Downloads%20Documenten%20Website/Algemene-Voorwaarden-2021-The-Teach-and-Learn-Company-BV-Test-Correct-versie-20210618.pdf" target="_blank" style="text-decoration: underline">algemene voorwaarden</a> heeft ontvangen, gelezen en begrepen.</p>
        </div>
    </div>
</div>
<div class="popup-footer tat-footer pt16" style="padding-bottom: 2rem!important;">
    <div style="display: flex;align-items:center;width: 100%">
        <?php if ($closeable) { ?>
        <button class="button text-button flex"
                style="font-size: 18px; align-items: center;padding:0!important;"
                onclick="Popup.closeLast()">
            <?= $this->element('arrow-left') ?>
            <span style="margin-left: 10px">Later lezen en accepteren</span>
        </button>
        <?php }?>
        <button id="terms_accept_btn" class="button button-md cta-button" style="cursor: pointer;margin-left:auto;"
                onclick="userAcceptedTerms()">
            Accepteren
        </button>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#prevent_logout_div').parent().css({'border-radius': '10px'})
    })

    function userAcceptedTerms() {
        $.ajax({
            url: '/users/accept_general_terms',
            method: 'POST',
            data: {},
            success: function () {
                Popup.closeLast();
                Navigation.reload();
            }
        })
    }
</script>