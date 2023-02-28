<style>
    @media screen and (max-width: 650px) {
        #switch_school_popup {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
    }

    #school-location-list-options {
        display: flex;
        width: 100%;
        flex-direction: column;
        gap: .5rem;
    }

    #school-location-list-options .location-option {
        --sllo-background-color: white;
        --sllo-border-color: var(--blue-grey);
        --sllo-color: var(--system-base);
        display: flex;
        width: 100%;
        border-radius: 10px;
        background: var(--sllo-background-color);
        border-style: solid;
        border-color: var(--sllo-border-color);
        border-width: 2px;
        box-sizing: border-box;
        font-weight: 700;
        padding: 1.25rem;
        color: var(--sllo-color);
        transition-property: color, background-color, border-color;
        transition-timing-function: ease-in-out;
        transition-duration: 150ms;
        justify-content: space-between;
        align-items: center;
        line-height: 1.33;
        cursor:pointer;
    }

    #school-location-list-options .location-option:hover {
        --sllo-background-color: var(--off-white);
        --sllo-border-color: var(--primary);
        --sllo-color: var(--primary);
    }

    #school-location-list-options .location-option > svg {
        display: none;
    }

    #school-location-list-options .location-option[data-active="true"] > svg {
        display: block;
    }

    #school-location-list-options .location-option[data-active="true"] {
        --sllo-background-color: var(--off-white);
        --sllo-border-color: var(--primary);
        --sllo-color: var(--primary);
    }

</style>
<div id="switch_school_popup" class="tat-content border-radius-bottom-0"
     style="padding-bottom: 1rem!important;padding-top: 1.5rem!important;border-top-left-radius: 10px;border-top-right-radius">
    <div style="display:flex;align-items: center">
        <div style="flex-grow:1">
            <h2 style="margin:0">
                <?= __("Kies een locatie") ?>
            </h2>
        </div>
        <div class="close" style="flex-shrink: 1">
            <a href="#" onclick="Popup.closeLast();">
                <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg">
                    <g stroke="currentColor" fill-rule="evenodd" stroke-linecap="round" stroke-width="3">
                        <path d="M1.5 12.5l11-11M12.5 12.5l-11-11"/>
                    </g>
                </svg>
            </a>
        </div>
    </div>
    <div class="divider mb16 mt16"></div>
    <div class="body1">
        <p style="font-size:1.125rem;line-height: 1.33">
            <?= __("Kies een locatie om naar te wisselen.") ?>
        </p>
        <div id="school-location-list-options">
        </div>
    </div>
</div>
<div class="popup-footer tat-footer pt16"
     style="padding-bottom: 1.5rem!important;border-bottom-left-radius: 10px;border-bottom-right-radius">
    <div style="display: flex;
                align-items:center;
                width: 100%;
                gap: .5rem;
                justify-content: end;">
        <button type="button"
                class="button flex text-button button-md"
                style="font-size: 18px; align-items: center;"
                onclick="Popup.closeLast()"
        >
            <?= __('Annuleer') ?>
        </button>
        <button type="button"
                id="switch-school-confirm-button"
                class="button flex cta-button button-md"
                style="font-size: 18px; align-items: center;"
        >
            <span style=""><?= __("Wissel") ?></span>
        </button>

    </div>
</div>
<template id="location-option-template">
    <div class="location-option">
        <span class="location-option-title"></span>
        <?= $this->element('checkmark'); ?>
    </div>
</template>
<script>
    $(document).ready(function () {
        $('#switch_school_popup').parent().css({
            'border-radius': '10px',
            'overflow': 'auto'
        })

        User.info.school_location_list.forEach(location => {
            let template = document.getElementById('location-option-template').content.firstElementChild.cloneNode(true)
            template.querySelector('.location-option-title').innerHTML = location.name;
            template.dataset.uuid = location.uuid;
            template.dataset.active = location.active;

            template.addEventListener('click', (event) => {
                Array.from($('#school-location-list-options').children()).forEach(child => {
                    child.dataset.active = 'false';
                })

                const currentEl = event.target.classList.contains('location-option')
                    ? event.target
                    : event.target.closest('.location-option');
                currentEl.dataset.active = 'true'
            });
            $('#school-location-list-options').append(template);
        })
        $('#switch-school-confirm-button').on('click', (event) => {
            const uuid = $('.location-option[data-active="true"]')[0].dataset.uuid;
            if (!uuid) {
                debugger;
            }

            User.switchLocation('', uuid);
        })
    })
</script>