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
            <a href="#" class="btn white pull-right dropblock-left" id="test_<?=$user['id']?>" onClick="Notify.notify('Je kunt een demo gebruiker niet verwijderen','error');">
                <span class="fa fa-list-ul"></span>
            </a>
                    <?php
                    } else {
                    ?>
            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="test_<?=$user['id']?>">
                <span class="fa fa-list-ul"></span>
            </a>
                    <?php
                    }
                    ?>
            <a href="#" onclick="Navigation.load('/users/view/<?=$user['id']?>');"  class="btn white pull-right">
                <span class="fa fa-folder-open-o"></span>
            </a>

            <div class="dropblock blur-close" for="test_<?=$user['id']?>">
                <?php
                    if((bool) $user['demo'] !== true){
                    ?>
                <a href="#" class="btn highlight white" onclick="Popup.load('/users/edit/<?=$user['id']?>', 400);">
                    <span class="fa fa-edit mr5"></span>
                    Wijzigen
                </a>
                <a href="#" class="btn highlight white" onclick="User.delete(<?=$user['id']?>);">
                    <span class="fa fa-remove mr5"></span>
                    Verwijderen
                </a>
                    <?php
                    }
                ?>
            </div>
        </td>
    </tr>
    <?
}
?>