<?
foreach($organisations as $organisation) {

    if($organisation['count_active_licenses'] > 0) {
        $percentage = round((100 / $organisation['count_active_licenses']) * $organisation['count_students']);
    }else{
        $percentage = '0';
    }

    ?>
    <tr>
        <td><?=$organisation['customer_code']?></td>
        <td><?=$organisation['name']?></td>
        <td><div class="label" style="background:green">Dit is de eindklant</div></td>
        <td><?=$organisation['count_active_licenses']?></td>
        <td><?=$organisation['count_students'] . ' (' . $percentage . '%)'?></td>
        <td><?=$organisation['count_questions']?></td>
        <td class="nopadding">
            <? if($isAdministrator): ?>
                <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="organisation_<?=$organisation['id']?>">
                    <span class="fa fa-list-ul"></span>
                </a>
            <? endif; ?>
            <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/umbrella_organisations/view/<?=$organisation['id']?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>
            <? if($isAdministrator): ?>
                <div class="dropblock blur-close" for="organisation_<?=$organisation['id']?>">
                    <a href="#" class="btn highlight white" onclick="Popup.load('/umbrella_organisations/edit/<?=$organisation['id']?>', 800);">
                        <span class="fa fa-edit mr5"></span>
                        Wijzigen
                    </a>
                    <a href="#" class="btn highlight white" onclick="Organisation.delete(<?=$organisation['id']?>);">
                        <span class="fa fa-remove mr5"></span>
                        Delete
                    </a>
                </div>
            <? endif; ?>
        </td>
    </tr>
    <?
}
?>