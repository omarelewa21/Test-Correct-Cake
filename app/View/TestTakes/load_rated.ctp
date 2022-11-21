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
        <td>
            <?
            foreach($test_take['invigilator_users'] as $user) {
                echo $user['name_first']. ' ' . $user['name_suffix'] . ' ' . $user['name']. '<br />';
            }
            ?>
        </td>
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
        <td><?=$test_take['weight']?></td>
        <td><?=$test_take['participants_taken']?></td>
        <td><?=$test_take['participants_not_taken']?></td>
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
                
                <a href="#" onclick="TestTake.archive(this,'<?=getUUID($test_take, 'get');?>')" class="btn highlight white jquery-show-not-archived">
                    <span class="fa fa-trash mr5"></span>
                    <?= __("Archiveren")?>
                </a>
                <a href="#" onclick="TestTake.updateTestTakeStatusToDiscussed(this,'<?=getUUID($test_take, 'get');?>')" class="btn highlight white jquery-show-not-archived">
                    <span class="fa fa-arrow-right mr5"></span>
                    <?= __("Terug naar nakijken")?>
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
