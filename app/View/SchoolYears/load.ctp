<?
foreach($school_years as $school_year) {
    ?>
    <tr>
        <td><?=$school_year['year']?></td>
        <td>
            <?
            foreach($school_year['school_locations'] as $location) {
                echo $location['name'].'<br />';
            }
            ?>
        </td>
        <td class="nopadding">
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="SchoolYear_<?=getUUID($school_year, 'get');?>">
                <span class="fa fa-ellipsis-v"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="Navigation.load('/school_years/view/<?=getUUID($school_year, 'get');?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="SchoolYear_<?=getUUID($school_year, 'get');?>">
                <a href="#" class="btn highlight white" onclick="Popup.load('/school_years/edit/<?=getUUID($school_year, 'get');?>', 400);">
                    <span class="fa fa-edit mr5"></span>
                    <?= __("Wijzigen")?>
                </a>
                <a href="#" class="btn highlight white" onclick="SchoolYear.delete(<?=getUUID($school_year, 'getQuoted');?>);">
                    <span class="fa fa-remove mr5"></span>
                    <?= __("Verwijderen")?>
                </a>
            </div>
        </td>
    </tr>
    <?
}
?>