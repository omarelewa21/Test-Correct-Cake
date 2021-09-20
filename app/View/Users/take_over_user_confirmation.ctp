<div class="tat-content">
    <?= $this->Form->create('User') ?>
    <div>
        <div>Wachtwoord</div>
        <div>
                <?php
                echo $this->Form->input(
                    'password',
                    array(
                        'type'        => 'password',
                        'label'       => false,
                        'placeholder' => 'Wachtwoord',
                        'verify'      => 'notempty'
                    )
                );
                ?>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>
<div class="tat-footer">
    <div style="display: flex; justify-content: end; width: 100%">
        <button class="button text-button" onclick="Popup.closeLast()">
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
            confirm : $('#take_over_user_send'),
            onsuccess : function(result) {

            },
            onfailure : function(result) {
                Notify.notify(result, 'error');
            }
        }
    );
</script>