<div class="tat-content border-radius-bottom-0" style="padding-bottom: 0!important;">
    <div style="display:flex;align-items: center">
        <div style="flex-grow:1">
            <h2 style="margin:0">
                <?= $opened_by_user ? 'Automatisch uitloggen uitstellen' : 'Let op! U wordt automatisch uitgelogd' ?>
            </h2>
        </div>
        <?php if ($opened_by_user) { ?>
            <div class="close" style="flex-shrink: 1">
                <a href="#" onclick="Popup.closeLast();">
                    <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg">
                        <g stroke="currentColor" fill-rule="evenodd" stroke-linecap="round" stroke-width="3">
                            <path d="M1.5 12.5l11-11M12.5 12.5l-11-11"/>
                        </g>
                    </svg>
                </a>
            </div>
        <?php } ?>
    </div>
    <div class="divider mb24 mt10"></div>
    <div class="body2">
        <p><?= !$opened_by_user ? 'U bent al langere tijd inactief geweest. ' : '' ?>U kunt het automatisch uitloggen
            eenmalig uitstellen met:</p>
    </div>
    <div class="body2" style="padding-bottom: 20px">
        <div>
            <input id="prevent-5-min" name="prevent-logout-time" type="radio" class="radio-custom" value="1">
            <label for="prevent-5-min" class="radio-custom-label" onclick="setPostponeTimeInMinutes(5)">
                <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                          fill-rule="evenodd"
                          stroke-linecap="round"/>
                </svg>
                <span>5 minuten</span>
            </label>
        </div>
        <div>
            <input id="prevent-15-min" name="prevent-logout-time" type="radio" class="radio-custom" value="1">
            <label for="prevent-15-min" class="radio-custom-label" onclick="setPostponeTimeInMinutes(15)">
                <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                          fill-rule="evenodd"
                          stroke-linecap="round"/>
                </svg>
                <span>15 minuten</span>
            </label>
        </div>
        <div>
            <input id="prevent-30-min" name="prevent-logout-time" type="radio" class="radio-custom" value="1">
            <label for="prevent-30-min" class="radio-custom-label" onclick="setPostponeTimeInMinutes(30)">
                <svg width="13" height="13" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="currentColor" stroke-width="3" d="M1.5 5.5l4 4 6-8" fill="none"
                          fill-rule="evenodd"
                          stroke-linecap="round"/>
                </svg>
                <span>30 minuten</span>
            </label>

        </div>
    </div>
</div>
<div class="popup-footer tat-footer">
    <div style="display: flex;align-items:center;justify-content:space-between;width: 100%">
        <div class="body2" style="display: flex">
            <?php if (!$opened_by_user) { ?>
                <p>Uitloggen over: <span id="logoutCountdown">30</span> seconden</p>
            <?php } ?>
        </div>
        <div style="display: flex;">
            <button class="button button-sm primary-button mr10" style="cursor: pointer;" onclick="User.logout()">
                Log uit
            </button>
            <button id="postpone-button" class="button button-sm cta-button" style="cursor: pointer;" disabled
                    onclick="Popup.closeLast();User.postponeAutoUserLogout(postponeTime)">
                Uitstellen
            </button>
        </div>
    </div>
</div>

<script>
    var postponeTime = 0;

    function setPostponeTimeInMinutes(time) {
        postponeTime = time * 60;
        $('#postpone-button').removeAttr('disabled')
    }

    <?php if (!$opened_by_user) { ?>
    User.logoutCountdownInterval = setInterval(function () {
        $('#logoutCountdown').text(User.logoutWarningTimer);
        User.logoutWarningTimer--;
        if (User.logoutWarningTimer <= 0) {
            User.logout();
        }
    }, 1000)
    <?php } ?>
</script>