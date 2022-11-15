<div class="dashboard create-content">
    <div style="display: flex; justify-content: space-between; padding: 0 8px;align-items: center;">
        <h1><?= __("Hoe wil je een toets creëren?")?></h1>
        <span class="close" style="cursor:pointer" onclick="Popup.closeLast()">
            <?= $this->element('close') ?>
        </span>
    </div>
    <div class="divider"></div>
    <div class="cta-blocks" style=" padding: 0 8px;">
        <div class="block-container">
            <div class="cta-block">
                <div class="svg">
                    <?php echo $this->element('sticker_create_test'); ?>
                </div>
                <h4><?= __("Toets Construeren")?></h4>
                <span class="subtitle"><?= __("Ga zelf aan de slag met het maken van een toets")?></span>
                <span class="body"><?= __("Stel jouw toets in en zet jouw toets op met vraaggroepen en vragen")?></span>

                <button type="button"
                    <?php if(AuthComponent::user('school_location.allow_new_test_bank') == 1) { ?>
                        onclick="User.goToLaravel('/teacher/tests?referrerAction=create_test');"
                        <?php } else { ?>
                        onclick="Popup.closeWithNewPopup('/tests/add?content_creation_step=2', 1000);"
                        <?php } ?>
                        class="button cta-button button-md">
                    <span><?= __("Toets Construeren")?></span>
                </button>
            </div>
            <div class="cta-block">
                <div class="svg">
                    <?php echo $this->element('sticker_upload_test'); ?>
                </div>
                <h4><?= __("Toets uploaden")?></h4>
                <span class="subtitle"><?= __("Laat een bestaande toets digitaliseren")?></span>
                <span class="body"><?= __("Gelieve aan te leveren als:")?> <br> <?= __("PDF, Word, Wintoets")?></span>

                <button type="button"
                        onclick="User.goToLaravel('teacher/upload_test');"
                        class="button cta-button button-md">
                    <span><?= __("Toets uploaden")?></span>
                </button>
            </div>
        </div>
    </div>
    <div class="indicator">
        <svg style="margin-right: 5px" height="14px" width="14px" xmlns="http://www.w3.org/2000/svg">
            <circle class="primary" cx="7" cy="7" r="7"/>
        </svg>
        <svg height="14px" width="14px" xmlns="http://www.w3.org/2000/svg">
            <circle class="system-secondary" cx="7" cy="7" r="7"/>
        </svg>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.dashboard.create-content').parent().css({'border-radius': '10px'})
    })
</script>