<?
foreach($tiles as $id => $data) {
    ?>
    <div class="tile" id="<?=$id?>" menu="<?=$data['menu']?>" path="<?=$data['path']?>">
        <img src="/img/header/tile-<?=$data['icon']?>.png" /> <br />
        <?=$data['title']?>
    </div>
<?
}
?>

<br clear="all" />