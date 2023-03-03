
<style>
    @media screen and (max-width: 650px) {
        #prevent_logout_div {
            padding-left: 1rem!important;
            padding-right: 1rem!important;
        }
    }
    .box {
        display: flex;
        height: 100%;
        flex-direction: column;
        -ms-flex-direction: column;
    }
    .popup{
        overflow: hidden;
    }
    .box .popup-new-feature.text {
        height: 6rem;
    }

    .box .popup-new-feature.content {
        flex: 1;
        overflow-y: scroll;
        background-color: #ffffff;
        border-radius: 8px;
        border: solid 1px #f0f2f5;
        padding: 11px 16px 9px;
        box-sizing: border-box
    }
    .box .popup-new-feature.footer {
        height: 4rem;
    }
</style>
<div id="prevent_logout_div" class="tat-content border-radius-bottom-0" style="height:76vh;padding-bottom: 0!important;padding-top: 1.6rem!important;">
    <div style="display:flex;align-items: center">
        <div style="flex-grow:1; ">
            <img style="display: inline-block; margin-bottom: -5px; height:2rem;" src="img/ico/updates-en-onderhoud S.svg" alt="">
            <h2 style="display: inline-block; margin:0">
                <?= __("dashboard.features popup title")?>
            </h2>
        </div>

        <div class="close" style="flex-shrink: 1">
            <a href="#" onclick="
            Popup.closeLast();
            User.postponeAutoUserLogout();
            User.seenNewFeatures([
                <?php
                    if($infos && count($infos)){
                        foreach ($infos as $info){
                            ?>

                           '<?= $info['uuid']; ?>',
                    <?php
                        }
                    }
                ?>
            ]);">
                <?= $this->element('close') ?>
            </a>
        </div>
    </div>

    <div class="divider mt16"></div>
    <div class="popup-new-feature box">
        <div class="popup-new-feature text">
            <p>
                <?= __("dashboard.features popup text")?>
            </p>
        </div>
        <div class="popup-new-feature content"
        >
                <?php if($infos && count($infos)){
                    array_multisort(array_column($infos, 'show_from'), SORT_DESC, $infos);
                    echo $this->element('whats_new_info_messages');
                } ?>
        </div>
        <div class="popup-new-feature footer">

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#prevent_logout_div').parent().css({
            'border-radius': '10px',
            'height' : '90vh',
            'max-height' : '820px',
            'overflow' : 'hidden'
        })
    })

</script>