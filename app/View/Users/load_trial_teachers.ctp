<?
foreach ($users as $user) {
    ?>
    <tr>
        <td><?= $user['name_first'] ?></td>
        <td><?= $user['name_suffix'] ?></td>
        <td><?= $user['name'] ?></td>
        <td><?= $user['username'] ?></td>
        <td>kaas</td>
        <td class="nopadding">
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left"
               id="trial_teacher_<?= getUUID($user, 'get'); ?>"
               onclick="Popup.load('/users/change_trial_date/<?= getUUID($user, 'get'); ?>', 600);">
                <span class="fa fa-calendar"></span>
            </a>
        </td>
    </tr>
    <?
}
?>