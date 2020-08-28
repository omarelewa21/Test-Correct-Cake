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

		<link href="/css/default.css?v=20200828-141200" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"> -->
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

		<link rel="icon" href="https://www.test-correct.nl/wp-content/uploads/2019/01/cropped-fav-32x32.png" sizes="32x32" />
		<link rel="icon" href="https://www.test-correct.nl/wp-content/uploads/2019/01/cropped-fav-192x192.png" sizes="192x192" />
		<link rel="apple-touch-icon-precomposed" href="https://www.test-correct.nl/wp-content/uploads/2019/01/cropped-fav-180x180.png" />
		<meta name="msapplication-TileImage" content="https://www.test-correct.nl/wp-content/uploads/2019/01/cropped-fav-270x270.png" />

		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-beta.3/js/select2.min.js"></script>

		<script src="//code.highcharts.com/highcharts.js"></script>
		<script src="//code.highcharts.com/highcharts-more.js"></script>

		<script type="text/javascript" src="/js/jquery.touch.js"></script>

		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<script src="/ckeditor/ckeditor.js" type="text/javascript"></script>
		<script src="/ckeditor/adapters/jquery.js"></script>

		<script type="text/javascript" src="/js/jquery.datetimepicker.js"></script>
		<script type="text/javascript" src="/js/popup.js?20200520193000"></script>
		<script type="text/javascript" src="/js/definitions.js"></script>
		<script type="text/javascript" src="/js/navigation.js"></script>
		<script type="text/javascript" src="/js/core.js?20200304092201"></script>
		<script type="text/javascript" src="/js/answer.js"></script>
		<script type="text/javascript" src="/js/menu.js?20200520090000"></script>
		<script type="text/javascript" src="/js/table.js"></script>
		<script type="text/javascript" src="/js/questions.js"></script>
		<script type="text/javascript" src="/js/redactor.js"></script>
		<script type="text/javascript" src="/js/tablefy.js"></script>
		<script type="text/javascript" src="/js/test_take.js?20200324151301"></script>
		<script type="text/javascript" src="/js/test.js"></script>
		<script type="text/javascript" src="/js/formify.js?20200707105400"></script>
		<script type="text/javascript" src="/js/notifications.js"></script>
		<script type="text/javascript" src="/js/user.js?20200407202601"></script>
		<script type="text/javascript" src="/js/subscript.js"></script>
		<script type="text/javascript" src="/js/limiter.js"></script>
		<script type="text/javascript" src="/js/counter.js"></script>
		<script type="text/javascript" src="/js/cookie.js"></script>
		<script type="text/javascript" src="/js/analyses.js"></script>
		<script type="text/javascript" src="/js/prettyCheckable.min.js"></script>
		<script src="/js/URLSearchParamsPolyfill.js"></script>
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
			<img src="/img/logo_1.png" id="logo_1" onclick="User.welcome();" />
			<img src="/img/logo_2.png" id="logo_2" onclick="User.welcome();" />
			<span id="versionBadge"></span>
			<div id="top">
				<div id="user"></div>
				<div id="user_menu">
					<a href="#" onclick="User.logout();" id="btnLogout" class="btn white">Uitloggen</a>

					<a href="#" onclick="User.resetPassword();" class="btn white mt5" id="btnChangePassword" >Wachtwoord wijzigen</a>

					<a href="#" onclick="TestTake.handIn(); return false" id="btnMenuHandIn" class="btn white mt5" style="display: none;">Inleveren</a>
				</div>

			</div>
			<div id="menu"></div>
		</div>

		<div id="tiles" class="highlight"></div>

		<div id="container"></div>
        <script src="//app.helphero.co/embed/2EBWUZfGT2n"></script>
		<!-- Start of HubSpot Embed Code --><script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/3780499.js"></script><!-- End of HubSpot Embed Code -->
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

		</script>
	</body>
</html>
