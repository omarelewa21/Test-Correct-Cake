<div class="block" style="width:500px; margin: 0px auto; margin-top:100px;">
    <div class="block-head"><?= __("Toets is afgerond")?></div>
    <div class="block-content">
        <div class="alert alert-info" style="text-align: center;">
        <?= __("Deze toets is afgerond en kan niet meer worden geopend.")?>
        </div>
    </div>
</div>

<script>
    clearInterval(TestTake.heartBeatInterval);
</script>