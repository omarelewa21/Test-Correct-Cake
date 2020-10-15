<div class="popup-head">
    Inloggen op Test-Correct
    <?php
		if(MaintenanceHelper::getInstance()->isInMaintenanceMode()){
            echo '<br /><strong style="color:#ff6666">Maintenance mode</strong>';
        }
    ?>
</div>

<div class="popup-content">
    <?= $this->Form->create('User') ?>
    <table width="100%" class="table table-striped form">
        <tr>
            <th width="120">E-mail</th>
            <td>
                <?php
                echo $this->Form->input(
                    'email',
                    array(
                        'type' => 'text',
                        'label' => false,
                        'placeholder' => 'Gebruikersnaam',
                        'verify' => 'notempty'
                    )
                );
                ?>
            </td>
        </tr>
        <tr>
            <th>Wachtwoord</th>
            <td>
                <?php
                echo $this->Form->input(
                    'password',
                    array(
                        'type' => 'password',
                        'label' => false,
                        'placeholder' => 'Wachtwoord',
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
            <i class="fa fa-check mr5"></i> Test login
        </a>

        <a href="#" class="btn mt5 mr5 blue pull-right btnLogin" id="" style="display:none;">
            <i class="fa fa-check mr5"></i> Inloggen
        </a>
    <? } else { ?>
        <a href="#" class="btn mt5 mr5 blue pull-right btnLogin" id="">
            <i class="fa fa-check mr5"></i> Inloggen
        </a>
    <? }?>
    <a href="#" class="btn mt5 mr5 grey pull-right" onclick="User.forgotPassword();">
        Wachtwoord vergeten
    </a>

    <a href="#" onclick="return closeApplication('quit');" class="btn grey pull-right mt5 mr5" id="btnClose" style="display: none;">
        Sluiten
    </a>
    <a href="/logout" class="btn grey pull-right mt5 mr5" id="btnCloseChromebook" style="display: none;">
        Sluiten
    </a>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        url = document.URL;

        if(url.indexOf('local') > 0 || url.endsWith('/test') > 0){
            $(".btnLogin").fadeIn();
        }

        $(".appType").val(Core.appType);

    <? if(substr_count(Router::url( $this->here, true ),'testportal.test-correct')){ ?>
        $('#UserPassword, #UserEmail').on('keydown',function(e){
            if(e.keyCode == 13){
                $('.btnLoginTest').trigger('click');
            }
        });
        $(".btnLoginTest").on('click',function(){
            Core.inApp = true;
        });
    <? } ?>
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
                Notify.notify("Je bent ingelogd", "info");
                Core.afterLogin();
            },
            onfailure : function(result) {
                if( typeof result.message !== typeof undefined && result.message != '') {
                    Notify.notify(result.message, 'error');
                }else{
                    Notify.notify("Inloggegevens incorrect", "error");
                }
            }
        }
    );

    setTimeout(function() {
        $('#UserEmail').focus();
    }, 500);
</script>
