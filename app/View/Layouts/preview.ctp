<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Test Correct</title>

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=1280, user-scalable = no">

    <link href="/css/test_preview.css?v20200829143300" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet"
          href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css"/>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-beta.3/js/select2.min.js"></script>
    <script type="text/javascript" src="/js/jquery.touch.js"></script>
    <script src="/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script src="/ckeditor/adapters/jquery.js"></script>
    <script type="text/javascript" src="/js/popup.js"></script>
    <script type="text/javascript" src="/js/answer.js"></script>
    <script type="text/javascript" src="/js/answer.js"></script>
    <script type="text/javascript" src="/js/table.js"></script>
    <script type="text/javascript" src="/js/test_preview.js"></script>
    <script type="text/javascript" src="/js/redactor.js"></script>
    <script type="text/javascript" src="/js/test_take.js?2020829134501"></script>
    <script type="text/javascript" src="/js/test.js"></script>
    <script type="text/javascript" src="/js/limiter.js"></script>
    <script src="https://www.wiris.net/demo/plugins/app/WIRISplugins.js?viewer=image"></script>
</head>

<body>
<div id="container">
    <?= $content_for_layout ?>
</div>
<script>
    $(document).ready(function () {
        document.renderCounter = 0;
        renderMathML()
    })

    function renderMathML() {
        if ('com' in window && 'wiris' in window.com && 'js' in window.com.wiris && 'JsPluginViewer' in window.com.wiris.js) {
            com.wiris.js.JsPluginViewer.parseDocument();
        } else {
            // try again in half a second but no more then for 5 seconds.
            if (document.renderCounter < 10) {
                document.renderCounter ++;
                setTimeout(() => renderMathML(), 500);
            }
        }
    }

</script>
</body>


</html>