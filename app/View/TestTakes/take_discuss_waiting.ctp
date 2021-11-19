<div class="block" style="width:500px; margin: 0px auto; margin-top:100px;">
    <div class="block-head"><?=$take['test']['name']?></div>
    <div class="block-content">
        <div id="waiting">
            <center>
                <img src="/img/ico/loading-large.gif" />
            </center>
            <div class="alert alert-info" style="text-align: center; margin:20px 0px 0px 0px;">
            <?= __("CO-Learning is nog niet gestart, wacht op de docent..")?>
            </div>
        </div>
    </div>
</div>

<script>
    TestTake.startHeartBeat('discussing');
</script>