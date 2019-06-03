<?
foreach($tiles as $id => $data) {
    ?>
    <div class="tile tile-<?=$data['icon']?>" id="<?=$id?>" menu="<?=$data['menu']?>" path="<?=$data['path']?>">
        <?=$data['title']?>
    </div>
<?
}
?>

<br clear="all" />