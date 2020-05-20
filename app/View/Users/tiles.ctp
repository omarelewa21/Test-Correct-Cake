<?
foreach($tiles as $id => $data) {
    ?>
    <div class="tile tile-<?=$data['icon']?>" id="<?=$id?>" menu="<?=$data['menu']?>" path="<?=$data['path']?>" type="<?php echo (isset($data['type']) ? $data['type'] : '') ?>">
        <?=$data['title']?>
    </div>
<?
}
?>

<br clear="all" />