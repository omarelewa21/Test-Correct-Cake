<div style="border-bottom:solid 1px var(--mid-grey);flex-grow: 1">
    <div class="flex">
        <div class="flex justifyContentCenter" style="flex-direction: column; position:relative; top: 1px;">
            <button class="flex button button-md text-button justifyContentCenter alignItemsCenter"
                    style="<?= $tab === 'schoollocation' ? 'color:var(--primary);' : '' ?>"
                    onclick="Navigation.load('/tests/index')"
            >
                Schoollocatie
            </button>
            <span style="display: flex;<?= $tab === 'schoollocation' ? 'border-bottom: 3px solid var(--primary)' : '' ?>">
        </div>
        <?php if(AuthComponent::user('hasSharedSections')) { ?>
        <div class="flex justifyContentCenter" style="flex-direction: column; position:relative; top: 1px;">
            <button class="flex button button-md text-button justifyContentCenter alignItemsCenter"
                    onclick="Navigation.load('/shared_sections_tests/index')"
                    style="<?= $tab === 'shared_sections' ? 'color:var(--primary);' : '' ?>"
            >
                Scholengemeenschap
            </button>
            <span style="display:flex;<?= $tab === 'shared_sections' ? 'border-bottom: 3px solid var(--primary)' : '' ?>">
        </div>
        <?php } ?>
        <div class="flex justifyContentCenter" style="flex-direction: column; position:relative; top: 1px;">
            <button class="flex button button-md text-button justifyContentCenter alignItemsCenter"
                    onclick="<?= AuthComponent::user('hasCitoToetsen') ? 'Navigation.load(\'/cito_tests/index\')' : '' ?>"
                    style="<?= $tab === 'cito' ? 'color:var(--primary);' : '' ?>"
                <?= !AuthComponent::user('hasCitoToetsen') ? 'disabled title="Binnenkort beschikbaar"' : '' ?>
            >
                CITO Snelle Starttoets
            </button>
            <span style="display:flex;<?= $tab === 'cito' ? 'border-bottom: 3px solid var(--primary)' : '' ?>">
        </div>
    </div>
</div>