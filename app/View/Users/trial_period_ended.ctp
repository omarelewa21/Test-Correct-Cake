<style>
    @media screen and (max-width: 650px) {
        #prevent_logout_div {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
    }
</style>
<div id="prevent_logout_div" class="tat-content border-radius-bottom-0"
     style="padding-bottom: 0!important;padding-top: 2rem!important;">
    <div style="display:flex;align-items: center">
        <div style="flex-grow:1">
            <h2 style="margin:0">
                <?php if ($hasMultipleLocations) { ?>
                    <span class=""><?= AuthComponent::user('school_location.name') ?>:</span>
                <?php } ?>
                <?= __("Proefperiode afgelopen") ?>
            </h2>
        </div>
        <?php if ($closeable) { ?>
            <div class="close" style="flex-shrink: 1">
                <a href="#" onclick="Popup.closeLast();">
                    <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg">
                        <g stroke="currentColor" fill-rule="evenodd" stroke-linecap="round" stroke-width="3">
                            <path d="M1.5 12.5l11-11M12.5 12.5l-11-11"/>
                        </g>
                    </svg>
                </a>
            </div>
        <?php } ?>
    </div>
    <div class="divider mb16 mt16"></div>
    <div class="body1">
        <div>
            <p style="font-size:1.125rem;line-height: 1.33">
                <?php
                $text = $hasMultipleLocations ? __("Proefperiode tekst multi") : __("Proefperiode tekst single");
                echo sprintf($text, AuthComponent::user('school_location.name'));
                ?>
            </p>
        </div>
    </div>
</div>
<div class="popup-footer tat-footer pt16" style="padding-bottom: 2rem!important;">
    <div style="display: flex;
                align-items:center;
                width: 100%;
                gap: .5rem;
                justify-content: end;"
    >
        <a type="button"
           href="<?= $trialInfoURL ?>"
           target="_blank"
           class="button flex button-md <?= $hasMultipleLocations ? 'secondary-button' : 'primary-button' ?>"
           style="font-size: 18px; align-items: center;"
        >
            <span style=""><?= __("Boek demo") ?></span>
        </a>
        <a type="button"
           href="<?= $trialInfoURL ?>"
           target="_blank"
           class="button flex button-md <?= $hasMultipleLocations ? 'primary-button' : 'cta-button' ?>"
           style="font-size: 18px; align-items: center;"
        >
            <span style=""><?= __("Verleng licentie") ?></span>
        </a>
        <?php if ($hasMultipleLocations) { ?>
        <button type="button"
                class="button flex cta-button button-md"
                style="font-size: 18px; align-items: center;"
                onclick="Popup.load('users/switch_school_location_popup', 576)"
        >
            <span style=""><?= __("Wissel van locatie") ?></span>
        </button>
        <?php } ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#prevent_logout_div').parent().css({
            'border-radius': '10px',
            // 'height' : '90vh',
            // 'max-height' : '820px',
            'overflow': 'auto'
        })
    })
</script>