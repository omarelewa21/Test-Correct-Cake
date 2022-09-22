<?
foreach($test_takes as $test_take) {
    ?>
    <tr>
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
            <?=$test_take['test']['question_count']?>
        </td>
        <td>
            <?php if(isset($test_take['scheduled_by']) && !empty($test_take['scheduled_by'])) { ?>
                <?= $test_take['scheduled_by_user_name'] ?>
            <?php } else { ?>
                <?=$test_take['user']['name_first']?>
                <?=$test_take['user']['name_suffix']?>
                <?=$test_take['user']['name']?>
            <?php } ?>
        </td>
        <td><?=$test_take['test']['subject']['name']?></td>
        <td><?=date('d-m-Y', strtotime($test_take['time_start']))?></td>
        <td>
            <?
                if($test_take['retake'] == 0) {
                    ?>
                    <div class="label label-info"><?= __("Standaard")?></div>
                    <?
                }else {
                    ?>
                    <div class="label label-warning"><?= __("Inhaaltoets")?></div>
                <?
                }
            ?>
        </td>
        <td><?=$test_take['weight']?></td>
        <td class="nopadding" width="100">
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_take_<?=getUUID($test_take, 'get');?>">
                <span class="fa fa-ellipsis-v"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="Navigation.load('/test_takes/view/<?=getUUID($test_take, 'get');?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="test_take_<?=getUUID($test_take, 'get');?>">
                <a href="#" class="btn highlight white" onclick="TestTake.copyDirectlink('<?=$test_take['directLink']?>');">
                    <span class="fa fa-clipboard mr5"></span>
                    <?= __("Kopieer toetslink")?>
                </a>
                <a href="#" class="btn highlight white" onclick="Navigation.load('/test_takes/view/<?=getUUID($test_take, 'get');?>');">
                    <span class="fa fa-edit mr5"></span>
                    <?= __("Wijzigen")?>
                </a>
                <? if($test_take['test_take_status_id'] == 1) { ?>
                    <? if(date('d-m-Y', strtotime($test_take['time_start'])) == date('d-m-Y')) {?>
                        <a href="#" class="btn  <? if(!$test_take['invigilators_acceptable']){?>
                                                                        toets_afnemen_disabled
                                                                <?}else{?>
                                                                        highlight white
                                                                <?}?>"
                            <? if($test_take['invigilators_acceptable']){?>
                                onclick="TestTake.startTake('<?=getUUID($test_take, 'get');?>');"
                            <?}?>
                            <? if(!$test_take['invigilators_acceptable']){?>
                                onclick="TestTake.noStartTake('<?=$test_take['invigilators_unacceptable_message']?>');"
                            <?}?>
                           >
                            <span class="fa fa-calendar mr5"></span>
                            <?= __("Nu afnemen")?>
                        </a>
                    <? } ?>
                    <a href="#" onclick="Popup.showPdfTestTake('<?=getUUID($test_take, 'get');?>', 1000)" class="btn highlight white">
                        <span class="fa fa-file-o mr5"></span>
                        <?= __("Exporteren / Printen")?>
                    </a>
                    <?php if($test_take['test']['has_pdf_attachments']) {?>
                    <a href="#"
                       onclick="Loading.show();Popup.load('/tests/pdf_showPDFAttachment/<?= getUUID($test_take['test'], 'get') ?>', 1000)"
                       class="btn highlight white">
                        <span class="fa fa-file-o mr5"></span>
                        <?= __("Printen pdf bijlagen")?>
                    </a>
                    <? } ?>
                    <a href="#" class="btn highlight white" onclick="TestTake.delete(<?=getUUID($test_take, 'getQuoted');?>);">
                        <span class="fa fa-remove mr5"></span>
                        <?= __("Verwijderen")?>
                    </a>
                <? } ?>
            </div>
        </td>
    </tr>
    <?
}
?>
