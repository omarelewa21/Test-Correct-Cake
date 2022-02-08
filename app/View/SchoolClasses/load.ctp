<?
foreach($classes as $class) {
    ?>
    <tr>
        <td><?=$class['name']?></td>
        <td class="nopadding">
            <?php if((bool) $class['demo'] !== true){?>
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="class_<?=getUUID($class, 'get');?>">
                <span class="fa fa-ellipsis-v"></span>
            </a>
            <?php } else { ?>
            <a href="#" class="btn white pull-right dropblock-left" id="class_<?=getUUID($class, 'get');?>" onClick="Notify.notify('<?= __("De demoklas kan niet aangepast of verwijderd worden")?>','error');">
                <span class="fa fa-ellipsis-v"></span>
            </a>
            <?php } ?>
            <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/school_classes/view/<?=getUUID($class, 'get');?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="class_<?=getUUID($class, 'get');?>">
                <?php if((bool) $class['demo'] !== true){?>
                <a href="#" class="btn highlight white" onclick="Popup.load('/school_classes/edit/<?=getUUID($class, 'get');?>', 800);">
                    <span class="fa fa-edit mr5"></span>
                    <?= __("Wijzigen")?>
                </a>
                <a href="#" class="btn highlight white" onclick="SchoolClass.delete('<?=getUUID($class, 'get');?>', 0);">
                    <span class="fa fa-remove mr5"></span>
                    <?= __("Verwijderen")?>
                </a>
                <?php } ?>
            </div>
        </td>
    </tr>
    <?
}
?>