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
		<meta name="viewport" content="width=1280, user-scalable = no">

		<link href="/css/default.css?v=<?= time() ?>" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="/css/font-awesome.min.css">
		<link rel="stylesheet" href="/css/jquery-ui.css">

		<link rel="icon" href="/img/Logo-Test-Correct recolored icon-only.svg"/>
		<link rel="apple-touch-icon-precomposed" href="https://www.test-correct.nl/wp-content/uploads/2019/01/cropped-fav-180x180.png" />
		<meta name="msapplication-TileImage" content="https://www.test-correct.nl/wp-content/uploads/2019/01/cropped-fav-270x270.png" />

		<?php
			if(MaintenanceHelper::getInstance()->isInMaintenanceMode()){
				echo '<style> #header.blue { background: #ff6666; }</style>';
			}
		?>

		<script src="/js/jquery.min.js"></script>
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
		
		<script src="/js/URLSearchParamsPolyfill.js?<?= time() ?>"></script>
        <script src="https://www.wiris.net/demo/plugins/app/WIRISplugins.js?viewer=image"></script>
	</head>

	<body>
		
		<div id="loading">
			<img src="/img/loading.gif" />
		</div>

		<div id="fade"></div>
		<div id="page_fade"></div>
		<div id="background"></div>

		<div id="notifications"></div>

		<div id="header" class="highlight">
            <?= $this->element('logo_circle', array('onclick' => 'Menu.dashboardButtonAction(\'dashboard\')')) ?>
            <?= $this->element('logo_text', array('onclick' => 'Menu.dashboardButtonAction(\'dashboard\')')) ?>
<!--			<img src="/img/logo_1.png" id="logo_1" onclick="User.welcome();" />-->
<!--			<img src="/img/logo_2.png" id="logo_2" onclick="User.welcome();" />-->
			<span id="versionBadge"></span>
			<div id="top">
				<div id="user"></div>
                <div id="action_icons"></div>

				<div id="user_menu">
                    <div id="user_school_locations"></div>
					<a href="#" onclick="User.logout(true);" id="btnLogout" class="btn white"><?= __("Uitloggen")?></a>
					<a href="#" onclick="User.resetPassword();" class="btn white mt5" id="btnChangePassword" ><?= __("Wachtwoord wijzigen")?></a>
					<a href="#" onclick="TestTake.handIn(); return false" id="btnMenuHandIn" class="btn white mt5" style="display: none;"><?= __("Inleveren")?></a>
				</div>

                <div id="support_menu">
                    <a href="#" onclick="Popup.showExternalPage('https://support.test-correct.nl')" id="btnMenuKnowledgeBase" class="btn white"><?= __("Kennisbank")?></a>
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
		</div>

		<div id="tiles" class="highlight"></div>

		<div id="container"></div>
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

            <?php if($name = CakeSession::read('Support.name')) {?>
                Notify.notify('Let op! Je bent ingelogd via het support account van <?= $name ?>', 'info', 10000)
                Menu.supportUser = '<?= CakeSession::read("Support.id") ?>';
            <?php }?>
		</script>
	</body>
</html>
