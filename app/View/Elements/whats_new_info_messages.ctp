<?php foreach($infos as $info) {?>
    <div class="whats-new info" data-code="<?= $info['uuid'] ?>">
        <div class="title">
            <h4 style=""><?= $info['title']?></h4>
        </div>

        <div class="body mb20">
            <?= $info['content']?>
        </div>
    </div>
<?php } ?>

<style>
    .dashboard .notification.info .title {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .dashboard .notification.info .fa-times {
        color: darkblue;
        cursor: pointer;
    }
    .dashboard .notification.info .fa-times:hover {
        color: blue;
    }

</style>