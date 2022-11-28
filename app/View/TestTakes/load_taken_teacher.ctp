<?
foreach($test_takes as $test_take) {
    ?>
    <tr
            class=" <?= $test_take['archived'] ?  'jquery-archived grey': 'jquery-not-archived'  ?>
         <?= $hide_when_archived ?  'jquery-hide-when-archived': ''  ?>

       ">
        <td>
            <?= $test_take['test']['test_kind_id'] == 4 ? 'OPDRACHT ' : ''; ?>
            <?=$test_take['test']['name']?> [<?=$test_take['test']['abbreviation']?>]
        </td>
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
                <div class="label label-info"><?= __("Standaard")?></div>
                <?
            }else{
                ?>
                <div class="label label-warning"><?= __("Inhaaltoets")?></div>
                <?
            }
            ?>
        </td>
        <td>
            <?
            switch ($test_take['test_take_status_id']) {
                case 6:
                    $statusText = __("Afgenomen");
                    break;
                case 7:
                    $statusText = __("CO-Learning gestart");
                    break;
                default:
                    $statusText = __("Onbekend");
                    break;
            }
            echo $statusText;


            ?>
        </td>
        <td><?=$test_take['weight']?></td>
        <td class="nopadding" width="100">
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_take_<?=$test_take['id']?>">
                <span class="fa fa-ellipsis-v"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="TestTake.loadDetails(this, '<?=getUUID($test_take, 'get');?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="test_take_<?=$test_take['id']?>">

                <a href="#" class="btn highlight white jquery-show-not-archived" onclick="Navigation.load('/test_takes/view/<?=getUUID($test_take, 'get');?>');">
                    <span class="fa fa-folder-open-o mr5"></span>
                    <?= __("Openen")?>
                </a>
                <a href="#" onclick="Popup.load('/test_takes/answers_preview/<?=getUUID($test_take, 'get');?>', 1000)" class="btn highlight white jquery-show-not-archived">
                    <span class="fa fa-file mr5"></span>
                    <?= __("Antwoorden PDF")?>
                </a>
                <a href="#" onclick="Popup.load('/test_takes/skip_discussion_popup/<?=getUUID($test_take, 'get');?>',500);" class="btn highlight white jquery-show-not-archived">
                    <span class="fa fa-forward mr5"></span>
                    <?= __("Meteen naar nakijken")?>
                </a>
                <a href="#" onclick="TestTake.archive(this,'<?=getUUID($test_take, 'get');?>')" class="btn highlight white jquery-show-not-archived">
                    <span class="fa fa-trash mr5"></span>
                    <?= __("Archiveren")?>
                </a>

                <a href="#" onclick="TestTake.unarchive(this, '<?=getUUID($test_take, 'get');?>')" class="btn highlight white jquery-show-when-archived">
                    <span class="fa fa-recycle mr5"></span>
                    <?= __("Dearchiveer")?>
                </a>

            </div>
        </td>
    </tr>
    <?
}
?>
