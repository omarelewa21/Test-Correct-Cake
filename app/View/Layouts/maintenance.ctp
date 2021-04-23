<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Test-Correct</title>

		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width=1280, user-scalable = no">

		<link href="/css/default.css?v=20200829-143300" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="/css/font-awesome.min.css">

		<link rel="stylesheet" href="/css/jquery-ui.css">

		<link rel="icon" href="https://www.test-correct.nl/wp-content/uploads/2019/01/cropped-fav-32x32.png" sizes="32x32" />
		<link rel="icon" href="https://www.test-correct.nl/wp-content/uploads/2019/01/cropped-fav-192x192.png" sizes="192x192" />
		<link rel="apple-touch-icon-precomposed" href="https://www.test-correct.nl/wp-content/uploads/2019/01/cropped-fav-180x180.png" />
		<meta name="msapplication-TileImage" content="https://www.test-correct.nl/wp-content/uploads/2019/01/cropped-fav-270x270.png" />

		<script src="/js/jquery.min.js"></script>
		<script src="/js/jquery-ui.min.js"></script>

		<script type="text/javascript" src="/js/bootstrap.min.js"></script>

		<script type="text/javascript" src="/js/core.js?20200928141201"></script>
	</head>

	<body>

		<div id="background"></div>


		<div id="container">
			<div class="block" style="background-color: #ff6666;max-width:650px;margin:auto">
				<div class="m56" style="margin-top:75px;padding:15px 15px 25px 15px">
					<?php
				echo MaintenanceHelper::getInstance()->getMaintenanceMessage();
					?>
				</div>
			</div>

		</div>
	</body>
</html>
