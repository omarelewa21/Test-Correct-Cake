<?
foreach($schools as $school) {

    if($school['count_active_licenses'] > 0) {
        $percentage = round((100 / $school['count_active_licenses']) * $school['count_students']);
    }else{
        $percentage = '0';
    }
    ?>
    <tr>
        <td><?=$school['customer_code']?></td>
        <td><?=$school['name']?></td>
        <td>
            <?
            if(empty($school['umbrella_organization']['name'])) {
                echo '<div class="label" style="background:green">Geen, gemeenschap is eindklant</div>';
            }else{
                echo $school['umbrella_organization']['name'];
            }
            ?>
        </td>
        <td><?=$school['main_city']?></td>
        <td><?=$school['count_active_licenses']?></td>
        <td><?=$school['count_students'] . ' (' . $percentage . '%)'?></td>
        <td><?=$school['count_questions']?></td>
        <td class="nopadding">
            <? if($isAdministrator): ?>
                <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="school_<?=$school['id']?>">
                    <span class="fa fa-list-ul"></span>
                </a>
            <? endif;  ?>
            <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/schools/view/<?=$school['id']?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>
            <? if($isAdministrator): ?>
                <div class="dropblock blur-close" for="school_<?=$school['id']?>">
                    <a href="#" class="btn highlight white" onclick="Popup.load('/schools/edit/<?=$school['id']?>', 800);">
                        <span class="fa fa-edit mr5"></span>
                        Wijzigen
                    </a>
                    <a href="#" class="btn highlight white" onclick="School.delete(<?=$school['id']?>);">
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