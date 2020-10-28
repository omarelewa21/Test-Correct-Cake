<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="/css/buttons.css" rel="stylesheet" type="text/css" />
    <link href="/css/spacing.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <style>
        body {
            font-family: Myriad Pro, Arial;
            background: white;
        }
    </style>

    <title>HTML5 viewport</title>
</head>
<body>

<header>
    <div>
        <a id="btn-undo" class="btn highlight small mr2 pull-left"><span class="fa fa-mail-reply"></span></a>
        <a id="btn-redo" class="btn highlight small mr2 pull-left"><span class="fa fa-mail-forward"></span></a>
        <a id="btn-tool-freeform" class="btn highlight small mr2 pull-left"><span class="fa fa-paint-brush"></span></a>
        <a id="btn-tool-line" class="btn highlight small mr2 pull-left"><span class="fa fa-minus"></span></a>
        <a id="btn-tool-arrow" class="btn highlight small mr2 pull-left"><span class="fa fa-long-arrow-right"></span></a>
        <a id="btn-tool-shape-circle" class="btn highlight small mr2 pull-left"><span class="fa fa-circle-thin"></span></a>
        <a id="btn-tool-shape-rectangle" class="btn highlight small mr2 pull-left"><span class="fa fa-square-o"></span></a>
        <a id="btn-export" class="btn highlight small ml5 pull-right"><span class="fa fa-check"></span> Klaar</a>
    </div>
</header>

<div id="canvas-holder" class="v-center__wrapper">

</div>

<script type="text/javascript">
    window.question_id = '<?=$question_id?>';
    window.drawingSaveUrl = '/answers/save_drawing_pad/<?=$question_id?>';
    window.parent.drawingCallback = function(){
        window.parent.Answer.drawingPadClose('<?=$question_id?>');
        window.parent.Loading.hide();
    };
</script>

<!-- Vendors -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="/js/filesaver.min.js"></script>
<script src="/js/canvas-toblob.js"></script>

<script src="/js/paint.js"></script>
<script src="/js/loadPaint.js"></script>

</body>
</html>