<?
foreach($classes as $class) {
    ?>
    <tr>
        <td><?=$class['name']?></td>
        <td class="nopadding">
            <?php if((bool) $class['demo'] !== true){?>
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="class_<?=$class['id']?>">
                <span class="fa fa-list-ul"></span>
            </a>
            <?php } else { ?>
            <a href="#" class="btn white pull-right dropblock-left" id="class_<?=$class['id']?>" onClick="Notify.notify('De demoklas kan niet aangepast of verwijderd worden','error');">
                <span class="fa fa-list-ul"></span>
            </a>
            <?php } ?>
            <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/school_classes/view/<?=$class['id']?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="class_<?=$class['id']?>">
                <?php if((bool) $class['demo'] !== true){?>
                <a href="#" class="btn highlight white" onclick="Popup.load('/school_classes/edit/<?=$class['id']?>', 800);">
                    <span class="fa fa-edit mr5"></span>
                    Wijzigen
                </a>
                <a href="#" class="btn highlight white" onclick="SchoolClass.delete(<?=$class['id']?>, 0);">
                    <span class="fa fa-remove mr5"></span>
                    Verwijderen
                </a>
                <?php } ?>
            </div>
        </td>
    </tr>
    <?
}
?>