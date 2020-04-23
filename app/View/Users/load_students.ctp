<?
foreach($users as $user) {
    ?>
    <tr>
        <td style="padding:2px;" width="50">
            <img src="/users/profile_picture/<?=$user['id']?>" width="45" height="45" style="border-radius: 50px" />
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
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_<?=$user['id']?>">
                <span class="fa fa-list-ul"></span>
            </a>
            <? if($role != 'Administrator') { ?>
            <a href="#" class="btn white pull-right dropblock-left" onclick="Navigation.load('/users/view/<?=$user['id']?>')">
                <span class="fa fa-folder-open-o"></span>
            </a>
            <? } ?>

            <div class="dropblock blur-close" for="test_<?=$user['id']?>">
                <? if($role != 'Administrator') { ?>
                    <a href="#" class="btn highlight white" onclick="Popup.load('/users/edit/<?=$user['id']?>', 400);">
                        <span class="fa fa-edit mr5"></span>
                        Wijzigen
                    </a>
                    <a href="#" class="btn highlight white" onclick="User.delete(<?=$user['id']?>);">
                        <span class="fa fa-remove mr5"></span>
                        Verwijderen
                    </a>
                <? }else { ?>
                    <a href="#" class="btn highlight white" onclick="Popup.load('/users/move/<?=$user['id']?>', 400);">
                        <span class="fa fa-edit mr5"></span>
                        Overplaatsen
                    </a>
                <? } ?>
            </div>
        </td>
    </tr>
    <?
}
?>