<?php
foreach ($menus as $id => $title) {
    ?>
    <div class="item" id="<?= $id ?>"><?= $title ?>
        <span class="counter"></span>
    </div>
    <?php
}

if (AuthComponent::user('roles')->id == 1 || AuthComponent::user('is_temp_teacher')) {
    ?>
    <div class="" >
        <div class="btn btn-primary" style="color:#FFFFFF;background-color:#3DBB56;" onClick="Popup.load('/users/tell_a_teacher');">Nodig een collega uit! <i class='fa fa-chevron-right' style='background:#3DBB56;color:#FFFFFF;font-weight:bold;'></i></div>  
    </div>
    <?php
}
?>
<br clear="all" />