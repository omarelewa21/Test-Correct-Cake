<div class="notification info">
    <?php foreach($infos as $info) {?>
    <div class="title">
        <h5 style=""><?= $info['title']?></h5>
    </div>
    <div class="body mb20">
        <?= $info['content']?>
    </div>
    <?php } ?>
</div>