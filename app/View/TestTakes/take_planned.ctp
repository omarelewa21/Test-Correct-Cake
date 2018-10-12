<div class="block" style="width:500px; margin: 0px auto; margin-top:100px;">
    <div class="block-head"><?=$take['test']['name']?></div>
    <div class="block-content">
        <p>
            <?=nl2br($take['test']['introduction'])?>
        </p>

        <div id="waiting">

            <center>
                <img src="/img/ico/loading-large.gif" />
            </center>
            <div class="alert alert-info" style="text-align: center; margin:20px 0px 0px 0px;">
                Deze toets is nog niet gestart, wacht op de surveillant..
            </div>
        </div>
        <center>
            <a href="#" class="btn highlight large" style="display: none;" id="btnStartTest" onclick="TestTake.startTest(<?=$take['id']?>);">
                Toets starten
            </a>
        </center>
    </div>
</div>

<script>
    TestTake.startHeartBeat('planned');
</script>