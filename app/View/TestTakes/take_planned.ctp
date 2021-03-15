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
        <div id="chromebook-menu-notice-container" style="display:block;text-align:left">
            <p>We kunnen de toets alleen starten wanneer de browser op volledig scherm staat.</p>
            <p>
                In Chromebooks kan het zijn dat de menu balk hieronder nog zichtbaar is, in dat geval kun je deze uit zetten door op het icoontje boven de 5 te klikken<br />
                <img src="/img/chromebook-menu-btn.png" title="Deze button"/>
            </p>
            <div id="chromebook-menu-notice-container-inapp" style="display:block">
                <p>Je dient na de aanpassing de app af te sluiten en opnieuw op te starten.</p>
            </div>
        </div>
        <?php if($oldPlayerAccess) { ?>
        <a href="#" class="btn highlight large" style="display: none;" id="btnStartTest" onclick="TestTake.startTest('<?=getUUID($take, 'get');?>');">
            Toets starten
        </a>
        <?php
        }
        if($newPlayerAccess) {
            ?>
        <a href="#" class="btn highlight large" style="display: none;" id="btnStartTestInLaravel" onclick="TestTake.startTestInLaravel('<?=getUUID($take, 'get');?>');">
            <?= !$oldPlayerAccess? 'Toets starten' : 'Start in nieuwe speler' ?>
        </a>
        <?php } ?>
        </center>
    </div>
</div>

<script>
    TestTake.startHeartBeat('planned');
</script>