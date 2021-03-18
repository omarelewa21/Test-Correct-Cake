<div class="popup-head">
<?= __("Inloggen op Test-Correct")?>
    <?php
		if(MaintenanceHelper::getInstance()->isInMaintenanceMode()){
            echo '<br /><strong style="color:#ff6666">Maintenance mode</strong>';
        }
    ?>
</div>

<div class="popup-content">
    <?= $this->Form->create('User') ?>
    <table width="100%" class="table table-striped form">
        <tr id="SeleniumWarning" style="background-color:yellow;display:none;">
            <th>
            <?= __("Selenium Test is actief")?>
            </th>
        </tr>
        <tr>
            <th width="120"><?= __("E-mail")?></th>
            <td>
                <?php
                echo $this->Form->input(
                    'email',
                    array(
                        'type' => 'text',
                        'label' => false,
                        'placeholder' => __("Gebruikersnaam"),
                        'verify' => 'notempty'
                    )
                );
                ?>
            </td>
        </tr>
        <tr>
            <th><?= __("Wachtwoord")?></th>
            <td>
                <?php
                echo $this->Form->input(
                    'password',
                    array(
                        'type' => 'password',
                        'label' => false,
                        'placeholder' => __("Wachtwoord"),
                        'verify' => 'notempty'
                    )
                );
                ?>
            </td>
        </tr>
    </table>

    <input type="hidden" name="appType" value="" class="appType">

    <?= $this->Form->end() ?>
</div>

<div class="popup-footer">
    <? if(substr_count(Router::url( $this->here, true ),'testportal.test-correct')){ ?>
        <a href="#" class="btn mt5 mr5 blue pull-right btnLoginTest btnLogin" id="">
            <i class="fa fa-check mr5"></i> <?= __("Test login")?>
        </a>

        <a href="#" class="btn mt5 mr5 blue pull-right btnLogin" id="" style="display:none;">
            <i class="fa fa-check mr5"></i> <?= __("Inloggen")?>
        </a>
    <? } else { ?>
        <a href="#" class="btn mt5 mr5 blue pull-right btnLogin" id="">
            <i class="fa fa-check mr5"></i> <?= __("Inloggen")?>
        </a>
    <? }?>
    <a href="#" class="btn mt5 mr5 grey pull-right" onclick="User.forgotPassword();">
    <?= __("Wachtwoord vergeten")?>
    </a>

    <a href="#" onclick="return closeApplication('quit');" class="btn grey pull-right mt5 mr5" id="btnClose" style="display: none;">
    <?= __("Sluiten")?>
    </a>
    <a href="/logout" class="btn grey pull-right mt5 mr5" id="btnCloseChromebook" style="display: none;">
    <?= __("Sluiten")?>
    </a>
    <a onclick="closeApplication('close')" class="btn grey pull-right mt5 mr5" id="btnCloseElectron" style="display: none;">
    <?= __("Sluiten")?>
    </a>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        url = document.URL;

        if(url.indexOf('local') > 0 || url.endsWith('/test') > 0){
            $(".btnLogin").fadeIn();
        }

        $(".appType").val(Core.appType);

    <?php if(substr_count(Router::url( $this->here, true ),'testportal.test-correct')){ ?>
        $('#UserPassword, #UserEmail').on('keydown',function(e){
            if(e.keyCode == 13){
                $('.btnLoginTest').trigger('click');
            }
        });
        // $(".btnLoginTest").on('click',function(){
        //     Core.inApp = true;
        // });

        $.getJSON('/testing/selenium_state',
            function (state) {
                if (state.status == 1) {
                    $('#SeleniumWarning').show();
                    Notify.notify('<?= __("Selenium test is actief")?>', 'error')
                }
            }
        );

    <?php } ?>

    });

    var messageHandler = function(event) {
        event.source.postMessage( {'reply': 'Sandbox received: ' + event.data}, event.origin);
    };

    window.addEventListener('message', messageHandler);

    function closeApplication(cmd)
    {
        if (cmd=='quit')
        {
            open('/', '_self').close();
        } else if (cmd=='close') {
            try {
                electron.closeApp();
            } catch (error) {
                window.close();
            }
        }
        return false;
    }

    setInterval(function() {
        if(window.isInApp) {
            $('#btnClose').show();
        }

        if(window.navigator.userAgent.indexOf('CrOS') > 0) {
            $('#btnCloseChromebook').show();
        }

        try {
            if (typeof(electron.closeApp) === typeof(Function)) {
                $('#btnCloseElectron').show();
            }
        } catch (error) {}

    }, 500);

    $('#UserLoginForm').formify(
        {
            confirm : $('.btnLogin'),
            enterConfirm : $('#UserPassword'),
            onsuccess : function(result) {
                if(Core.inApp && result.message != '' && typeof result.message !== typeof undefined && result.message !== null) {
                    Notify.notify(result.message);
                }

                Popup.closeLast();
                Notify.notify('<?= __("Je bent ingelogd")?>', "info");
                Core.afterLogin();
            },
            onfailure : function(result) {
                if( typeof result.message !== typeof undefined && result.message != '') {
                    Notify.notify(result.message, 'error');
                }else{
                    Notify.notify('<?= __("Inloggegevens incorrect")?>', "error");
                }
            }
        }
    );

    setTimeout(function() {
        $('#UserEmail').focus();
    }, 500);
</script>
