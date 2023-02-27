<?php
foreach ($menus as $id => $title) {
    $onClick = '';
    if(is_array($title) && array_key_exists('onClick',$title)){
        $onClick = "onClick='".$title['onClick']."'";
        $title = $title['title'];
    }
    $selid = '';
    if(is_array($menus[$id]) && array_key_exists('selid',$menus[$id])){
        $selid = "selid='".$menus[$id]['selid']."'";
    }
    ?>

    <div class="item" id="<?= $id ?>" <?= $onClick ?> <?= $selid ?> >
        <span class="item-title"><?= $title ?></span>
        <span class="counter"></span>
    </div>
    <?php
}
?>