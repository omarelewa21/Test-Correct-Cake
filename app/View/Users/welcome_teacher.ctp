<?php
if ($wizard_steps) {
    ?>
    <div style="position: relative; width: 100%">
        <div id="buttons" style="position:absolute; right: 0;">
            <a id="toggle-ob-wizard" href="#0" class="btn white">
            <span>
                <i class="fa fa-minus mr5"></i>
                Verberg demo tour
            </span>
                <span id="ob-wizard-finished-icon"><?= $progress == 100 ? ' <i id="wizard-completed" class="text-success fa fa-check"></i>' : '' ?></span>
            </a>
        </div>
    </div>
<?php } ?>

<div class="dashboard">
    <div class="notes">
        <?php

        if (null === AuthComponent::user('account_verified')) {
            ?>
            <div class="notification error">
                <div class="title">
                    <?php echo $this->element('warning', array('color' => 'var(--error-text)')) ?><h5
                            style="margin-left: 20px;">Let op! Je account is nog niet geverifieerd.</h5>
                </div>
                <div class="body">
                    <p>Je account is nog niet geverifieerd in ons systeem. Heb je geen mail ontvangen?</p>
                    <a class="text-button" onclick="resendEmailVerificationMail()">
                        Stuur verificatiemail opnieuw<?php echo $this->element('arrow') ?>
                    </a>
                </div>
            </div>
            <?php
        } else if (AuthComponent::user('is_temp_teacher')) {
            ?>
            <div class="notification warning">
                <div class="title">
                    <?php echo $this->element('warning', array('color' => 'var(--teacher-Highlight-dark)')) ?><h5
                            style="margin-left: 30px;">Let op: je account is nog niet geactiveerd.</h5>
                </div>
                <div class="body">
                    <p>Vanuit het oogpunt van informatiebeveiliging voeren wij een dubbelcheck uit op de door jouw
                        opgegeven
                        data, voordat je klassen en toetsen kunt uploaden en kunt samenwerken met je collega’s in
                        Test-Correct. Dat kan één tot twee werkdagen in beslag nemen. Wil je niet zo lang wachten? Bel
                        ons
                        dan op <a href="tel:010 7 171 171">010 7 171 171</a> om dit te versnellen.</p>
                </div>
            </div>
        <?php } ?>
        <div class="notification info">
            <div class="title">
                <h5>Welkom op het Test-Correct platform! Onze november update staat nu online.</h5>
            </div>
            <div class="body">
                <p>
                    Creëer filters en sla deze op zodat je altijd snel de juiste toetsen bij de hand hebt in de
                    itembank. Archiveer toetsen die je al hebt afgenomen en becijferd. Creëer analyses van je
                    toetsesultaten en zie precies hoeveel grip je leerlingen hebben op elk leerdoel.
                </p>
            </div>
        </div>
    </div>

    <div class="cta">
        <div class="tat-top-text">
            <?php echo $this->element('send_big_blue'); ?>
            <h1 class="inline-block">Nodig een collega uit!</h1>
            <div class="ml60">
                <h6 class="">Samen met je collega's kun je:</h6>
                <div class="flex">
                    <div class="mr10">
                        <?php echo $this->element('checkmark', array('color' => 'var(--system-base)')) ?>
                    </div>
                    <div class="flex-grow">
                        <span class="body1">Overleggen over de voortgang van jouw studenten en ervaringen delen.</span>
                    </div>
                </div>
                <div class="flex">
                    <div class="mr10">
                        <?php echo $this->element('checkmark', array('color' => 'var(--system-base)')) ?>
                    </div>
                    <div>
                        <span class="body1">Gebruikmaken van elkaars toetsen en toetsvragen.</span>
                    </div>
                </div>
                <div style="margin-top: 8px">
                    <button type="button"
                            onClick="Popup.load('/users/tell_a_teacher', 800);"
                            class="inline-block button cta-button button-md"
                    >
                        <span style="margin-right: 10px; display: inline">Nodig een collega uit</span><?php echo $this->element('chevron', array('color' => 'white')) ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if ($wizard_steps) {
?>
<div>

    <div id="ob-wizard">
        &nbsp; <!-- nbsp spacer for div  i_i -->

        <div class="block">
            <div class="block-head m56" style="padding-top:25px;">
                <?php
                $name = AuthComponent::user('name_first');
                if (strlen(AuthComponent::user('name_first')) == 1
                    || (strlen(AuthComponent::user('name_first')) == 2 && AuthComponent::user('name_first'){1} === '.')) {
                    $name = sprintf('%s %s %s', AuthComponent::user('name_first'), AuthComponent::user('name_suffix'), AuthComponent::user('name'));
                }
                ?>

                <div id="welcome-text" style="height:70px; text-align: left;">
                        <span class="pull-left" style="line-height: 30px;">
                             <?php if ($progress == 0) { ?>
                                 <strong>Welkom  <?= $name ?>,</strong>
                                 <BR>we willen je graag snel op weg helpen binnen Test-Correct!
                             <?php } else if ($progress == 100) { ?>
                                 <strong>Welkom  <?= $name ?>,</strong>
                                 <BR> gefeliciteerd je hebt de demo tour afgerond!
                             <?php } else { ?>
                                 <strong>Welkom terug <?= $name ?>.</strong> <BR>Je bent goed bezig!
                             <?php } ?>
                        </span>
                    <span class="pull-right">
                        <img src="img/dolly.png" style="height:60px !important;"></span>


                </div>

                <div style="height:25px;"><span class="pull-left">Voortgang...</span> <span id="progress-percentage"
                                                                                            class="pull-right"><?= $progress ?>%</span>
                </div>
                <div class="progress">
                    <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                         aria-valuemax="100" style="width: <?= $progress ?>%;">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
            <?php
            foreach ($wizard_steps['steps'] as $stepKey => $step) {
                ?>
                <div class="block-head" id="ob-wizard-<?= $stepKey ?>">
                    <div style="text-align: left; cursor:pointer; padding-left:6px">
                        <?php
                        if ($step['done'] == 1) {
                            ?>
                            <i class="fa fa-check text-success"></i>
                            <?php
                        } else {
                            ?>
                            <i class="fa" style="width:20px"></i>
                            <?php
                        }
                        ?>
                        <span <?= $step['done'] ? 'style="text-decoration: line-through;"' : '' ?>>
                    <?= $step['title'] ?>
                            </span>

                        <a href="#" class="ob-wizard-toggle-sub pull-left btn white fa fa-plus"
                           data-step="<?= $stepKey ?>"></a>
                    </div>
                </div>
                <div class="block-content">
                    <ul>
                        <?php foreach ($step['sub'] as $subKey => $sub) {
                            switch ($sub['action']) {
                                case 'button_done':
                                    ?>
                                    <li><a href="#" data-id="<?= $sub['id'] ?>"
                                           class="prr-button btn white btn-done"
                                           data-confetti-max-count="<?= $sub['confetti_max_count'] ?>"
                                           data-confetti-time-out="<?= $sub['confetti_time_out'] ?>"
                                           data-action="<?= $sub['action_content'] ?>"
                                           data-step="<?= $step['id'] ?>">Klik hier als je alle stappen hebt gedaan</a>
                                    </li>
                                    <?php
                                    break;
                                case 'tour':
                                    ?>
                                    <li>
                                        <?php if ($sub['done'] == 1) { ?>
                                            <i class="text-success fa fa-check-circle-o"></i>
                                            <?php
                                        } else { ?>
                                            <i class="fa fa-circle-o"></i>
                                            <?php
                                        } ?>
                                        <a href="<?= $sub['action_content'] ?>"
                                           class="ob-wizard-step-link"
                                           data-type="tour"
                                           data-id="<?= $sub['id'] ?>"
                                        ><?= $sub['title'] ?>
                                        </a>
                                        <?php if (array_key_exists('knowledge_base_action', $sub) && $sub['knowledge_base_action']) { ?>
                                            <a
                                                    href="<?= $sub['knowledge_base_action'] ?>"
                                                    target=_blank
                                                    data-id="<?= $sub['id'] ?>"
                                                    class="prr-button knowledgebase-button btn white inline-block grey pull-right ob-wizard-step-link">
                                                <span
                                                        class="fa fa-external-link"></span> Lees in kennisbank
                                            </a>
                                        <?php } else { ?>
                                            <span class="prr-button knowledgebase-button pull-right"></span>
                                        <?php } ?>
                                        <a
                                                onclick="HelpHero.startTour('<?= /* TCP-358 */
                                                trim(end(explode("/", $sub['action_content']))); ?>')"
                                                data-id="<?= $sub['id'] ?>"
                                                class="prr-button btn white inline-block grey pull-right ob-wizard-step-link"
                                        ><span class="fa fa-male"></span> Doe de tour</a>

                                    </li>
                                    <?php
                                    break;
                                case 'video':
                                    ?>
                                    <li>
                                        <?php if ($sub['done'] == 1) { ?>
                                            <i class="text-success fa fa-check-circle-o"></i>
                                            <?php
                                        } else { ?>
                                            <i class="fa fa-circle-o"></i>
                                            <?php
                                        } ?>
                                        <?php if (array_key_exists('knowledge_base_action', $sub) && $sub['knowledge_base_action']) { ?>
                                            <a
                                                    href="<?= $sub['knowledge_base_action'] ?>"
                                                    target=_blank
                                                    data-id="<?= $sub['id'] ?>"
                                                    class="prr-button knowledgebase-button btn white inline-block grey pull-right ob-wizard-step-link">
                                                <span
                                                        class="fa fa-external-link"></span> Lees in kennisbank
                                            </a>
                                        <?php } else { ?>
                                            <span class="prr-button knowledgebase-button pull-right"></span>
                                        <?php } ?>
                                        <a href="#0" class="ob-wizard-step-link"
                                           onclick="Popup.load('/video/popup/?url=<?= rawurlencode($sub['action_content']) ?>', '630')"
                                           data-type="video"
                                           data-id="<?= $sub['id'] ?>"
                                        >
                                            <?= $sub['title'] ?>
                                        </a>
                                        <a href="#0"
                                           onclick="Popup.load('/video/popup/?url=<?= rawurlencode($sub['action_content']) ?>', '630')"
                                           class="prr-button btn white inline-block grey pull-right ob-wizard-step-link"
                                           data-id="<?= $sub['id'] ?>"
                                        ><span class="fa fa-film"></span> Bekijk de video
                                        </a>
                                    </li>
                                    <?php
                                    break;
                            }
                        }
                        ?>
                    </ul>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
    }
    ?>
</div>


<h1 style="text-align:center; margin-top: 50px;">Meteen naar:</h1>
<div style="text-align:center;">
    <div style="display:inline-block;">
        <?php
        if (AuthComponent::user('isToetsenbakker') == true) {
            ?>
            <span class="blue">
            <div class="tile btn pull-left defaultMenuButton plus"
                 onclick="Navigation.load('file_management/testuploads');">
            Te verwerken toetsen
            </div></span>
        <?php } else { ?>

            <span class="blue">
            <div class="tile btn pull-left defaultMenuButton plus" onclick="Popup.load('/tests/add', 1000);">
            Toets construeren
            </div></span>

            <span class="blue"><div class="tile tile-surveilleren"
                                    onclick="Navigation.load('/test_takes/surveillance');">
            Surveilleren
            </div></span>

            <span class="blue"><div class="tile tile-nakijken" onclick="Navigation.load('/test_takes/to_rate');">
            Nakijken
            </div></span>
        <?php } ?>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/gh/mathusummut/confetti.js/confetti.min.js"></script>
<script>
    if (typeof hubspotLoaded == 'undefined') {
        var _hsq = window._hsq = window._hsq || [];
        _hsq.push(["identify", "<?=AuthComponent::user('username')?>"]);
        _hsq.push(['trackPageView']);

        try {
            window.HubSpotConversations.widget.load();
        } catch (error) {

        }

        hubspotLoaded = true;
    }

    HelpHero.identify("<?=AuthComponent::user('uuid')?>", {
        name: "<?=AuthComponent::user('name')?>",
        name_first: "<?=AuthComponent::user('name_first')?>",
        name_suffic: "<?=AuthComponent::user('name_suffix')?>"
    });

    if (jQuery("#supportLinkUserMenu").length != 1) {
        jQuery("#user_menu").append('<a id="supportLinkUserMenu" href="https://support.test-correct.nl" target="_blank" class="btn white mt5" > Supportpagina</a>');
        <?php
        if(AuthComponent::user('isToetsenbakker') == true){
        ?>
        jQuery("#user_menu").append('<a href="#" onClick="Navigation.load(\'file_management/testuploads\');" class="btn white mt5" > Te verwerken toetsen</a>');
        <?php
        }else {
        ?>
        jQuery("#user_menu").append('<a href="#" onClick="Navigation.load(\'file_management/testuploads\');" class="btn white mt5" >Uploaden toets</a>');
        <?php
        }
        ?>
    }

    var activeStep = "<?= $wizard_steps['active_step'] ?>";
    var showOnboardWizard = "<?= $wizard_steps['show'] ?>";

    if (typeof onboarding_wizard_scripting_loaded == 'undefined') {
        $(document).ready(function () {
            $("body")
                .on('click', '#ob-wizard .block-head', function () {
                    $(this).find('a:first').trigger('click');
                })
                .on("click", ".ob-wizard-toggle-sub", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleWizardSub($(e.target))
                })
                .on("click", ".ob-wizard-step-link", function (e) {
                    if ($(e.target).data('type') === 'video') {
                        e.preventDefault();
                    }
                    consumeStepLink($(e.target))
                })
                .on('click', '.btn-done', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();

                    if ($(e.target).hasClass('active-button')) {
                        closeStep($(e.target));
                        openNextStepOrCloseWizard($(e.target))
                    } else if ($(e.target).hasClass('not-all-previous-steps-completed')) {
                        Popup.messageWithPreventDefault({
                            'title': 'Waarschuwing',
                            'message': 'U kunt deze stap pas afronden als de voorgaande stap is afgerond',
                            'btnOk': 'Ok'
                        }, function () {
                            $.Event().preventDefault();
                        });
                    } else {
                        if ($(e.target).attr('disabled') !== 'disabled') {
                            Popup.messageWithPreventDefault({
                                    'title': 'Waarschuwing',
                                    'message': 'Doorloop eerst alle sub stappen',
                                    'btnOk': 'Ok'
                                }, function () {
                                    $.Event().preventDefault();
                                }
                            );
                        }
                    }
                    return false
                })
                .on('click', '#toggle-ob-wizard', function (e) {
                    saveShowState(toggleWizardVisibilityState())
                })
        })

        function saveShowState(show) {
            $.ajax({
                url: '/users/onboarding_wizard',
                type: 'PUT',
                data: {'show': show}
            });
        }

        function toggleWizardVisibilityState() {
            var el = $('i:first', $('#toggle-ob-wizard'));
            var show = true;

            var completed = $("wizard-completed").length;

            if (el.hasClass('fa-minus')) {
                el.parent().html('<i class="fa fa-plus mr5"></i> Toon demo tour');
                $('#ob-wizard').hide();
                show = false;
            } else {
                el.parent().html('<i class="fa fa-minus mr5"></i> Verberg demo tour');
                $('#ob-wizard').show();
            }

            if (completed !== 0) {
                markWizardCompleted();
            }


            return show;
        }

        function toggleWizardSub(el) {
            hideAllSubSteps()
            if (el.hasClass('fa-minus')) {
                el.addClass('fa-plus').removeClass('fa-minus');
            } else {
                $('.block .btn.fa-minus').not(el).removeClass('fa-minus').addClass('fa-plus');
                el.addClass('fa-minus').removeClass('fa-plus');
                el.parent().parent().next().show();
                el.parent().addClass('text-strong');
            }
        }

        function closeStep(el) {
            $.post('users/store_onboarding_wizard_step', {'onboarding_wizard_step_id': $(el).data('step')})
                .then(function (e) {
                    $(el).closest('div').prev().find('i:first').addClass('text-success').addClass('fa-check');
                    $(el).closest('div').prev().find('span:first').css({'text-decoration': 'line-through'});

                    $.post('users/store_onboarding_wizard_step', {'onboarding_wizard_step_id': $(el).data('id')})
                        .then(function (e) {
                            var response = JSON.parse(e);
                            updateProgressBarTo(response.data.progress);
                            var confettiMaxCount = $(el).data('confetti-max-count') ? parseInt($(el).data('confetti-max-count')) : 150;
                            confetti.start()

                            var confettiTimeOut = $(el).data('confetti-time-out') ? parseInt($(el).data('confetti-time-out')) : 3000;
                            setTimeout(function (e) {
                                confetti.stop()
                            }, confettiTimeOut)
                            Popup.showCongrats($(el).data('action'));
                            updateDoneButtonStatuses();
                        })
                });
        }

        function openNextStepOrCloseWizard(el) {
            // indien er een volgende stap is.
            if ($(el).closest('.block-content').next().length) {
                $(el).closest('.block-content').next().find('.ob-wizard-toggle-sub:first').trigger('click');
            } else {
                closeWizard();
            }
        }

        function closeWizard() {
            $('#toggle-ob-wizard').trigger('click');
            markWizardCompleted();
        }

        function markWizardCompleted() {
            $('#ob-wizard-finished-icon').html('<i id="wizard-completed" class="text-success fa fa-check"></i>');
        }

        function consumeStepLink(el) {
            $.post('users/store_onboarding_wizard_step', {'onboarding_wizard_step_id': el.data('id')})
                .then(function (e) {
                    whileCount = 0
                    while (!el.hasClass('fa-circle-o')) {
                        whileCount++
                        el = el.prev();
                        if (whileCount > 10) return false;
                    }
                    el.addClass('text-success').addClass('fa-check-circle-o').removeClass('fa-circle-o');
                    var response = JSON.parse(e);
                    updateProgressBarTo(response.data.progress);
                    updateDoneButtonStatuses()
                });
            var parentStep = el.parents('.block-content').prev('div:first').find('.ob-wizard-toggle-sub').data('step');
            $.ajax({
                url: '/users/onboarding_wizard',
                type: 'PUT',
                data: {
                    'show': '1',
                    'active_step': parentStep
                }
            });
        }

        function updateProgressBarTo(percentage) {
            var valueAsString = percentage + '%';
            $('#progress-bar').css({'width': valueAsString});
            $('#progress-percentage').html(valueAsString);
            $('#progress-bar').attr('aria-valuenow', percentage)
            if (percentage === 100) {
                $('#ob-wizard').hide();
                saveShowState(false);
            }

        }

        function hideAllSubSteps() {
            $(".block .block-content").hide();
            $(".block-head .text-strong").removeClass('text-strong');
        }

        function updateDoneButtonStatuses() {
            isFirst = true;
            $(".btn-done").each(function () {
                var disabled = false;
                var btn = $(this);
                // binnen huidige stap zijn alle elementen afgerond.
                if ($(this).closest('ul').find('i').length === $(this).closest('ul').find('i.fa-check-circle-o').length) {
                    // is jouw hoofditem reeds afgerond;
                    if ($(this).parents('div.block-content:first').prev('div').find('i:first').hasClass('text-success')) {
                        disabled = true;
                    } else {
                        // indien vorige stap afgerond of de eerste stap
                        if ($(this).parents('div.block-content:first').prev().prev().prev().find('i:first').hasClass('text-success') || isFirst) {
                            $(this).attr('disabled', false).addClass('active-button blue').removeClass('grey').removeClass('not-all-previous-steps-completed');
                        } else {
                            $(this).addClass('not-all-previous-steps-completed').addClass('grey').removeClass('active-button blue')
                        }
                    }
                }
                if (disabled) {
                    btn.attr('disabled', true).removeClass('blue').addClass('grey').removeClass('active-button');
                }
                isFirst = false;
            });
            // update active step.
            canAddArrow = true;
            $('.block-head > div > i').each(function () {
                if (!$(this).hasClass('fa-check') && canAddArrow) {
                    $(this).addClass('fa-chevron-right');
                    canAddArrow = false;
                } else {
                    $(this).removeClass('fa-arrow');
                }
            });
        }

        onboarding_wizard_scripting_loaded = true;
    }

    $('#ob-wizard-' + activeStep).find('.ob-wizard-toggle-sub').trigger('click');
    updateDoneButtonStatuses();
    if (showOnboardWizard != 1) {
        toggleWizardVisibilityState();
    }

    function resendEmailVerificationMail() {
        $.ajax({
            url: '/users/resendEmailVerificationMail',
            type: 'POST',
            success: function () {
                Notify.notify('De verificatiemail is nogmaals naar je verstuurd', 'info');
            }
        });
    }

