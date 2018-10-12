<div class="popup-head">Informatie</div>
<div class="popup-content">

    <center>
        <img src="/users/profile_picture/<?=$participant['user']['id']?>" width="100" height="100" style="border-radius: 100px; margin-bottom: 20px;" />
    </center>

    <? if(empty($participant['total_time']) && empty($participant['rating'])) { ?>
        <center>
            Geen informatie voor deze Student
        </center>
    <? } else{ ?>
        <table class="table striped">
            <tr>
                <th width="140">Cijfer voor deze toets</th>
                <td><?=empty($participant['rating']) ? '-' : $participant['rating']?></td>
            </tr>
            <tr>
                <th width="140">Cijfer voor dit vak</th>
                <td><?=isset($participant['user']['average_ratings'][0]) ? round($participant['user']['average_ratings'][0]['rating'], 1) : '-'?></td>
            </tr>
            <tr>
                <th>Tijd totaal</th>
                <td><?=round($participant['total_time'] / 60)?> min</td>
            </tr>
            <tr>
                <th>Tijd per vraag</th>
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
                <th>Duurde het langst</th>
                <td>
                    <?=$participant['longest_answer']['question']['question']?>
                </td>
            </tr>
            <tr>
                <th>Notities</th>
                <td>
                    <?=empty($participant['invigilator_note']) ? 'Geen notities' : nl2br($participant['invigilator_note'])?>
                </td>
            </tr>
        </table>
    <? } ?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Sluiten
    </a>
    <a href="#" class="btn grey mt5 mr5 highlight pull-right" onclick="Popup.load('/messages/send/<?=$participant['user_id']?>');">
        Bericht sturen
    </a>
    <a href="#" class="btn grey mt5 mr5 highlight pull-right" onclick="Popup.closeLast(); Navigation.load('/test_takes/view_results/<?=$take_id?>/<?=$participant_id?>');">
        Toets bekijken
    </a>
    <a href="#" class="btn grey mt5 mr5 highlight pull-right" onclick="Popup.closeLast(); Navigation.load('/analyses/student/<?=$participant['user_id']?>');">
        Analyse
    </a>
</div>