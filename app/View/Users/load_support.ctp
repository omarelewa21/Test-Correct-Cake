<?
foreach($users as $user) {
    ?>
    <tr>
        <td><?=$user['name_first']?></td>
        <td><?=$user['name_suffix']?></td>
        <td><?=$user['name']?></td>

        <td class="nopadding">
            <?php
                    if((bool) $user['demo'] === true){
                    ?>
            <a href="#" class="btn white pull-right dropblock-left" id="test_<?=getUUID($user, 'get');?>" onClick="Notify.notify('Je kunt een demo gebruiker niet verwijderen','error');">
                <span class="fa fa-list-ul"></span>
            </a>
                    <?php
                    } else {
                    ?>
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_<?=getUUID($user, 'get');?>">
                <span class="fa fa-list-ul"></span>
            </a>
                    <?php
                    }
                    ?>
            <a href="#" onclick="Navigation.load('/users/view/<?=getUUID($user, 'get');?>');"  class="btn white pull-right">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="test_<?=getUUID($user, 'get');?>">
                <?php
                    if((bool) $user['demo'] !== true){
                    ?>
                <a href="#" class="btn highlight white" onclick="Popup.load('/users/edit/<?=getUUID($user, 'get');?>', 400);">
                    <span class="fa fa-edit mr5"></span>
                    Wijzigen
                </a>
                <a href="#" class="btn highlight white" onclick="User.delete('<?=getUUID($user, 'get');?>');">
                    <span class="fa fa-remove mr5"></span>
                    Verwijderen
                </a>
                    <?php
                    }
                ?>
                <?php if (isset($role) && $role == 'Support') { ?>
                    <a href="#" class="btn highlight white" onclick="Popup.load('/support/take_over_user_confirmation/<?=getUUID($user, 'get');?>', 600);">
                        <span class="fa fa-user-secret mr5"></span>
                        Log in als deze gebruiker
                    </a>
                <?php }?>
            </div>
        </td>
    </tr>
    <?
}
?>