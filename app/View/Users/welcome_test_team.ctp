<div class="dashboard" style="margin-top: 20px!important">
    <h1><?=__('Welkom in Test-Correct')?></h1>

    <?php if(count($infos)){ ?>
            <div class="notes">
                <?= $this->element('welcome_info_messages'); ?>
            </div>
    <?php } ?>

    <h3 class="ml10"><?=__('Kies een andere omgeving')?></h3>
    <div style="display: flex; justify-content: center; width: 100%">
        <div style="display: flex; flex-direction: column; justify-content: center; width: 100%">
            <a class="button primary-button button-md" style="display: flex; justify-content: center; align-items: center; text-decoration: none; margin: 0 10px;" href="https://portal.test-correct.nl/">
                <span>Live portal</span>
            </a>
        </div>
        <div style="display: flex; flex-direction: column; justify-content: center; width: 100%">
            <a class="button cta-button button-md" style="display: flex; justify-content: center; align-items: center; text-decoration: none; margin: 0 10px;" href="https://testportal.test-correct.nl/">
                <span>Test portal</span>
            </a>
        </div>
        <div style="display: flex; flex-direction: column; justify-content: center; width: 100%">
            <a class="button secondary-button button-md" style="display: flex; justify-content: center; align-items: center; text-decoration: none; margin: 0 10px;" href="https://devportal.test-correct.nl">
                <span>Dev portal</span>
            </a>
        </div>
        <div style="display: flex; flex-direction: column; justify-content: center; width: 100%">
            <a class="button secondary-button button-md" style="display: flex; justify-content: center; align-items: center; text-decoration: none; margin: 0 10px;" href="http://test-correct.test/">
                <span>Sobit dev</span>
            </a>
        </div>
    </div>
</div>