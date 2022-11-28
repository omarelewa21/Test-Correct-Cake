<?php foreach($infos as $info) {?>
    <div class="notification info" data-code="<?= $info['uuid'] ?>">
        <div class="title">
            <h5 style=""><?= $info['title']?></h5>
            <?php if(AuthComponent::user('roles')[0]['id'] != 3){ ?>
                <i class="fa fa-times" aria-hidden="true"></i>
            <?php } ?>
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

        $('body').on('click','.dashboard .notification.info .fa-times',function(){
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