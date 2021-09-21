<?
foreach($users as $user) {
    ?>
    <tr>
        <td style="padding:2px;" width="50">
            <img src="/users/profile_picture/<?=getUUID($user, 'get');?>" width="45" height="45" style="border-radius: 50px" />
        </td>
        <td><?=$user['name_first']?></td>
        <td><?=$user['name_suffix']?></td>
        <td><?=$user['name']?></td>
        <td>
            <?
            if(isset($user['school_location']['name']) && !empty($user['school_location']['name'])) {
                echo $user['school_location']['name'];
            }else{
                echo '-';
            }
            ?>
        </td>
        <td>
            <?
            $classes = [];

            foreach($user['student_school_classes'] as $class) {
                $classes[] = $class['name'];
            }

            echo implode(', ', $classes);
            ?>
        </td>
        <td class="nopadding">
            <?php if((bool) $user['demo'] === true){?>
            <a href="#" class="btn white pull-right dropblock-left" id="test_<?=getUUID($user, 'get');?>" onClick="Notify.notify('Je kunt een demo gebruiker niet verwijderen','error');">
                <span class="fa fa-list-ul"></span>
            </a>
            <?php } else {?>
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_<?=getUUID($user, 'get');?>">
                <span class="fa fa-list-ul"></span>
            </a>
            <?php } ?>
            <? if($role != 'Administrator') { ?>
            <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/users/view/<?=getUUID($user, 'get');?>')">
                <span class="fa fa-folder-open-o"></span>
            </a>
            <? } ?>

            <div class="dropblock blur-close" for="test_<?=getUUID($user, 'get');?>">
                <? if($role != 'Administrator') { ?>
                    <a href="#" class="btn highlight white" onclick="Popup.load('/users/edit/<?=getUUID($user, 'get');?>', 400);">
                        <span class="fa fa-edit mr5"></span>
                        Wijzigen
                    </a>
                    <a href="#" class="btn highlight white" onclick="User.delete('<?=getUUID($user, 'get');?>');">
                        <span class="fa fa-remove mr5"></span>
                        Verwijderen
                    </a>
                <? }else { ?>
                    <a href="#" class="btn highlight white" onclick="Popup.load('/users/move/<?=getUUID($user, 'get');?>', 400);">
                        <span class="fa fa-edit mr5"></span>
                        Overplaatsen
                    </a>
                <? } ?>
                <?php if (isset($role) && $role == 'Support') { ?>
                    <a href="#" class="btn highlight white" onclick="Popup.load('/users/take_over_user_confirmation/<?=getUUID($user, 'get');?>', 600);">
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