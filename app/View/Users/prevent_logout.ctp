<div id="prevent_logout_div" class="tat-content border-radius-bottom-0" style="padding-bottom: 0!important;padding-top: 2rem!important;">
    <div style="display:flex;align-items: center">
        <div style="flex-grow:1">
            <h2 style="margin:0">
                <?= $opened_by_user ? __("Automatisch uitloggen uitstellen") : __(" Let op! Je wordt automatisch uitgelogd") ?>
            </h2>
        </div>
        <div class="close" style="flex-shrink: 1">
            <a href="#" onclick="Popup.closeLast(); User.postponeAutoUserLogout()">
                <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg">
                    <g stroke="currentColor" fill-rule="evenodd" stroke-linecap="round" stroke-width="3">
                        <path d="M1.5 12.5l11-11M12.5 12.5l-11-11"/>
                    </g>
                </svg>
            </a>
        </div>
    </div>
    <div class="divider mb16 mt16"></div>
    <div class="body2">
        <p><?= !$opened_by_user ? __("Om de veiligheid van je account te garanderen word je na 15 minuten inactiviteit automatisch uitgelogd op Test-Correct. Wil je je sessie verlengen met 2 uur?") : __("Om de veiligheid van je account te garanderen word je na 5 minuten inactiviteit automatisch uitgelogd op Test-Correct. Wil je je sessie verlengen met 30 minuten?") ?></p>
    </div>
</div>
<div class="popup-footer tat-footer pt16" style="padding-bottom: 2rem!important;">
    <div style="display: flex;align-items:center;justify-content:space-between;width: 100%">
        <div class="body2" style="display: flex; width:50%;">
            <?php if (!$opened_by_user) { ?>
            <span style="width: 100%; background-color: white; border: 1px solid var(--light-grey);height: 1rem;border-radius:4px;overflow: hidden;display:block">
                <span id="prevent_logout_progress_bar" style="display:block;background-color: var(--cta-primary); height: 1rem;transition:width 0.25s;"></span>
            </span>
            <?php } ?>
        </div>
        <div style="display: flex;">
            <button id="postpone-button" class="button button-sm cta-button" style="cursor: pointer;"
                    onclick="Popup.closeLast();User.postponeAutoUserLogout('<?= $opened_by_user ? 30 : 2*60 ?>')">
                    <?= __("Sessie verlengen")?>
            </button>
        </div>
    </div>
</div>

<script>
    clearInterval(User.userLogoutInterval);
    $(document).ready(function() {
        $('#prevent_logout_div').parent().css({'border-radius': '10px'})
    })

    <?php if (!$opened_by_user) { ?>
    let initialStartTime = User.logoutWarningTimer;
    User.logoutCountdownInterval = setInterval(function () {
        $('#prevent_logout_progress_bar').css({'width': (User.logoutWarningTimer/initialStartTime*100)+'%'}) ;
        User.logoutWarningTimer--;
        if (User.logoutWarningTimer <= 0) {
            User.logout();
        }
    }, 1000)
    <?php } ?>
</script>