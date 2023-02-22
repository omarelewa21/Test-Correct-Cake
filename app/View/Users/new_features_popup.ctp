
<style>
    @media screen and (max-width: 650px) {
        #prevent_logout_div {
            padding-left: 1rem!important;
            padding-right: 1rem!important;
        }
    }
    .box {
        display: flex;
        flex-flow: column;
        height: 100%;
    }
    .box .popup-new-feature.text {
        flex: 0 1 auto;
    }
    .box .popup-new-feature.content {
        max-height: 38rem;
        padding: 11px 16px 9px;
        border-radius: 8px;
        border: solid 1px #f0f2f5;
        background-color: #ffffff;
        flex: 1 1 auto;
        overflow-y: scroll;
        box-sizing: border-box
    }
    .box .popup-new-feature.footer {
        flex: 0 1 40px;
    }
</style>
<div id="prevent_logout_div" class="tat-content border-radius-bottom-0" style="padding-bottom: 0!important;padding-top: 1.6rem!important;">
    <div style="display:flex;align-items: center">
        <div style="flex-grow:1; ">
            <img style="display: inline-block; margin-bottom: -5px; height:2rem;" src="img/ico/updates-en-onderhoud S.svg" alt="">
            <h2 style="display: inline-block; margin:0">
                <?= __("dashboard.features popup title")?>
            </h2>
        </div>

        <div class="close" style="flex-shrink: 1">
            <a href="#" onclick="Popup.closeLast(); User.postponeAutoUserLogout()">
                <?= $this->element('close') ?>
            </a>
        </div>
    </div>

    <div class="divider mt16"></div>
    <div class="popup-new-feature box" style="overflow: clip;  overflow-clip-margin: 4rem;">
        <div class="popup-new-feature text">
            <p>
                <?= __("dashboard.features popup text")?>
            </p>
        </div>
        <div class="popup-new-feature content"

        >
            <?php if($infos && count($infos)){
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
            'overflow' : 'auto'
        })
    })

</script>