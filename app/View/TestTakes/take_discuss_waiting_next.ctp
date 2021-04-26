<div class="block" style="width:500px; margin: 0px auto; margin-top:100px;">
    <div class="block-head"><?= __("Moment geduld..")?></div>
    <div class="block-content">
        <div id="waiting">
            <center>
                <img src="/img/ico/loading-large.gif" />
            </center>
            <div class="alert alert-info" style="text-align: center; margin:20px 0px 0px 0px;">
            <?= __("Wacht tot de docent de volgende vraag toont.")?>
            </div>
        </div>
    </div>
</div>

<script>
    TestTake.startHeartBeat('waiting_next');

    setTimeout(function() {
        Loading.discard = true;
        Navigation.refresh();
    }, 5000);
</script>