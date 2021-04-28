<?
foreach($users as $user) {
    ?>
    <tr>
        <td><?=$user['name_first']?></td>
        <td><?=$user['name_suffix']?></td>
        <td><?=$user['name']?></td>
        <td><?=$user['username']?></td>
        <td><?=$user['teacher_external_id']?></td>

        <td class="nopadding">
            <?php if ($user['active']){ ?>
                <a href="#" class="btn blue pull-right dropblock-left" id="<?=getUUID($user, 'get');?>" onClick="User.existingTeacherAction('<?= getUUID($user, 'get')?>')">
                <span class="fa fa-trash"></span>
            </a>
            <?php } else { ?>
            <a href="#" class="btn white pull-right dropblock-left" id="<?=getUUID($user, 'get');?>" onClick="User.existingTeacherAction('<?= getUUID($user, 'get')?>')">
                <span class="fa fa-link"></span>
            </a>
            <?php } ?>


        </td>
    </tr>
    <?
}
?>
