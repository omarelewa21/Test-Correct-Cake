<style>
    #chromebook-menu-notice-container {
        background-color: var(--error-bg);
        border: 1px solid var(--error-border);
        color: var(--error-text);
        padding: 20px 30px;
        border-radius: 10px;
        margin-bottom: 16px;
    }
</style>
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
        <div id="chromebook-menu-notice-container" style="display:none;text-align:left">
            <div class="notification error">
                <div class="body">
                    <div id="chromebook-menu-notice-container-inapp" style="display:none">
                        <p><strong>Let op!</strong> je kunt de toets pas starten als je in full screen modus zit. Druk op de full screen knop (<img src="/img/chromebook-menu-icon.png" title="Deze knop kun je vinden boven de 5" style="border:none;vertical-align:middle;max-height:13px"/>) , sluit de Test-Correct app af en start de app opnieuw op om de toets te maken.</p>
                    </div>
                    <div id="chromebook-menu-notice-container-notinapp" style="display:none">
                        <p><strong>Let op!</strong> je kunt de toets pas starten als je in full screen modus zit. Druk op de full screen knop (<img src="/img/chromebook-menu-icon.png" title="Deze knop kun je vinden boven de 5" style="border:none;vertical-align:middle;max-height:13px"/>) om verder te gaan.</p>
                    </div>
                </div>
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