<?php
if ($wizard_steps) {
    ?>
   <!-- <div style="position: relative; width: 100%">
        <div style="position:absolute; top:30px; right: 0;">
            <button id="scrollToDemo" class="button primary-button button-md" >
                <span>
                     <?/*= __("Naar de demo tour") */?>
                </span>
            </button>
        </div>
    </div>-->
<?php } ?>

<div class="dashboard">
    <div class="notes">
        <?php if(($has_features )|| $maintenanceNotification) { ?>
            <?php if($has_features && $shouldShowIfNewFeatureMessageClosed ) { ?>
                <div class="feature-message notification info">
                    <div>
                        <div class="title">
                            <h5 style="
                                    width: 100%;
                                ">
                                <?= __("dashboard.features message title") ?>
                                <button class="text-button pull-right " onclick="closeFeatureMessage();" >
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </button>
                            </h5>

                        </div>
                        <div class="body">
                            <p>
                                <?= __("dashboard.features message text") ?>
                            </p>
                            <p>
                                <a href="#" class="text-button" style="text-decoration: none;" onclick="Popup.load('users/new_features_popup/'  , popupWidth)"><?= __("dashboard.features message link") ?>  <?php echo $this->element('arrow') ?></a>
                            </p>
                        </div>

                    </div>
                </div>
            <?php }?>

                <div>
                    <?php if ($maintenanceNotification) { ?>
                        <div class="body">
                            <?= $maintenanceNotification ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php if ($afterLoginMessage) { ?>
        <div class="notification info">
                <div class="notification">
                    <?= $afterLoginMessage ?>
                </div>
        </div>
        <?php } ?>
        <?php if ($shouldDisplayGeneralTermsNotification) {?>
        <div class="notification warning terms-and-conditions">
            <div class="title">
                <h5 style=""><?= __("Op de dienst Test-Correct zijn de algemene voorwaarden van toepassing")?></h5>
            </div>
            <div class="body mb20">
                <p style="display: block; margin-bottom: 1rem;"><?= __("Wij vragen je onze algemene voorwaarden te lezen en accepteren. Dit is nodig om van onze producten gebruik te kunnen blijven maken. Je hebt 14 dagen de tijd om deze te lezen en accepteren. Daarna zal jouw account worden bevroren totdat deze zijn geaccepteerd.")?></p>
                <a href="#" class="text-button" style="text-decoration: none;" onclick="Popup.load('users/terms_and_conditions/<?= $generalTermsDaysLeft ?>', popupWidth)"><?= __("Lees en accepteer onze algemene voorwaarden")?> <?php echo $this->element('arrow') ?></a>
            </div>
            <div class="flex tabs">
                <?php for($i = 13; $i >= 0; $i--) {?>
                    <div class="flex tab" style="<?= $i >= $generalTermsDaysLeft ? 'background-color:var(--teacher-Highlight-dark)' : '' ?>">
                        <span><?= $i == $generalTermsDaysLeft ? __('nog '). $generalTermsDaysLeft . ($generalTermsDaysLeft == 1 ? __(" dag"): __(" dagen")) : ''?></span>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <?php if ($shouldDisplayTrialPeriodNotification) {?>
            <?php if( $trialPeriodDaysLeft <= 7) { ?>
                <?php if(AuthComponent::user('has_package') == 1) {?>
                    <div class="notification info trial-period">
                        <div class="body mb20">
                            <p style="display: block; margin-bottom: 1rem;"><?= __('Je hebt nog') ?> <?= $trialPeriodDaysLeft ?> <?= ($trialPeriodDaysLeft == 1 ? __(" dag."): __(" dagen.")) . __(' Je licentie loopt binnenkort af. Verleng deze als je gebruik wilt blijven maken van Test-Correct.') ?></p>
                            <a href="https://www.test-correct.nl/pakketten" target="_blank" class="text-button" style="text-decoration: none;"><?= __("Meer informatie")?> <?php echo $this->element('arrow') ?></a>
                        </div>
                        <div class="flex tabs">

                            <?php for($i = $trialPeriodTotalDays; $i >= 0; $i--) {?>
                                <div class="flex tab" style="<?= $i >= $trialPeriodDaysLeft ? 'background-color:var(--primary)' : '' ?>">
                                    <span class="<?= $i == $trialPeriodDaysLeft ? 'current-day' : ''?>"><?= $i == $trialPeriodDaysLeft ? __('nog '). ($trialPeriodDaysLeft) . ($trialPeriodDaysLeft == 1 ? __(" dag"): __(" dagen")) : ''?></span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php }else{ ?>
                        <div class="notification info trial-period">
                        <div class="body mb20">
                            <p style="display: block; margin-bottom: 1rem;"><?= __('Je hebt nog') ?> <?= $trialPeriodDaysLeft ?> <?= ($trialPeriodDaysLeft == 1 ? __(" dag"): __(" dagen")) . __(' over in je proefperiode. Je kan je proefperiode verlengen door een demonstratie in te plannen of een licentiepakket te kiezen.') ?></p>
                            <a href="https://www.test-correct.nl/pakketten" target="_blank" class="text-button" style="text-decoration: none;"><?= __("Meer informatie")?> <?php echo $this->element('arrow') ?></a>
                        </div>
                        <div class="flex tabs">

                            <?php for($i = $trialPeriodTotalDays; $i >= 0; $i--) {?>
                                <div class="flex tab" style="<?= $i >= $trialPeriodDaysLeft ? 'background-color:var(--primary)' : '' ?>">
                                    <span class="<?= $i == $trialPeriodDaysLeft ? 'current-day' : ''?>"><?= $i == $trialPeriodDaysLeft ? __('nog '). ($trialPeriodDaysLeft) . ($trialPeriodDaysLeft == 1 ? __(" dag"): __(" dagen")) : ''?></span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } ?>
        <?php if ($should_display_import_incomplete_panel) { ?>
        <div class="notification warning">
            <div class="title">
                <h5 style=""><?= __("De importgegevens van klassen zijn bijna compleet")?></h5>
            </div>
            <div class="body">
                <p><?= __("De geïmporteerde gegevens van klassen uit")?> <?= $lvs_type ?> <?= __("zijn bijna compleet. Vul de gegevens aan om de klassen te kunnen inplannen voor toetsen.")?></p>
                <a class="text-button" onclick="displayCompleteUserImport()"><?= __("Importgegevens voor klassen compleet maken")?>.<?php echo $this->element('arrow') ?></a>
            </div>
        </div>
        <?php } ?>
        <?php

        if (!$account_verified) {
            ?>
            <div class="notification error">
                <div class="title">
                    <?php echo $this->element('warning', array('color' => 'var(--error-text)')) ?><h5
                            style="margin-left: 20px;"><?= __("Let op! Je account is nog niet geverifieerd.")?></h5>
                </div>
                <div class="body">
                    <p><?= __("Je account is nog niet geverifieerd in ons systeem. Heb je geen mail ontvangen?")?></p>
                    <a class="text-button" onclick="resendEmailVerificationMail()">
                    <?= __("Stuur verificatiemail opnieuw")?><?php echo $this->element('arrow') ?>
                    </a>
                </div>
            </div>
            <?php
        } else if (AuthComponent::user('is_temp_teacher')) { ?>
            <div class="notification warning">
                <div class="title">
                    <?php echo $this->element('warning', array('color' => 'var(--teacher-Highlight-dark)')) ?><h5
                            style="margin-left: 30px;"><?= __("Let op: je account is nog niet geactiveerd.")?></h5>
                </div>
                <div class="body">
                    <p><?= __("Vanuit het oogpunt van informatiebeveiliging voeren wij een dubbelcheck uit op de door jouw opgegeven data, voordat je klassen en toetsen kunt uploaden en kunt samenwerken met je collega’s in Test-Correct. Dat kan één tot twee werkdagen in beslag nemen. Wil je niet zo lang wachten? Bel ons dan op ")?><a href="tel:010 7 171 171">010 7 171 171</a> <?= __("om dit te versnellen.")?></p>
                </div>
            </div>
        <?php } ?>

        <?php if($infos && count($infos)){
            echo $this->element('welcome_info_messages');
         } ?>

    </div>

    <div class="cta">
        <div class="svg">
            <?php echo $this->element('sticker_invite_colleague'); ?>
        </div>
        <div class="cta-content">
            <h4><?= __("Nodig een collega uit")?></h4>
            <p><?= __("Samen met collega’s kun je gebruikmaken van elkaars toetsen en vragen, voor elkaar surveilleren en analyses delen.")?></p>
        </div>
        <button type="button"
                onClick="Popup.load('/users/tell_a_teacher', 800);"
                class="button cta-button button-md"
                style="width: max-content; padding: 0 1.5rem"
        >
            <span><?= __("Nodig een collega uit")?></span>
        </button>
    </div>
    <div class="cta-blocks">
        <div class="block-container">
            <div class="cta-block">
                <div class="svg">
                    <?php echo $this->element('sticker_upload_classlist'); ?>
                </div>
                <h4><?= __("Klassen toevoegen")?></h4>
                <span class="subtitle"><?= __("Lever een klasbestand aan om klassen toe te voegen")?></span>
                <span class="body"><?= __("Gelieve aan te leveren als:")?><br> Excel, CSV</span>

                <?php if($has_lvs){ ?>
                    <?php if($should_display_import_incomplete_panel) { ?>
                        <button type="button"
                                onclick="displayCompleteUserImport();"
                                class="button cta-button button-md">
                            <span><?= __("Importgegevens voor klassen compleet maken")?></span>
                        </button>
                    <?php } else { ?>
                        <button type="button"
                                onclick="Popup.confirm(
                                        {
                                        'title' : '<?= __('Klassen zijn reeds gekoppeld via de automatische import')?>',
                                        'text': '<?= __('Neem contact op met onze support afdeling indien je gekoppeld bent aan de verkeerde klassen.')?>',
                                        'okBtn': $.i18n('Ok')
                                        },function() {
                                        return true;
                                        });"
                                class="button cta-button button-md">
                            <span><?= __("Klassen toevoegen")?></span>
                        </button>
                    <?php } ?>
                <?php } else { ?>

                <button type="button"
                        onclick="Popup.load('/file_management/upload_class', 800);"
                        class="button cta-button button-md">
                    <span><?= __("Klassen toevoegen")?></span>
                </button>
                <?php  } ?>
<!--                <div class="task-completed">-->
<!--                    --><?php //echo $this->element('checkmark'); ?>
<!--                </div>-->
            </div>
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
                        onclick="Popup.load('/tests/add', 1000);"
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
                <span class="body"><?= __("Gelieve aan te leveren als: <br> PDF, Word, Wintoets")?></span>
                <button type="button"
                        onclick="User.openUploadTestPage();"

                        class="button cta-button button-md">
                    <span><?= __("Toets uploaden")?></span>
                </button>
            </div>

        </div>
        <div class="slider-button left display-none" onclick="scrollToLeft()">
            <?php echo $this->element('chevron'); ?>
        </div>
        <div class="slider-button right display-none" onclick="scrollToRight()">
            <?php echo $this->element('chevron'); ?>
        </div>
    </div>

</div>
<div style="margin-top:-150px; position: absolute; " id="demo-tour">
<p></p>
</div>



<div style="height: 50px;">

</div>
<script src="/js/confetti.min.js"></script>
<script> $.i18n().locale = '<?=CakeSession::read('Config.language')?>';</script>
<script type="text/javascript" src="/js/welcome-messages.js?<?= time() ?>"></script>

<script>
    if('<?= $language?>' == 'eng'){
        ['demo','buttons','scrollToDemo'].forEach(function(id){
            var el = document.getElementById(id);
            if(el){
                el.style.display = 'none';
            }
        });
    }

    if (typeof hubspotLoaded == 'undefined') {

        var _hsq = window._hsq = window._hsq || [];
        _hsq.push(["identify", "<?=AuthComponent::user('username')?>"]);
        _hsq.push(['trackPageView']);

        // try {
        //     window.HubSpotConversations.widget.load();
        // } catch (error) {
        //
        // }

        hubspotLoaded = true;
    }

    HelpHero.identify("<?=AuthComponent::user('uuid')?>", {
        name: "<?=AuthComponent::user('name')?>",
        name_first: "<?=AuthComponent::user('name_first')?>",
        name_suffic: "<?=AuthComponent::user('name_suffix')?>"
    });

    // if (jQuery("#supportLinkUserMenu").length != 1) {
    //     jQuery("#user_menu").append('<a id="supportLinkUserMenu" href="https://support.test-correct.nl" target="_blank" class="btn white mt5" >' +  '<?= __("Supportpagina")?>' + '</a>');
    //     jQuery("#user_menu").append('<a id="extendAutoLogoutPopupButton" href="#" onClick="Popup.load(\'/users/prevent_logout?opened_by_user=true\')" class="btn white mt5">' +  '<?= __("Automatisch uitloggen uitstellen")?>' + '</a>');
    //     <?php
    //     if(AuthComponent::user('isToetsenbakker') == true){
    //     ?>
    //     jQuery("#user_menu").append('<a href="#" onClick="Navigation.load(\'file_management/testuploads\');" class="btn white mt5" >' + '<?= __("Te verwerken toetsen")?>' + '</a>');
    //     <?php
    //     }else {
    //     ?>
    //     jQuery("#user_menu").append('<a href="#" onClick="Navigation.load(\'file_management/testuploads\');" class="btn white mt5" >' +  '<?= __("Uploaden toets")?>' + '</a>');
    //     <?php
    //     }
    //     ?>
    // }
    // if($('#ot-sdk-btn').length !==1 ) {
    //     $("#user_menu").append('<a id="ot-sdk-btn" class="ot-sdk-show-settings btn white mt5 cookie-button">Cookie Settings</a>');
    // }

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
                            'title': '<?= __("Waarschuwing")?>',
                            'message': '<?= __("U kunt deze stap pas afronden als de voorgaande stap is afgerond")?>',
                            'btnOk': '<?= __("Ok")?>'
                        }, function () {
                            $.Event().preventDefault();
                        });
                    } else {
                        if ($(e.target).attr('disabled') !== 'disabled') {
                            Popup.messageWithPreventDefault({
                                    'title': '<?= __("Waarschuwing")?>',
                                    'message': '<?= __("Doorloop eerst alle sub stappen")?>',
                                    'btnOk': '<?= __("Ok")?>'
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
                .on("click", '#scrollToDemo' ,function (){
                    toggleWizardVisibilityState(true);
                    $("HTML, BODY").animate({
                        scrollTop: $("#demo-tour").offset().top
                    }, 500);
            });
            markWizardCompletedIfAppropriate();
        })

        function saveShowState(show) {
            $.ajax({
                url: '/users/onboarding_wizard',
                type: 'PUT',
                data: {'show': show}
            });
        }

        function closeFeatureMessage(){
            $.ajax({
                url: '/infos/hideNewFeatureMessage',
                type: 'post',
                success: function (data){
                    isSendSuccesful =JSON.parse(data);
                    if(!isSendSuccesful.data){
                        return Notify.notify($.i18n('Pop up kon niet worden gesloten'), 'error');
                    }
                    return jQuery('.feature-message').hide();
                },
                error:function (){
                    Notify.notify($.i18n('Pop up kon niet worden gesloten'), 'error');
                }
            });
        }

        function toggleWizardVisibilityState(doNotHide) {
            var doNotHide = !!doNotHide;
            var tabDemoTour = $('#toggle-ob-wizard');
            var show = true;
            var chevron = tabDemoTour.find('#checked_classes_svg');
            var tabDemoTourText = tabDemoTour.find('.text');

            if ($('#ob-wizard').is(':hidden') || doNotHide) {
                tabDemoTourText.html($.i18n('Demotour verbergen'));
                chevron.css({ transform: 'rotate(270deg)', transition:'0.5s ease-in-out'});
                $('#ob-wizard').slideDown( 'slow' );
            } else {
                tabDemoTourText.html($.i18n('Demotour tonen'));
                chevron.css({transform: 'rotate(90deg)', transition: '0.5s ease-in-out'})
                $('#ob-wizard').slideUp('slow');
                show = false;
            }
            markWizardCompletedIfAppropriate();

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
            markWizardCompletedIfAppropriate();
        }

        function markWizardCompletedIfAppropriate() {
            var progress = $('#progress-percentage').data('progress');
            if (100 === parseInt(progress)) {
                $('#ob-wizard-finished-icon').html('<i id="wizard-completed" class="text-success fa fa-check"></i>');
            }
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
            $('#progress-percentage').html(valueAsString).data('progress',parseInt(valueAsString));
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
                Notify.notify('<?= __("De verificatiemail is nogmaals naar je verstuurd")?>', 'info');
            }
        });
    }

    let width = 0;
    let rightButton = document.querySelector('.slider-button.right');
    let leftButton = document.querySelector('.slider-button.left');
    let dashboard = document.querySelector('.dashboard');
    let ctaBlockContainer = document.querySelector('.block-container');

    document.querySelectorAll('.cta-block').forEach(function (block) {
        width += block.offsetWidth;
    });

    function checkForSlider() {
        if (width > dashboard.offsetWidth) {
            if (ctaBlockContainer.scrollLeft === 0) {
                rightButton.classList.remove('display-none');
            } else {
                leftButton.classList.remove('display-none');
            }
        } else {
            rightButton.classList.add('display-none');
            leftButton.classList.add('display-none');
        }
    }

    checkForSlider();

    function scrollToLeft() {
        ctaBlockContainer.scrollTo({left: 0, behavior: 'smooth'})
        leftButton.classList.add('display-none');
        rightButton.classList.remove('display-none');
    }

    function scrollToRight() {
        ctaBlockContainer.scrollTo({left: width, behavior: 'smooth'})
        rightButton.classList.add('display-none');
        leftButton.classList.remove('display-none');
    }

    window.addEventListener('resize', checkForSlider);

    function displayCompleteUserImport() {
        Popup.load('users/teacher_complete_user_import_main_school_class', 1080);
    }

    if ($('#support_webinar').length !== 1) {
        $('#support_menu').append(
            '<a id="support_webinar" href="#" onclick="Popup.showExternalPage(\'https://embed.webinargeek.com/ac16aaa56a08d79ca2535196591dd91b20b70807849b5879fe\', 600, 350)" class="btn white mt5">Webinar</a>\n' +
            '<a id="support_email" href="mailto:support@test-correct.nl" class="btn white mt5">E-mail</a>\n' +
            '<a id="support_updates" href="#" onclick="Popup.showExternalPage(\'https://support.test-correct.nl/knowledge/wat-zijn-de-laatste-updates\', 1000)" class="btn white mt5"><?= __("Updates")?> &amp; <?= __("onderhoud")?></a>'
        );
    }
    var popupWidth = $(document).width() < 600 ? $(document).width() : 600;
    <?php if($shouldDisplayGeneralTermsNotification && $generalTermsDaysLeft == 0) { ?>
    setTimeout(function () {
            Popup.load('users/terms_and_conditions/<?= $generalTermsDaysLeft ?>', popupWidth);
    }, 1000);
    <?php } ?>
    <?php if($shouldDisplayTrialPeriodNotification && $trialPeriodDaysLeft == 0) { ?>
    setTimeout(function () {
            Popup.load('users/trial_period_ended/<?=$trialPeriodDaysLeft?>/'+ (User.info.school_location_list.length > 1), popupWidth);
    }, 1000);
    <?php } ?>
    <?php if(!$shouldDisplayGeneralTermsNotification && !$shouldDisplayTrialPeriodNotification && $shouldShowIfNewFeature) { ?>
    setTimeout(function () {
        Popup.load('users/new_features_popup/'  , popupWidth)
    }, 1000);
    <?php } ?>

    <?php if($name = CakeSession::read('support.name')) {?>
    Notify.notify('<?= __("Let op! Je bent ingelogd via het support account van"). " ".$name ?>', 'info', 10000)
    <?php }?>
    $(document).ready(function () {
        if ($('.trial-period .tab .current-day').length == 0) return;

        var currentDayText = $('.trial-period .tab .current-day');

        if (currentDayText.is(':first-child')) return;
        if (currentDayText.get(0).offsetWidth > currentDayText.parentElement.getBoundingClientRect().right) {
            currentDayText.get(0).style.left = '0';
        }
    });
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
    .read-more{
        width: 100%;
        display: flex;
        border-bottom: solid 1px var(--blue-grey);
        justify-content: center;

    }
    .hide-demo-tour {
        padding: 8px 16px 0 16px;
        background-color: #f5f5f5;
        color: #041f74;
        text-align:center;
        line-height: 1.5;
        display: inline-flex;
        box-sizing:border-box;
        align-items: center;
        cursor:pointer;
        position:relative;
        top:1px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        border-top: solid 1px var(--blue-grey);
        border-right: solid 1px var(--blue-grey);
        border-left: solid 1px var(--blue-grey);

    }
    .hide-demo-tour .text {
        display:flex;
        flex-grow:1;
        text-align:center;
        font-size:16px;
        font-weight: bold;
        margin-right: 8px
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
    html{
        transition-duration: 300ms;
        scroll-behavior: smooth;
    }
</style>