</script>
<style>
    .block .block-content {
        width: 80%;
        padding-bottom: 0px;
    }

    .block-content > ul {
        margin: 0 0 0 25px;
        border-left: 1px solid #eeeeee;
        padding-left: 23px;
        padding-top: 1em;
        padding-bottom: 1em;
    }

    .block li {
        list-style-type: none;
        border-bottom: 2px solid white;
        padding-left: 8px;
    }

    .block-content li:nth-child(even) {
        background-color: #f9f9f9;
    }

    .text-success {
        color: #76b827;
    }

    .text-strong {
        font-weight: bold;
    }

    .extra-padding {
        padding: 12px !important;
    }

    .ob-wizard-step-link {
        text-decoration: none;
        color: black;
    }

    #ob-wizard .block-content {
        line-height: 29px;
        padding-top: 0px;
    }

    .btn.white.ob-wizard-toggle-sub {
        padding-top: 5px;
        color: #337ab7;
    }

    .prr-button {
        padding: 6px;
        line-height: 15px;
        text-align: center;
        width: 300px;
        border-left: 1px solid white;
        border-right: 1px solid white;
    }

    .knowledgebase-button {
        width: 214px;
    }

    .btn-done {
        margin-left: 5px;
        display: block;
        padding-left: 19px;
        padding-right: 19px;
        margin-left: -9px;
    }
</style>