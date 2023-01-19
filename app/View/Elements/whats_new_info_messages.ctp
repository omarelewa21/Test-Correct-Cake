<?php foreach($infos as $info) {?>
    <div class="whats-new info" data-code="<?= $info['uuid'] ?>">
        <div class="title">
            <h5 style=""><?= $info['title']?></h5>
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
<script>
    if(typeof notificationRemovalHasRun === "undefined") {

        $('body').on('click','.whats-new.info .fa-times',function(){
            let elem = $(this).parents('.notification');
            $.post(
                "/infos/removeDashboardInfo/" + $(elem).data('code'),
                function (data, status) {
                    data = JSON.parse(data)
                    if (data.status) {
                        $(elem).hide(1000);
                    } else {
                        Notify.notify($.i18n('Bericht kan niet worden verwijderd'), 'error');
                    }
                });
        });
        var notificationRemovalHasRun = true;
    }
</script>