<?
foreach($sections as $section) {
    ?>
    <tr>
        <td><?=$section['name']?></td>
        <td>
            <?
            foreach($section['school_locations'] as $location) {
                echo $location['name'].'<br />';
            }
            ?>
        </td>
        <td class="nopadding">
            <?php if((bool) $section['demo'] !== true){?>
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="section_<?=getUUID($section, 'get');?>">
                <span class="fa fa-list-ul"></span>
            </a>
            <?php } else { ?>
            <a href="#" class="btn white pull-right dropblock-left" id="section_<?=getUUID($section, 'get');?>" onclick="Notify.notify('De demo sectie kan niet gewijzigd of verwijderd worden','error');">
                <span class="fa fa-list-ul"></span>
            </a>
            <?php } ?>
            <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/sections/view/<?=getUUID($section, 'get');?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="section_<?=getUUID($section, 'get');?>">
                <a href="#" class="btn highlight white" onclick="Popup.load('/sections/edit/<?=getUUID($section, 'get');?>', 400);">
                    <span class="fa fa-edit mr5"></span>
                    <?= __("Wijzigen")?>
                </a>
                <a href="#" class="btn highlight white" onclick="Section.delete(<?=getUUID($section, 'getQuoted');?>);">
                    <span class="fa fa-remove mr5"></span>
                    <?= __("Verwijderen")?>
                </a>
            </div>
        </td>
    </tr>
    <?
}
?>