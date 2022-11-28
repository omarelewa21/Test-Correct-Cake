<div id="change_trial_date_modal" class="tat-content" style="padding: 1rem!important;">
    <div style="padding: 0 1rem; display: flex; justify-content: space-between; align-items: center;">
        <h2 style="display: flex"><?=__('Proefversie einddatum')?></h2>

        <span class="close primary-hover" style="cursor:pointer; color: var(--system-base)" onclick="Popup.closeLast()">
           <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg">
                <g stroke="currentColor" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-width="3">
                    <path d="M1.5 12.5l11-11M12.5 12.5l-11-11"/>
                </g>
            </svg>
        </span>
    </div>
    <div class="divider"></div>
    <div style="padding: 1rem"><?=__('Geef aan tot wanneer de gebruiker de proefversie mag gebruiken.')?></div>
    <?= $this->Form->create('User') ?>
    <div style="padding: 0 1rem">
        <?= $this->Form->input('date', [
                'id' => 'trialDate',
                'class' => 'dateField new-styling-input',
                'label' => false,
                'verify' => 'notempty'
        ]) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
<div class="tat-footer" style="padding: 1rem 2rem 2rem 2rem!important;">
    <div style="display: flex; justify-content: end; width: 100%">
        <button type="button" class="button text-button" onclick="Popup.closeLast()">
            <?=__('Annuleer')?>
        </button>
        <button type="submit" class="button primary-button" id="extend_trial_days_submit">
            <?=__('Wijzigen')?>
        </button>
    </div>
</div>

<script>
    $('.dateField').datepicker({
        dateFormat: 'dd-mm-yy'
    }).datepicker('setDate', '+14d');

    $('#UserChangeTrialDateForm').formify(
        {
            confirm: $('#extend_trial_days_submit'),
            onsuccess: function (result) {
                Popup.closeLast();
                Navigation.refresh();
            },
            onfailure: function (result) {
                console.log('error')
                console.log(result)
            }
        }
    );
</script>