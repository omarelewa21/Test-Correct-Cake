<div class="popup-head">
    Inloggen op Test-Correct
</div>

<div class="popup-content">
   <!-- MarkO (07-09-2018): melding weggehaald op verzoek van Alex
<p>
        Op dit moment zij we bezig met onderhoud. Het kan zijn dat bepaalde functionaliteiten tijdelijk niet volledig werken. Het onderhoud zal vrijdag 24 augustus afgerond worden. Excuses voor eventueel ongemak.
    </p>-->

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
            <th>Password</th>
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

    <a href="#" class="btn mt5 mr5 blue pull-right btnLoginTest btnLogin" id="" style="display:none;">
        <i class="fa fa-check mr5"></i> Test login
    </a>

    <a href="#" class="btn mt5 mr5 blue pull-right btnLogin" id="">
        <i class="fa fa-check mr5"></i> Inloggen
    </a>

    <a href="#" class="btn mt5 mr5 grey pull-right" onclick="User.forgotPassword();">
        Wachtwoord vergeten
    </a>

    <a href="#" onclick="window.closeApplication();" class="btn grey pull-right mt5 mr5" id="btnClose" style="display: none;">
        Sluiten
    </a>
    <a href="/logout" class="btn grey pull-right mt5 mr5" id="btnCloseChromebook" style="display: none;">
        Sluiten
    </a>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        url = document.URL;

        if(url.indexOf('local') > 0 || url.indexOf('testportal') > 0){
            $(".btnLoginTest").fadeIn();
        }

        $(".appType").val(Core.appType);

        $(".btnLoginTest").on('click',function(){
            Core.inApp = true;
        });
    });

    var messageHandler = function(event) {
        event.source.postMessage( {'reply': 'Sandbox received: ' + event.data}, event.origin);
    };

    window.addEventListener('message', messageHandler);

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
