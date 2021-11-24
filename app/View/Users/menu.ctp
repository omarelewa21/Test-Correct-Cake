<?php
foreach ($menus as $id => $title) {
    $onClick = '';
    if(is_array($title) && array_key_exists('onClick',$title)){
        $onClick = "onClick='".$title['onClick']."'";
        $title = $title['title'];
    }
    ?>
    <div class="item" id="<?= $id ?>" <?= $onClick ?> >
        <span class="item-title"><?= $title ?></span>
        <span class="counter"></span>
    </div>
    <?php
}
?>