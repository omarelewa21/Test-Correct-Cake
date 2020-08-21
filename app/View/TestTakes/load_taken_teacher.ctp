<?
foreach($test_takes as $test_take) {
    ?>
    <tr>
        <td><?=$test_take['test']['name']?> [<?=$test_take['test']['abbreviation']?>]</td>
        <td>
            <?
            foreach($test_take['school_classes'] as $class) {
                echo $class['name'].'<br />';
            }
            ?>
        </td>
<!--        <td>-->
<!--            --><?//
//            foreach($test_take['invigilator_users'] as $user) {
//                echo $user['name_first']. ' ' . $user['name_suffix'] . ' ' . $user['name']. '<br />';
//            }
//            ?>
<!--        </td>-->
        <td>
            <?=$test_take['test']['question_count']?>
        </td>
        <td>
            <?=$test_take['user']['name_first']?>
            <?=$test_take['user']['name_suffix']?>
            <?=$test_take['user']['name']?>
        </td>
        <td><?=$test_take['test']['subject']['name']?></td>
        <td><?=date('d-m-Y', strtotime($test_take['time_start']))?></td>
        <td>
            <?
            if($test_take['retake'] == 0) {
                ?>
                <div class="label label-info">Standaard</div>
                <?
            }else{
                ?>
                <div class="label label-warning">Inhaaltoets</div>
            <?
            }
            ?>
        </td>
        <td>
            <?
            switch ($test_take['test_take_status_id']) {
                case 6:
                    $statusText = 'Afgenomen';
                    break;
                case 7:
                    $statusText = 'CO-Learning gestart';
                    break;
                default:
                    $statusText = 'Onbekend';
                    break;
            }
            echo $statusText;


             ?>
        </td>
        <td><?=$test_take['weight']?></td>
        <td class="nopadding" width="100">
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_take_<?=$test_take['id']?>">
                <span class="fa fa-list-ul"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="Navigation.load('/test_takes/view/<?=getUUID($test_take, 'get');?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="test_take_<?=$test_take['id']?>">
                <a href="#" class="btn highlight white" onclick="Navigation.load('/test_takes/view/<?=getUUID($test_take, 'get');?>');">
                    <span class="fa fa-folder-open-o mr5"></span>
                    Openen
                </a>
                <a href="#" onclick="Popup.load('/test_takes/answers_preview/<?=getUUID($test_take, 'get');?>', 1000)" class="btn highlight white">
                    <span class="fa fa-file mr5"></span>
                    Antwoorden PDF
                </a>
                <a href="#" onclick="$.get('/test_takes/skip_discussion/<?=getUUID($test_take, 'get');?>',[], function(){ Navigation.load('/test_takes/to_rate');})" class="btn highlight white">
                    <span class="fa fa-forward mr5"></span>
                   Meteen naar nakijken
                </a>
            </div>
        </td>
    </tr>
    <?
}
?>