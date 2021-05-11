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
        <tr id="SeleniumWarning" style="background-color:yellow;display:none;">
            <th colspan="2">
                Selenium Test is actief
            </th>
        </tr>
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

        <tr id="captcha_container" style="display:none">
            <th>Beveiligingscode</th>
            <td>
                <img src="" id="captcha"/><br/>
                <?php
                echo $this->Form->input(
                    'captcha_string',
                    array(
                        'type' => 'text',
                        'label' => false,
                        'placeholder' => 'Beveiligingscode',
                        'verify' => ''
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
    <a href="#"  onclick="ChromeOSdopost();" class="btn grey pull-right mt5 mr5" id="btnCloseChromebook" style="display: none;">
        Sluiten
    </a>
    <a onclick="closeApplication('close')" class="btn grey pull-right mt5 mr5" id="btnCloseElectron" style="display: none;">
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
                    Notify.notify("Selenium test is actief", 'error')
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
            enterConfirm : ['#UserPassword','#UserCaptchaString'],
            onsuccess : function(result) {
                if(Core.inApp && result.message != '' && typeof result.message !== typeof undefined && result.message !== null) {
                    Notify.notify(result.message);
                }

                Popup.closeLast();
                Notify.notify("Je bent ingelogd", "info");
                Core.afterLogin();
            },
            onfailure : function(result) {
                if(typeof result.showCaptcha !== typeof undefined && result.showCaptcha == true){
                    refreshCaptcha();
                }
                if( typeof result.message !== typeof undefined && result.message != '') {
                    Notify.notify(result.message, 'error');
                }else{
                    Notify.notify("Inloggegevens incorrect", "error");
                }
            }
        }
    );
  
    function ChromeOSdopost(){
        $.post("/users/logout", function (){window.location.href = '/';});  
    }

    function refreshCaptcha(){
        document.getElementById('captcha_container').style = '';
        document.getElementById('captcha').src = '/img/securimage_show.php?' + Math.random();
    }

    setTimeout(function() {
        $('#UserEmail').focus();
    }, 500);
</script>
