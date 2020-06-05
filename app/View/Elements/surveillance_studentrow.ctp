<tr>
    <td style="padding:2px 5px 2px 5px;" width="35">
        <? if(!substr(Router::fullBaseUrl(),-5) === '.test') {?>
        <img src="/users/profile_picture/<?=$participant['user']['id']?>" width="35" height="35" style="border-radius: 35px;" />
        <?}?>
    </td>
    <td>
        <?= $participant['user']['name_first'] ?>
        <?= $participant['user']['name_suffix'] ?>
        <?= $participant['user']['name'] ?>

        <span class="fa fa-exclamation-triangle" id="alert_events_<?= $participant['id'] ?>" style="color:orange" onclick="Popup.load('/test_takes/events/<?= $participant['test_take_id'] ?>/<?= $participant['id'] ?>', 500);"></span>
        <span class="fa fa-exclamation-triangle" id="alert_ip_<?= $participant['id'] ?>" style="color:red" onclick="TestTake.ipAlert();"></span>
    </td>
    <td width="70">
        <div id="label_participant_<?=$participant['id']?>" class="label"></div>
    </td>
    <td width="150">
        <div class="progress" style="margin-bottom: 0px; height:30px;">
            <? round((100 / $participant['max_score']) * $participant['made_score']) ?>
            <div id="progress_participant_<?=$participant['id']?>" class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="line-height:30px; font-size:16px; min-width:30px;"></div>
        </div>
    </td>
    <td align="center" width="40" class="nopadding">
        <a href="#" class="btn highlight small mr2" onclick="Popup.load('/test_takes/participant_info/<?= $participant['test_take_id'] ?>/<?= $participant['id'] ?>', 500);">
            <span class="fa fa-info-circle"></span>
        </a>
    </td>
    <td align="center" width="120" class="nopadding">
        <?
            $style = 'display:none';
            if ($participant['test_take_status_id'] == 3) {
                $style = '';
            }
        ?>
        <a href="#" class="btn highlight small" style="<?=$style?>" id="buttonTaken<?=$participant['id']?>"
           onclick="TestTake.forceTakenAway(<?= $participant['test_take_id'] ?>, <?= $participant['id'] ?>);">
            Inleveren
        </a>
        <?
            $style = 'display:none';
            if (in_array($participant['test_take_status_id'], [5, 6, 4])) {
                $style = '';
            }
        ?>
        <a href="#" class="btn highlight small" style="<?=$style?>" id="buttonPlanned<?=$participant['id']?>"
           onclick="TestTake.forcePlanned(<?= $participant['test_take_id'] ?>, <?= $participant['id'] ?>);">
            Heropen
        </a>
    </td>
</tr>