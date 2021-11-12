<div class="popup-head"><?= __("Informatie")?></div>
<div class="popup-content">

    <center>
        <img src="/users/profile_picture/<?=getUUID($participant['user'], 'get');?>" width="100" height="100" style="border-radius: 100px; margin-bottom: 20px;" />
    </center>

    <? if(empty($participant['total_time']) && empty($participant['rating'])) { ?>
        <center>
        <?= __("Geen informatie voor deze Student")?>
        </center>
    <? } else{ ?>
        <table class="table striped">
            <tr>
                <th width="140"><?= __("Cijfer voor deze toets")?></th>
                <td><?=empty($participant['rating']) ? '-' : $participant['rating']?></td>
            </tr>
            <tr>
                <th width="140"><?= __("Cijfer voor dit vak")?></th>
                <td><?=isset($participant['user']['average_ratings'][0]) ? round($participant['user']['average_ratings'][0]['rating'], 1) : '-'?></td>
            </tr>
            <tr>
                <th><?= __("Tijd totaal")?></th>
                <td><?=round($participant['total_time'] / 60)?> min</td>
            </tr>
            <tr>
                <th><?= __("Tijd per vraag")?></th>
                <td>
                    <?
                    if(!empty($participant['total_time']) && !empty($participant['questions'])) {
                        echo round(($participant['total_time'] / $participant['questions']) / 60, 1) . ' min';
                    }else{
                        echo '-';
                    }?>
                </td>
            </tr>
            <tr>
                <th><?= __("Duurde het langst")?></th>
                <td>
                    <?=$participant['longest_answer']['question']['question']?>
                </td>
            </tr>
            <tr>
                <th><?= __("Notities")?></th>
                <td>
                    <?=empty($participant['invigilator_note']) ? __("Geen notities") : nl2br($participant['invigilator_note'])?>
                </td>
            </tr>
        </table>
    <? } ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Sluiten")?>
    </a>
    <a href="#" class="btn grey mt5 mr5 highlight pull-right" onclick="Popup.load('/messages/send/<?=getUUID($participant['user'], 'get');?>');">
    <?= __("Bericht sturen")?>
    </a>
    <a href="#" class="btn grey mt5 mr5 highlight pull-right" onclick="Popup.closeLast(); Navigation.load('/test_takes/view_results/<?=$take_id?>/<?=$participant_id?>');">
    <?= __("Toets bekijken")?>
    </a>
    <a href="#" class="btn grey mt5 mr5 highlight pull-right" onclick="Popup.closeLast(); Navigation.load('/analyses/student/<?=getUUID($participant['user'], 'get');?>');">
    <?= __("Analyse")?>
    </a>
</div>