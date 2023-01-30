<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Test-Correct</title>

		<?php
		if (Configure::read('bugsnag-key-browser')) {
		?>

		<script src="//d2wy8f7a9ursnm.cloudfront.net/v7/bugsnag.min.js"></script>
		<script>Bugsnag.start({ apiKey: '<?=Configure::read('bugsnag-key-browser')?>' })</script>

		<?php } ?>

		<meta name="apple-mobile-web-app-capable" content="yes">
        <?php if (AuthComponent::user('roles.0.name') == 'Student' && AuthComponent::user('school_location.allow_new_student_environment') == false) { ?>
		    <meta name="viewport" content="width=1280, user-scalable = no">
        <?php } ?>

		<link href="/css/default.css?v=<?= time() ?>" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="/css/font-awesome.min.css">
		<link rel="stylesheet" href="/css/jquery-ui.css">

		<link rel="icon" href="/img/Logo-Test-Correct recolored icon-only.svg"/>
		<meta name="msapplication-TileImage" content="/img/Logo-Test-Correct recolored icon-only-270x270.svg" />

		<?php
			if(MaintenanceHelper::getInstance()->isInMaintenanceMode()){
				echo '<style> #header.blue { background: #ff6666; }</style>';
			}
		?>

		<script src="/js/jquery.min.js"></script>
        <script>
            jQuery.event.special.touchstart = {
                setup: function( _, ns, handle ) {
                    this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
                }
            };
            jQuery.event.special.touchmove = {
                setup: function( _, ns, handle ) {
                    this.addEventListener("touchmove", handle, { passive: !ns.includes("noPreventDefault") });
                }
            };
            jQuery.event.special.wheel = {
                setup: function( _, ns, handle ){
                    this.addEventListener("wheel", handle, { passive: true });
                }
            };
            jQuery.event.special.mousewheel = {
                setup: function( _, ns, handle ){
                    this.addEventListener("mousewheel", handle, { passive: true });
                }
            };
        </script>
		<script src="/js/jquery-ui.min.js"></script>
		<script src="/js/select2.min.js"></script>

		<script src="//code.highcharts.com/highcharts.js"></script>
		<script src="//code.highcharts.com/highcharts-more.js"></script>

		<script type="text/javascript" src="/js/jquery.touch.js"></script>

		<script type="text/javascript" src="/js/bootstrap.min.js"></script>
		<script src="/ckeditor/ckeditor.js" type="text/javascript"></script>
		<script src="/ckeditor/adapters/jquery.js"></script>

		<!-- Importing javascript files required for translation -->
		<script src="/js/jquery_i18n/CLDRPluralRuleParser.js?<?= time() ?>"></script>
        <script src="/js/jquery_i18n/jquery.i18n.js?<?= time() ?>"></script>
        <script src="/js/jquery_i18n/jquery.i18n.messagestore.js?<?= time() ?>"></script>
        <script src="/js/jquery_i18n/jquery.i18n.fallbacks.js?<?= time() ?>"></script>
        <script src="/js/jquery_i18n/jquery.i18n.language.js?<?= time() ?>"></script>
        <script src="/js/jquery_i18n/jquery.i18n.parser.js?<?= time() ?>"></script>
        <script src="/js/jquery_i18n/jquery.i18n.emitter.js?<?= time() ?>"></script>
        <script src="/js/jquery_i18n/jquery.i18n.emitter.bidi.js?<?= time() ?>"></script>
        <script> $.i18n().locale = '<?=CakeSession::read('Config.language')?>';</script>
		<script src="/js/translation.js?<?= time() ?>"></script>

		<script type="text/javascript" src="/js/polyfill.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/jquery.datetimepicker.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/popup.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/definitions.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/navigation.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/core.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/answer.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/menu.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/table.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/questions.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/redactor.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/tablefy.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/test_take.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/test.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/formify.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/notifications.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/user.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/subscript.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/limiter.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/counter.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/cookie.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/analyses.js?<?= time() ?>"></script>
		<script type="text/javascript" src="/js/prettyCheckable.min.js?<?= time() ?>"></script>
        <script type="text/javascript" src="/js/filtermanager.js?<?= time() ?>"></script>
        <script type="text/javascript" src="/js/overlay.js?<?= time() ?>"></script>
        <script type="text/javascript" src="/js/ckeditor_tlc_methods.js?<?= time() ?>"></script>
        <script type="text/javascript" src="/js/file-management.js?<?= time() ?>"></script>

		<script src="/js/URLSearchParamsPolyfill.js?<?= time() ?>"></script>
        <script src="https://www.wiris.net/demo/plugins/app/WIRISplugins.js?viewer=image"></script>

        <script type="text/javascript" src="//js.pusher.com/5.0/pusher.min.js"></script>

	</head>

	<body>

		<div id="loading">
			<img src="/img/loading.gif" />
		</div>

		<div id="fade"></div>
		<div id="page_fade"></div>
		<div id="background"></div>

		<div id="notifications"></div>

		<div id="header" class="highlight <?php if(MaintenanceHelper::getInstance()->isOnDeploymentTesting()){?> deployment-testing-marker <?php } ?>">
            <?php if (AuthComponent::user('guest') != true) { ?>
                <div class="logo-container">
                <?= $this->element('logo_circle', array('onclick' => 'Menu.dashboardButtonAction(\'dashboard\')')) ?>
                <?= $this->element('logo_text', array('onclick' => 'Menu.dashboardButtonAction(\'dashboard\')')) ?>
    <!--			<img src="/img/logo_1.png" id="logo_1" onclick="User.welcome();" />-->
    <!--			<img src="/img/logo_2.png" id="logo_2" onclick="User.welcome();" />-->
                    <span class="student_version_tag" style="display: none"></span>
                </div>
                <span id="versionBadge"></span>
                <div id="top">
                    <div class="user_name_button" selid="header-dropdown">
                        <div id="user"></div>
                        <?= $this->element('chevron', ['id' => 'user_chevron', 'style' => 'transform:rotate(90deg);']) ?>
                    </div>
                    <div id="action_icons"></div>

                    <div id="user_menu">
                        <div id="user_school_locations"></div>
                        <a href="javascript:void(0)" onclick="User.logout(true);" id="btnLogout" class="btn white" selid="logout-btn"><?= __("Uitloggen")?></a>
                        <a href="javascript:void(0)" onclick="User.resetPassword();" class="btn white mt5" id="btnChangePassword" ><?= __("Wachtwoord wijzigen")?></a>
                        <a href="javascript:void(0)" onclick="TestTake.handIn(); return false" id="btnMenuHandIn" class="btn white mt5" style="display: none;"><?= __("Inleveren")?></a>
                    </div>

                    <div id="support_menu">
                        <a href="#" onclick="Popup.showExternalPage('https://support.test-correct.nl/knowledge')" id="btnMenuKnowledgeBase" class="btn white"><?= __("Kennisbank")?></a>
                    </div>

                </div>
                <div class="menu-scroll-button left">
                    <span></span>
                    <?php echo $this->element('chevron', array('style' => 'color:var(--white);transform:rotate(180deg);')); ?>
                </div>
                <div id="menu"></div>
                <div class="menu-scroll-button right">
                    <span></span>
                    <?php echo $this->element('chevron', array('style' => 'color:var(--white);')); ?>
                </div>

            <?php } else { ?>
                <div class="guest_top">
                    <div class="guest_logo">
                        <?= $this->element('logo_new_full') ?>
                        <?php if(CakeSession::read('TLCVersion') != 'x') { ?>
                            <span class="student_version_tag <?= CakeSession::read('TLCVersionCheckResult') ?>"><?= __('Versie') ?>: <?= CakeSession::read('TLCVersion') ?></span>
                        <?php }?>
                    </div>
                    <div class="guest_name">
                        <button id="guest_user" onclick="showDropdown('#guest_name_dropdown', '#guest_user_chevron')">
                            <?= $this->element('chevron', ['id' => 'guest_user_chevron', 'style' => 'transform:rotate(90deg)']) ?>
                        </button>
                        <div id="guest_name_dropdown" style="display: none">
                            <button id="guest_user" onclick="showDropdown('#guest_name_dropdown', '#guest_user_chevron')">
                                <?= $this->element('chevron', ['id' => 'guest_user_chevron', 'style' => 'transform:rotate(-90deg)']) ?>
                            </button>
                            <button onclick="User.returnToLaravelLogin()">Log uit</button>
                        </div>
                    </div>
                </div>
            <?php } ?>
		</div>

		<div id="tiles" class="highlight"></div>

		<div id="container" <?= AuthComponent::user('guest') == true ? 'guest' : ''?>></div>
        <?= $this->element('temporary_login_options') ?>
        <script src="//app.helphero.co/embed/2EBWUZfGT2n"></script>
		<script>
            function onConversationsAPIReady() {
                window.hsConversationsSettings = {
                    loadImmediately: false
                };
			}

            if (window.HubSpotConversations) {
                onConversationsAPIReady();
            } else {
                window.hsConversationsOnReady = [onConversationsAPIReady];
            }
            <?php if(CakeSession::read('support.id')) {?>
                Menu.supportInfo =  {user: '<?= CakeSession::read("support.id") ?>', text: '<?= __("Terug naar support omgeving") ?>'};
            <?php }?>
        </script>

        <? foreach(AuthComponent::user('roles') as $role){ 
            if(strtolower($role['name']) === 'teacher'){?>
                <script>
                    User.userMenuExtension('teacher', {isToetsenbakker: <?= AuthComponent::user('isToetsenbakker') ? 'true' : 'false' ?>})
                </script>
                <script>
                    window.WEBSPELLCHECKER_CONFIG = {
                        "autoSearch": false,
                        "autoDestroy": true,
                        "autocorrect": true,
                        "autocomplete": true,
                        "serviceProtocol": "https",
                        "servicePort": "80",
                        "serviceHost": '<?= (AppHelper::isTestPortal()) ? "testwsc.test-correct.nl" : "wsc.test-correct.nl" ?>',
                        "servicePath": "wscservice/api"
                    }
                </script>
                <script src="https://<?= (AppHelper::isTestPortal()) ? "testwsc" : "wsc"?>.test-correct.nl/wscservice/wscbundle/wscbundle.js"></script>
        <?}}?>
	</body>
</html>