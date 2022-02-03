<div style="border-bottom:solid 1px var(--mid-grey);flex-grow: 1">
    <div class="flex">
        <div class="flex justifyContentCenter" style="flex-direction: column; position:relative; top: 1px;">
            <button class="flex button button-md text-button justifyContentCenter alignItemsCenter"
                    style="<?= $tab === 'schoollocation' ? 'color:var(--primary);' : '' ?>"
                    onclick="Navigation.load('/tests/index')"
            >
            <?= __("Schoollocatie")?>
            </button>
            <span style="display: flex;<?= $tab === 'schoollocation' ? 'border-bottom: 3px solid var(--primary)' : '' ?>">
        </div>
        <?php if(AuthComponent::user('hasSharedSections')) { ?>
        <div class="flex justifyContentCenter" style="flex-direction: column; position:relative; top: 1px;">
            <button class="flex button button-md text-button justifyContentCenter alignItemsCenter"
                    onclick="Navigation.load('/shared_sections_tests/index')"
                    style="<?= $tab === 'shared_sections' ? 'color:var(--primary);' : '' ?>"
            >
            <?= __("Scholengemeenschap")?>
            </button>
            <span style="display:flex;<?= $tab === 'shared_sections' ? 'border-bottom: 3px solid var(--primary)' : '' ?>">
        </div>
        <?php } ?>
        <div class="flex justifyContentCenter" style="flex-direction: column; position:relative; top: 1px;">
            <button class="flex button button-md text-button justifyContentCenter alignItemsCenter"
                    onclick="Navigation.load('/examen_tests/index')"
                    style="<?= $tab === 'exam' ? 'color:var(--primary);' : '' ?>"
            >
                <?= __("Examenmateriaal")?>
            </button>
            <span style="display:flex;<?= $tab === 'shared_sections' ? 'border-bottom: 3px solid var(--primary)' : '' ?>">
        </div>
        <div class="flex justifyContentCenter" style="flex-direction: column; position:relative; top: 1px;">
            <button class="flex button button-md text-button justifyContentCenter alignItemsCenter"
                    onclick="<?= AuthComponent::user('hasCitoToetsen') ? 'Navigation.load(\'/cito_tests/index\')' : '' ?>"
                    style="<?= $tab === 'cito' ? 'color:var(--primary);' : '' ?>"
                <?php
                 if(!AuthComponent::user('hasCitoToetsen') ){
                    echo "disabled title='". __("Binnenkort beschikbaar") ."'";
                 } else {
                     echo  '';
                 }
                 ?>
            >
            <?= __(" CITO Snelle Starttoets")?>
            </button>
            <span style="display:flex;<?= $tab === 'cito' ? 'border-bottom: 3px solid var(--primary)' : '' ?>">
        </div>
    </div>
</div>
