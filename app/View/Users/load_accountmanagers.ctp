<?
foreach($users as $user) {
    ?>
    <tr>
        <td><?=$user['sales_organization']['name']?></td>
        <td><?=$user['name_first']?></td>
        <td><?=$user['name_suffix']?></td>
        <td><?=$user['name']?></td>
        <td><?=$user['count_accounts']?></td>
        <td><?=$user['count_licenses']?></td>
        <td><?=$user['count_students']?></td>
        <td class="nopadding">
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_<?=$user['id']?>">
                <span class="fa fa-list-ul"></span>
            </a>
            <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/users/view/<?=$user['id']?>')">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="test_<?=$user['id']?>">
                <a href="#" class="btn highlight white" onclick="Popup.load('/messages/send/<?=$user['id']?>', 500);">
                    <span class="fa fa-edit mr5"></span>
                    Bericht sturen
                </a>
                <a href="#" class="btn highlight white" onclick="Popup.load('/users/edit/<?=$user['id']?>', 400);">
                    <span class="fa fa-edit mr5"></span>
                    Wijzigen
                </a>
                <a href="#" class="btn highlight white" onclick="User.delete(<?=$user['id']?>);">
                    <span class="fa fa-remove mr5"></span>
                    Delete
                </a>
            </div>
        </td>
    </tr>
    <?
}
?>