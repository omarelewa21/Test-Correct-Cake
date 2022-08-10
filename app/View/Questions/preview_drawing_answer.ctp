<? if (isset($backgroundImage) && $backgroundImage) { ?>
    <img src="<?= $backgroundImage ?>" class="position-absolute w-500" style="height: 320px !important"/>
    <img src="<?= $image ?>" class="position-relative w-500" style="height: 320px !important"/>
<? }else{ ?>
    <img src="<?= $image ?>"/>
<? } ?>


<style>
    .position-absolute {
        position: absolute;
    }
    .position-relative {
        position: relative;
    }
    .w-500 {
        width: 500px;
    }
</style>
