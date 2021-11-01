<?
foreach($sections as $section) {
    //  if There is no subjects
    if(empty($section['subjects'])){
        ?>
        <tr>
            <td><?=$section['name']?></td>
            <td>
            <?
                $counter = 1;
                foreach($section['school_locations'] as $location) {
                    echo $location['name'] . '<br />';
                    $counter++;
                }
                foreach($section['shared_school_locations'] as $shared_location){
                    if($counter < 4){
                        echo $shared_location['name'] . '<br />';
                        $counter++;
                    }
                    else{
                        echo "..." . '<br />';
                    }
                }
            ?>
            </td>
            <td> ---- </td>
            <td> ---- </td>
            <td class="nopadding">
                <?php if((bool) $section['demo'] !== true){?>
                <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="section_<?=getUUID($section, 'get');?>">
                    <span class="fa fa-list-ul"></span>
                </a>
                <?php } else { ?>
                <a href="#" class="btn white pull-right dropblock-left" id="section_<?=getUUID($section, 'get');?>" onclick="Notify.notify('<?= __('De demo sectie kan niet gewijzigd of verwijderd worden')?>','error');">
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

    else{
        // if there are subjects
        foreach($section['subjects'] as $index => $subject){
    ?>
        <tr>
            <td><?=$section['name']?></td>
            <td>
                <?
                $counter = 1;
                foreach($section['school_locations'] as $location) {
                    echo $location['name'] . '<br />';
                    $counter++;
                }
                
                foreach($section['shared_school_locations'] as $shared_location){
                    if($counter < 4){
                        echo $shared_location['name'] . '<br />';
                        $counter++;
                    }
                    else{
                        echo "..." . '<br />';
                    }
                }
                ?>
            </td>
            <td>
                <?
                    echo $subject['name'] . '<br />';
                ?>
            </td>
            <td>
                <?
                    echo $subject['base_subject']['name'] . '<br />';
                ?>
            </td>

            <td class="nopadding">
                <?php if((bool) $section['demo'] !== true){?>
                <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="section_<?=getUUID($section, 'get');?>">
                    <span class="fa fa-list-ul"></span>
                </a>
                <?php } else { ?>
                <a href="#" class="btn white pull-right dropblock-left" id="section_<?=getUUID($section, 'get');?>" onclick="Notify.notify('<?= __('De demo sectie kan niet gewijzigd of verwijderd worden')?>','error');">
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
    }
}
?>