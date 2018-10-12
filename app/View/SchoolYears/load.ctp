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
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="SchoolYear_<?=$school_year['id']?>">
                <span class="fa fa-list-ul"></span>
            </a>
            <a href="#" class="btn white pull-right" onclick="Navigation.load('/school_years/view/<?=$school_year['id']?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="SchoolYear_<?=$school_year['id']?>">
                <a href="#" class="btn highlight white" onclick="Popup.load('/school_years/edit/<?=$school_year['id']?>', 400);">
                    <span class="fa fa-edit mr5"></span>
                    Wijzigen
                </a>
                <a href="#" class="btn highlight white" onclick="SchoolYear.delete(<?=$school_year['id']?>);">
                    <span class="fa fa-remove mr5"></span>
                    Delete
                </a>
            </div>
        </td>
    </tr>
    <?
}
?>