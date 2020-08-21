<?
foreach($users as $user) {
    ?>
    <tr>
        <td><?=$user['name_first']?></td>
        <td><?=$user['name_suffix']?></td>
        <td><?=$user['name']?></td>
        <td><?=$user['username']?></td>
        <td class="nopadding">
            <?php
                    if((bool) $user['demo'] === true){
                    ?>
            <a href="#" class="btn white pull-right dropblock-left" id="test_<?=getUUID($user, 'get');?>" onClick="Notify.notify('Je kunt een demo gebruiker niet verplaatsen','error');">
                <span class="fa fa-arrows-h"></span>
            </a>

                    <?php
                    } else {
                    ?>
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_<?=getUUID($user, 'get');?>" onclick="Popup.load('/users/switch_school_location/<?=getUUID($user, 'get');?>', 600);">
                <span class="fa fa-arrows-h"></span>
            </a>

                        <?php if($user['is_temp_teacher']){?>
                            <a href="#" onclick="Popup.load('/users/edit_register_new_teacher/<?=getUUID($user, 'get');?>', 800);"  class="btn white pull-right">
                                <span class="fa fa-folder-open-o"></span>
                            </a>
                        <?php } else {?>
                            <a href="#" onClick="Notify.notify('Je kunt deze gebruiker niet wijzigen','error');"  class="btn white pull-right">
                                <span class="fa fa-folder-open-o"></span>
                            </a>
                        <?php } ?>

                    <?php
                    }
                    ?>


        </td>
    </tr>
    <?
}
?>