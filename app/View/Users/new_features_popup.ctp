

<style>
    @media screen and (max-width: 650px) {
        #prevent_logout_div {
            padding-left: 1rem!important;
            padding-right: 1rem!important;
        }
    }
</style>
<div id="prevent_logout_div" class="tat-content border-radius-bottom-0" style="padding-bottom: 0!important;padding-top: 2rem!important;">
    <div style="display:flex;align-items: center">
        <div style="flex-grow:1">
            <h2 style="margin:0">
            <?= __("Nieuwe functionaliteiten")?>
            </h2>
        </div>
        <div class="close" style="flex-shrink: 1">
            <a href="#" onclick="Popup.closeLast(); User.postponeAutoUserLogout()">
                <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg">
                    <g stroke="currentColor" fill-rule="evenodd" stroke-linecap="round" stroke-width="3">
                        <path d="M1.5 12.5l11-11M12.5 12.5l-11-11"/>
                    </g>
                </svg>
            </a>
        </div>
    </div>

    <div class="divider mb16 mt16"></div>
    <div class="body1" style="overflow-x: ">
        <div>
            <p><?= __("Test-Correct wordt constant doorontwikkeld.")?></p>
            <p><?= __("Lees hier over onze nieuwste functionaliteiten.")?></p>
        </div>
        <div style="border-radius: 10px; border:solid 1px var(--blue-grey);max-height: 100%; overflow-x: scroll;box-sizing: border-box">
            <?php if($infos && count($infos)){
                echo $this->element('whats_new_info_messages');
            } ?>
        </div>
    </div>
</div>
<div class="popup-footer tat-footer pt16" style="padding-bottom: 2rem!important;">
    <div style="display: flex;align-items:center;width: 100%">
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