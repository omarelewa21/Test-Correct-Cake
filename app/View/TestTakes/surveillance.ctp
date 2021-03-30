<div id="buttons">
    <a href="#" class="btn highlight mr2" onclick="TestTake.toggleParticipantProgress();" id="btnSmartBoard"></a>
</div>

<h1 id="surveillanceTitle">Surveillance</h1>

<?

$alerts = 0;
$ipAlerts = 0;;

if(count($takes) == 0) {
    ?>
    <center>
        Er zijn geen toetsen om te surveilleren
    </center>
    <script type="text/javascript">
        window.onbeforeunload = null;
    </script>
    <?
}else {
    ?>

    <div>
        <div class="block" style="width: calc(100% - 300px); float:left;">
            <div class="block-head">Toetsen</div>
            <div class="block-content">
                <table class="table table-striped">
                    <tr>
                        <th>Toets</th>
                        <th>Klas(sen)</th>
                        <th width="40"></th>
                        <th width="200">Voortgang</th>
                        <th width="120"></th>
                    </tr>
                    <?php

                        foreach ($takes as $take) {

                        ?>

                        <tr>
                            <td><?= $take[0]['test'] ?></td>
                            <td>
                                <?php
                                foreach ($take as $take_item) {
                                    if (isset($take_item['schoolClass'])) {
                                        echo $take_item['schoolClass'] . '<br />';
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <a title="Browsertoetsen voor iedereen aan/uit"
                                   href="#" id=""
                                   class="btn active <?= $take['info']['allow_inbrowser_testing'] ?  'cta-button' : 'grey' ?> small mr2"
                                   onclick="TestTake.toggleInbrowserTestingForAllParticipants(this,'<?=$take[0]['uuid']?>')">
                                    <span class="fa fa-chrome"></span>
                                </a>
                            </td>
                            <td>
                                <?php
                                foreach ($take['info']['school_classes'] as $class) {
                                    ?>
                                    <div class="progress" style="margin-bottom: 0px; height:20px; margin-bottom:1px;">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="60" id="progress_<?=getUUID($take['info'], 'get')?>_<?=getUUID($class, 'get')?>" aria-valuemin="0" aria-valuemax="100" style=" line-height:22px; font-size:14px;"></div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </td>
                            <td align="center" class="nopadding">
                                <a href="#" class="btn highlight small"
                                   onclick="TestTake.setTakeTakenSelector('<?= getUUID($take['info'], 'get') . "',"  . $take['info']['time_dispensation_ids']; ?>);">
                                    Innemen
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>

        <div class="block" style="width: 280px; float:right;">
            <div class="block-head">Huidige tijd</div>
            <div class="block-content" style="font-size:76px; text-align: center" id="time">

            </div>
            <Center>
                <div class="block-content" style="font-size:17px; text-align: center; padding:0px 0px 15px 0px; color: red; display: inline-block;" id="alertRed">
                    <span class="fa fa-exclamation-triangle"></span>
                </div>

                <div class="block-content" style="font-size:17px; text-align: center; padding:0px 0px 15px 0px; color: orange;display: inline-block" id="alertOrange">
                    <span class="fa fa-exclamation-triangle"></span>
                </div>
            </Center>
        </div>

        <br clear="all"/>
    </div>

    <div class="block" id="blockProgress">
        <div class="block-head">Voortgang Studenten</div>
        <div class="block-content">
            <table class="table table-striped" style="float:left; width:48%">
                <?php

                $participants = [];

                foreach ($takes as $take) {

                    if(is_array($take['info']['test_participants'])){

                        foreach ($take['info']['test_participants'] as $key => $value) {
                            $take['info']['test_participants'][$key]['test_take_uuid'] = getUUID($take['info'], 'get');
                        }

                        $participantsForThisTake = $take['info']['test_participants'];
                        $participants = array_merge($participants, $participantsForThisTake);
                    }
                }

                $half = floor(count($participants) / 2);

                for ($i = 0; $i < $half; $i++) {
                   echo $this->element('surveillance_studentrow',['participant' => $participants[$i]]);
                }
                ?>

            </table>

            <table class="table table-striped" style="float:right; width:48%">
                <?php
                for ($i = $half; $i < count($participants); $i++) {
                    echo $this->element('surveillance_studentrow',['participant' => $participants[$i]]);
                }
                ?>
            </table>

            <br clear="all"/>
        </div>
    </div>
<?php
}
?>

<script type="text/javascript">

    startPolling(10000);
    window.onbeforeunload = confirmExit;

    if(typeof(Pusher) == 'undefined'){
        console.log('adding pusher');
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = '//js.pusher.com/5.0/pusher.min.js';

        document.getElementsByTagName('head')[0].appendChild(script);

        setTimeout(function(){
            var pusher = new Pusher("<?=Configure::read('pusher-key')?>", {
                cluster: 'eu',
                forceTLS: false,
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('stop-polling', function (data) {
                stopPolling(data.message, data.title);
            });
            channel.bind('start-polling', function (data) {
                if (!data.pollingInterval) {
                    data.pollingInterval = 10000;
                }
                startPolling(data.pollingInterval);
            });
        },
        10000)
    }




    function stopPolling(message, title) {
        if (title === undefined) {
            title = '<span class="label-danger" style="display:block">Hoge server belasting</span>';
        }

        if (message === undefined) {
            message = 'Door de hoge serverbelasting wordt het surveillance scherm tijdelijk niet geupdate.'
        }

        Popup.message({
            title: title,
            message: message
        });
        $('#page_title').html('Surveillance (inactief)');

        clearTimeout(window.surveillanceTimeout);
    }

    function startPolling(interval) {
        clearTimeout(window.surveillanceTimeout);
        window.surveillanceTimeout = setInterval(function () {
            if(document.getElementById('surveillanceTitle')){
                loadData();
            }
            else{
                clearTimeout(window.surveillanceTimeout);
            }
        }, interval);
        $('#page_title').html('Surveillance');

    }

    function confirmExit() {
        return "U bent aan het surveilleren, weet u het zeker?";
    }

    function loadData() {
        User.inactive = 0;
        $.getJSON('/test_takes/surveillance_data/?' + new Date().getTime(),
            function(response) {

                $('#time').html(response.time);


                if(response.alerts > 0) {
                    $('#alertOrange').show().find('span').html('&nbsp;' + response.alerts);
                }else{
                    $('#alertOrange').hide();
                }

                if(response.ipAlerts > 0) {
                    $('#alertRed').show().find('span').html('&nbsp;' + response.ipAlerts);
                }else{
                    $('#alertRed').hide();
                }

                var totalCounts = parseInt(response.alerts) + parseInt(response.ipAlerts);

                if(totalCounts > 0) {
                    document.title = '[' + totalCounts + '] Test Correct';
                }else{
                    document.title = 'Test Correct';
                }


                $.each(response.takes, function(id, percentage) {

                    var widthPercentage = percentage;

                    if(percentage < 15) {
                        widthPercentage = 15;
                    }

                    $('#' + id).css({
                        'width' : widthPercentage + '%'
                    }).html(percentage + '%');
                });

                $.each(response.participants, function(id, data) {
                    var widthPercentage = data.percentage;

                    if(data.percentage < 15) {
                        widthPercentage = 15;
                    }

                    if(data.status == 3) {
                        $('#buttonTaken' + id).show();
                    }else{
                        $('#buttonTaken' + id).hide();
                    }

                    if(data.status == 4 || data.status == 5 || data.status == 6) {
                        $('#buttonPlanned' + id).show();
                    }else{
                        $('#buttonPlanned' + id).hide();
                    }

                    $('#alert_events_' + id + ', #alert_ip_' + id).hide();

                    if(data.alert == true) {
                        $('#alert_events_' + id).show();
                    }

                    if(data.ip == false) {
                        $('#alert_ip_' + id).show();
                    }

                    $('#progress_participant_' + id).css({
                        'width' : widthPercentage + '%'
                    }).html(data.percentage + '%');

                    $('#label_participant_' + id).html(data.text).removeClass().addClass('label').addClass('label-' + data.label);

                    if (data.allow_inbrowser_testing) {
                        $('#allow_inbrowser_testing_' + id).addClass('cta-button').removeClass('grey')
                    } else {
                        $('#allow_inbrowser_testing_' + id).removeClass('cta-button').addClass('grey')
                    }


                });
            }
        );
    }

    loadData();


    if(TestTake.showProgress) {
        $('#btnSmartBoard').html('Naar smartboard weergave');
        $('#alertOrange, #alertRed').hide();
    }else{
        $('#btnSmartBoard').html('Naar surveillant weergave');
        $('#blockProgress').hide();
        $('#alertOrange, #alertRed').hide();
    }

    if(typeof(nonDispensationJs) == 'undefined') {

        $(document).on("click", "#test_close_confirm", function () {

            if ($('#test_close_confirm').hasClass("disabled")) {
                return false;
            }

            if (TestTake.testCloseMethod == 'Close all') {

                TestTake.setTakeTakenNoPrompt(TestTake.lastTestSelected);

            } else {

                TestTake.setTakeTakenNonDispensation(TestTake.lastTestSelected, TestTake.lastTestTimeDispensedIds)

            }

            Popup.closeLast();
        });

        $(document).on("click", "#test_close_non_dispensation", function () {
            $('#test_close_non_dispensation').addClass("highlight");
            $('#test_close_confirm').removeClass("disabled");
            $('#test_close_confirm').removeClass("grey");
            $('#test_close_confirm').addClass("blue");
            $('#test_close_all').addClass("grey");
            $('#test_close_all').removeClass("highlight");
            TestTake.testCloseMethod = 'Close non dispensation';
        });

        $(document).on("click", "#test_close_all", function () {
            $('#test_close_all').addClass("highlight");
            $('#test_close_confirm').removeClass("disabled");
            $('#test_close_confirm').removeClass("grey");
            $('#test_close_confirm').addClass("blue");
            $('#test_close_non_dispensation').addClass("grey");
            $('#test_close_non_dispensation').removeClass("highlight");
            TestTake.testCloseMethod = 'Close all';
        });
        nonDispensationJs = true;
    }
</script>
