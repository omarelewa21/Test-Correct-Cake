<div class="tat-content" style="padding: 1rem!important;">
    <div style="padding: 0 1rem; display: flex; justify-content: space-between; align-items: center;">
        <h2 style="display: flex">Inloggen als gebruiker</h2>
        <span class="close primary-hover" style="cursor:pointer; color: var(--system-base)" onclick="Popup.closeLast()">
           <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg">
                <g stroke="currentColor" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-width="3">
                    <path d="M1.5 12.5l11-11M12.5 12.5l-11-11"/>
                </g>
            </svg>
        </span>
    </div>
    <div class="divider"></div>
    <div style="padding: 1rem">Voer ter verificatie opnieuw je wachtwoord in om door te gaan als deze gebruiker.</div>
    <?= $this->Form->create('User') ?>
    <div style="padding: 0 1rem">
        <?php
        echo $this->Form->input(
            'password',
            array(
                'type'        => 'password',
                'label'       => false,
                'placeholder' => 'Wachtwoord',
                'verify'      => 'notempty',
                'class'       => 'new-styling-input'
            )
        );
        ?>
    </div>
    <?= $this->Form->end() ?>
</div>
<div class="tat-footer" style="padding: 1rem 2rem 2rem 2rem!important;">
    <div style="display: flex; justify-content: end; width: 100%">
        <button type="button" class="button text-button" onclick="Popup.closeLast()">
            Annuleer
        </button>
        <button type="submit" class="button primary-button" id="take_over_user_send">
            Stuur
        </button>
    </div>
</div>

<script>
    $('#UserTakeOverUserConfirmationForm').formify(
        {
            confirm: $('#take_over_user_send'),
            enterConfirm : ['#UserPassword','#UserCaptchaString'],
            onsuccess: function (result) {

            },
            onfailure: function (result) {
                Notify.notify(result, 'error');
            }
        }
    );
</script>