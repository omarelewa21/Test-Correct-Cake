<div id="buttons">
    <a href="#" class="btn highlight mr2" onclick="TestTake.toggleParticipantProgress();" id="btnSmartBoard"></a>
</div>

<h1>Surveillance</h1>

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
                        <th width="200">Voortgang</th>
                        <th width="120"></th>
                    </tr>
                    <?
                    foreach ($takes as $take) {

                        ?>
                        <tr>
                            <td><?= $take[0]['test'] ?></td>
                            <td>
                                <?
                                foreach ($take as $take_item) {
                                    if (isset($take_item['schoolClass'])) {
                                        echo $take_item['schoolClass'] . '<br />';
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <?
                                foreach ($take['info']['school_classes'] as $class) {
                                    ?>
                                    <div class="progress" style="margin-bottom: 0px; height:20px; margin-bottom:1px;">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="60" id="progress_<?=$take['info']['id']?>_<?=$class['id']?>" aria-valuemin="0" aria-valuemax="100" style=" line-height:22px; font-size:14px;"></div>
                                    </div>
                                    <?
                                }
                                ?>
                            </td>
                            <td align="center" class="nopadding">
                                <a href="#" class="btn highlight small"
                                   onclick="TestTake.setTakeTaken(<?= $take['info']['id'] ?>);">
                                    Inleveren
                                </a>
                            </td>
                        </tr>
                    <?
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
                <?

                $participants = [];

                foreach ($takes as $take) {
                    $participants = array_merge($participants, $take['info']['test_participants']);
                }

                $half = floor(count($participants) / 2);

                for ($i = 0; $i < $half; $i++) {
                    ?>
                    <tr>
                        <td style="padding:2px 5px 2px 5px;" width="35">
                            <? if(!substr(Router::fullBaseUrl(),-5) === '.test') {?>
                            <img src="/users/profile_picture/<?=$participants[$i]['user']['id']?>" width="35" height="35" style="border-radius: 35px;" />
                            <?}?>
                        </td>
                        <td>
                            <?= $participants[$i]['user']['name_first'] ?>
                            <?= $participants[$i]['user']['name_suffix'] ?>
                            <?= $participants[$i]['user']['name'] ?>

                            <span class="fa fa-exclamation-triangle" id="alert_events_<?= $participants[$i]['id'] ?>" style="color:orange" onclick="Popup.load('/test_takes/events/<?= $participants[$i]['test_take_id'] ?>/<?= $participants[$i]['id'] ?>', 500);"></span>
                            <span class="fa fa-exclamation-triangle" id="alert_ip_<?= $participants[$i]['id'] ?>" style="color:red" onclick="TestTake.ipAlert();"></span>
                        </td>
                        <td width="70">
                            <div id="label_participant_<?=$participants[$i]['id']?>" class="label"></div>
                        </td>
                        <td width="150">
                            <div class="progress" style="margin-bottom: 0px; height:30px;">
                                <? round((100 / $participants[$i]['max_score']) * $participants[$i]['made_score']) ?>
                                <div id="progress_participant_<?=$participants[$i]['id']?>" class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="line-height:30px; font-size:16px; min-width:30px;"></div>
                            </div>
                        </td>
                        <td align="center" width="40" class="nopadding">
                            <a href="#" class="btn highlight small mr2" onclick="Popup.load('/test_takes/participant_info/<?= $participants[$i]['test_take_id'] ?>/<?= $participants[$i]['id'] ?>', 500);">
                                <span class="fa fa-info-circle"></span>
                            </a>
                        </td>
                        <td align="center" width="120" class="nopadding">
                            <a href="#" class="btn highlight small" id="buttonTaken<?=$participants[$i]['id']?>"
                               onclick="TestTake.forceTakenAway(<?= $participants[$i]['test_take_id'] ?>, <?= $participants[$i]['id'] ?>);">
                                Inleveren
                            </a>
                            <a href="#" class="btn highlight small" id="buttonPlanned<?=$participants[$i]['id']?>"
                               onclick="TestTake.forcePlanned(<?= $participants[$i]['test_take_id'] ?>, <?= $participants[$i]['id'] ?>);">
                                Heropen
                            </a>
                        </td>
                    </tr>
                <?
                }
                ?>

            </table>

            <table class="table table-striped" style="float:right; width:48%">
                <?
                for ($i = $half; $i < count($participants); $i++) {
                    ?>
                    <tr>
                        <td style="padding:2px 5px 2px 5px;" width="35">
                            <? if(!substr(Router::fullBaseUrl(),-5) === '.test') {?>
                            <img src="/users/profile_picture/<?=$participants[$i]['user']['id']?>" width="35" height="35" style="border-radius: 35px;" />
                            <?}?>
                        </td>
                        <td>
                            <?= $participants[$i]['user']['name_first'] ?>
                            <?= $participants[$i]['user']['name_suffix'] ?>
                            <?= $participants[$i]['user']['name'] ?>

                            <span class="fa fa-exclamation-triangle" id="alert_events_<?= $participants[$i]['id'] ?>" style="color:orange" onclick="Popup.load('/test_takes/events/<?= $participants[$i]['test_take_id'] ?>/<?= $participants[$i]['id'] ?>', 500);"></span>
                            <span class="fa fa-exclamation-triangle" id="alert_ip_<?= $participants[$i]['id'] ?>" style="color:red" onclick="TestTake.ipAlert();"></span>
                        </td>
                        <td width="70">
                            <div id="label_participant_<?=$participants[$i]['id']?>" class="label"></div>
                        </td>
                        <td width="150">
                            <div class="progress" style="margin-bottom: 0px; height:30px;">
                                <div id="progress_participant_<?=$participants[$i]['id']?>" class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="line-height:30px; font-size:16px; min-width:30px;"></div>
                            </div>
                        </td>
                        <td align="center" width="40" class="nopadding">
                            <a href="#" class="btn highlight small mr2" onclick="Popup.load('/test_takes/participant_info/<?= $participants[$i]['test_take_id'] ?>/<?= $participants[$i]['id'] ?>', 500);">
                                <span class="fa fa-info-circle"></span>
                            </a>
                        </td>
                        <td align="center" width="120" class="nopadding">
                            <? if ($participants[$i]['test_take_status_id'] == 3) { ?>
                                <a href="#" class="btn highlight small"
                                   onclick="TestTake.forceTakenAway(<?= $participants[$i]['test_take_id'] ?>, <?= $participants[$i]['id'] ?>);">
                                    Inleveren
                                </a>
                            <? } elseif (in_array($participants[$i]['test_take_status_id'], [5, 6, 4])) { ?>
                                <a href="#" class="btn highlight small"
                                   onclick="TestTake.forcePlanned(<?= $participants[$i]['test_take_id'] ?>, <?= $participants[$i]['id'] ?>);">
                                    Heropen
                                </a>
                            <?
                            }
                            ?>
                        </td>
                    </tr>
                <?
                }
                ?>
                </tr>
            </table>

            <br clear="all"/>
        </div>
    </div>
<?
}
?>

<script type="text/javascript">
    clearTimeout(window.surveillanceTimeout);
    window.surveillanceTimeout = setInterval(function() {
        loadData();
    }, 5000);

    window.onbeforeunload = confirmExit;
    function confirmExit() {
        return "U bent aan het surveilleren, weet u het zeker?";
    }

    function loadData() {
        User.inactive = 0;
        $.getJSON('/test_takes/surveillance_data/?' + new Date().getTime(),
            function(response) {
                console.log(response);

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
</script>