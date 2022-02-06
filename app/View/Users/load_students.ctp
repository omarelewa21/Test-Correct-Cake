<?
foreach($users as $user) {
    ?>
    <tr>
        <?php if ($role != 'Support') { ?>
        <td style="padding:2px;" width="50">
            <img src="/users/profile_picture/<?=getUUID($user, 'get');?>" width="45" height="45" style="border-radius: 50px" />
        </td>
        <?php } ?>
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
        <?php if ($role != 'Support') { ?>
        <td>
            <?
            $classes = [];

            foreach($user['student_school_classes'] as $class) {
                $classes[] = $class['name'];
            }

            echo implode(', ', $classes);
            ?>
        </td>
        <?php } ?>

        <td class="nopadding">
        <? if($role == 'Support') { ?>
            <a href="#" class="btn highlight white"
               onclick="Popup.load('/support/take_over_user_confirmation/<?= getUUID($user, 'get'); ?>', 600);"
               title="<?= __("Log in als deze gebruiker"); ?>"
               style="display:inline-flex"
            >
                <span class="fa fa-user-secret"></span>
            </a>
            <?php } else { ?>
                <?php if((bool) $user['demo'] === true){ ?>
                    <a href="#" class="btn white pull-right dropblock-left" id="test_<?=getUUID($user, 'get');?>" onClick="Notify.notify('Je kunt een demo gebruiker niet verwijderen','error');">
                        <span class="fa fa-ellipsis-v"></span>
                    </a>
                <?php } else {?>
                    <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_<?=getUUID($user, 'get');?>">
                        <span class="fa fa-ellipsis-v"></span>
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
                                <?= __("Wijzigen")?>
                            </a>
                            <a href="#" class="btn highlight white" onclick="User.delete('<?=getUUID($user, 'get');?>');">
                                <span class="fa fa-remove mr5"></span>
                                <?= __("Verwijderen")?>
                            </a>
                        <? }else { ?>
                            <a href="#" class="btn highlight white" onclick="Popup.load('/users/move/<?=getUUID($user, 'get');?>', 400);">
                                <span class="fa fa-edit mr5"></span>
                                <?= __("Overplaatsen")?>
                            </a>
                        <? } ?>
                    </div>
            <?php }?>
        </td>
    </tr>
    <?
}
?>