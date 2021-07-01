<div class="dashboard create-content">
    <div>
        <h1>Content creëren voor Test-Correct</h1>
    </div>
    <div style="display: flex; justify-content: center;width: 100%; text-align: center; margin-top: 2rem;">
        <h5 style="display: flex">Wil je een toets construeren of uploaden?</h5>
    </div>
    <div class="cta-blocks">
        <div class="block-container">
            <div class="cta-block">
                <div class="svg">
                    <?php echo $this->element('sticker_create_test'); ?>
                </div>
                <h4>Toets Construeren</h4>
                <span class="subtitle">Ga zelf aan de slag met het maken van een toets</span>
                <span class="body">Stel jouw toets in en zet jouw toets op met vraaggroepen en vragen</span>

                <button type="button"
                        onclick="Popup.load('/tests/add', 1000);"
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
                        onclick="Popup.load('/file_management/upload_test',800);"
                        class="button cta-button button-md">
                    <span>Toets uploaden</span>
                </button>
            </div>
        </div>
    </div>
</div>

