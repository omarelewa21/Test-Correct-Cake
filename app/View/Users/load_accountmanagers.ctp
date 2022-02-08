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
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_<?=getUUID($user, 'get');?>">
                <span class="fa fa-ellipsis-v"></span>
            </a>
            <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/users/view/<?=getUUID($user, 'get');?>')">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="test_<?=getUUID($user, 'get');?>">
                <a href="#" class="btn highlight white" onclick="Popup.load('/messages/send/<?=getUUID($user, 'get');?>', 500);">
                    <span class="fa fa-edit mr5"></span>
                    <?= __("Bericht sturen")?>
                </a>
                <a href="#" class="btn highlight white" onclick="Popup.load('/users/edit/<?=getUUID($user, 'get');?>', 400);">
                    <span class="fa fa-edit mr5"></span>
                    <?= __("Wijzigen")?>
                </a>
                <a href="#" class="btn highlight white" onclick="User.delete(<?=getUUID($user, 'getQuoted');?>);">
                    <span class="fa fa-remove mr5"></span>
                    <?= __("Verwijderen")?>
                </a>
            </div>
        </td>
    </tr>
    <?
}
?>