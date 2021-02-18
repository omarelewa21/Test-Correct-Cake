<?php
foreach ($menus as $id => $title) {
    ?>
    <div class="item" id="<?= $id ?>"><?= $title ?>
        <span class="counter"></span>
    </div>
    <?php
}
?>