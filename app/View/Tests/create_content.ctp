<div class="dashboard create-content">
    <div style="display: flex; justify-content: space-between; padding: 0 8px;align-items: center;">
        <h1>Hoe wil je een toets creëren?</h1>
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
                <h4>Toets Construeren</h4>
                <span class="subtitle">Ga zelf aan de slag met het maken van een toets</span>
                <span class="body">Stel jouw toets in en zet jouw toets op met vraaggroepen en vragen</span>

                <button type="button"
                        onclick="Popup.closeWithNewPopup('/tests/add?content_creation_step=2', 1000);"
                        class="button cta-button button-md">
                    <span>Toets Construeren</span>
                </button>
            </div>
            <div class="cta-block">
                <div class="svg">
                    <?php echo $this->element('sticker_upload_test'); ?>
                </div>
                <h4>Toets uploaden</h4>
                <span class="subtitle">Laat een bestaande toets digitaliseren</span>
                <span class="body">Gelieve aan te leveren als: <br> PDF, Word, Wintoets</span>

                <button type="button"
                        onclick="Popup.closeWithNewPopup('/file_management/upload_test?content_creation_step=2',800);"
                        class="button cta-button button-md">
                    <span>Toets uploaden</span>
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