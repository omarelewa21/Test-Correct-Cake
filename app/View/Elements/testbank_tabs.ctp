<div class="mb20" style="background-color: white;box-shadow: 0px 1px 3px rgb(0 0 0 / 20%);width:100%;">
    <div class="flex justifyContentCenter">
        <div class="flex justifyContentCenter" style="flex-direction: column; flex-grow: 1">
            <button class="flex button button-md text-button justifyContentCenter alignItemsCenter"
                    style="<?= $tab === 'schoollocation' ? 'color:var(--primary);' : '' ?>"
                    onclick="Navigation.load('/tests/index')"
            >
                Schoollocatie
            </button>
            <span style="display: flex;<?= $tab === 'schoollocation' ? 'border-bottom: 2px solid var(--primary)' : '' ?>">
        </div>
        <div class="flex justifyContentCenter" style="flex-direction: column; flex-grow: 1">
            <button class="flex button button-md text-button justifyContentCenter alignItemsCenter"
                    onclick="<?= AuthComponent::user('hasSharedSections') ? 'Navigation.load(\'/shared_sections_tests/index\')' : '' ?>"
                    style="<?= $tab === 'shared_sections' ? 'color:var(--primary);' : '' ?>"
                <?= !AuthComponent::user('hasSharedSections') ? 'disabled' : '' ?>
            >
                Scholengemeenschap
            </button>
            <span style="display:flex;<?= $tab === 'shared_sections' ? 'border-bottom: 2px solid var(--primary)' : '' ?>">
        </div>
        <div class="flex justifyContentCenter" style="flex-direction: column; flex-grow: 1">
            <button class="flex button button-md text-button justifyContentCenter alignItemsCenter"
                    onclick="<?= AuthComponent::user('hasCitoToetsen') ? 'Navigation.load(\'/cito_tests/index\')' : '' ?>"
                    style="<?= $tab === 'cito' ? 'color:var(--primary);' : '' ?>"
                <?= !AuthComponent::user('hasCitoToetsen') ? 'disabled' : '' ?>
            >
                CITO toetsen
            </button>
            <span style="display:flex;<?= $tab === 'cito' ? 'border-bottom: 2px solid var(--primary)' : '' ?>">
        </div>
    </div>
</div>