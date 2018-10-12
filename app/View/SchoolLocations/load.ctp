<?
foreach($school_locations as $school_location) {

    if($school_location['count_active_licenses'] > 0) {
        $percentage = round((100 / $school_location['count_active_licenses']) * $school_location['count_students']);
    }else{
        $percentage = '0';
    }

    ?>
    <tr>
        <td><?=$school_location['customer_code']?></td>
        <td><?=$school_location['name']?></td>
        <td>
            <?
            if(empty($school_location['school']['name'])) {
                echo '<div class="label" style="background:green">Geen, locatie is eindklant</div>';
            }else{
                echo $school_location['school']['name'];
            }
            ?>
        </td>
        <td><?=$school_location['main_city']?></td>
        <td><?=$school_location['count_active_licenses']?></td>
        <td><?=$school_location['count_students'] . ' (' . $percentage . '%)'?></td>
        <td><?=$school_location['count_questions']?></td>
        <td class="nopadding">
            <? if($isAdministrator): ?>
                <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="school_location_<?=$school_location['id']?>">
                    <span class="fa fa-list-ul"></span>
                </a>
            <? endif;?>
            <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/school_locations/view/<?=$school_location['id']?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>
            <? if($isAdministrator): ?>
                <div class="dropblock blur-close" for="school_location_<?=$school_location['id']?>">
                    <a href="#" class="btn highlight white" onclick="Popup.load('/school_locations/edit/<?=$school_location['id']?>', 1100);">
                        <span class="fa fa-edit mr5"></span>
                        Wijzigen
                    </a>
                    <a href="#" class="btn highlight white" onclick="SchoolLocation.delete(<?=$school_location['id']?>, 0);">
                        <span class="fa fa-remove mr5"></span>
                        Delete
                    </a>
                </div>
            <? endif;?>
        </td>
    </tr>
    <?
}
?>