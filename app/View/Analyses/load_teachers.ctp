<?
foreach($users as $user) {
    ?>
    <tr>
        <td><?=$user['name_first']?></td>
        <td><?=$user['name']?><?= !empty($user['name_suffix']) ? ', ' . $user['name_suffix'] : '' ?></td>
        <td><?=$user['abbreviation']?></td>
        <td class="nopadding" width="30">
            <a href="#" class="btn white pull-right" onclick="Navigation.load('/analyses/teacher/<?=getUUID($user, 'get');?>');">
                <span class="fa fa-folder-open-o"></span>
            </a>
        </td>
    </tr>
    <?
}
?>