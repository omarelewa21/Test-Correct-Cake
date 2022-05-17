<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="/css/buttons.css" rel="stylesheet" type="text/css" />
    <link href="/css/spacing.css" rel="stylesheet" type="text/css" />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/bootstrap.tooltips.css" />
    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: Myriad Pro, Arial;
        }
    </style>

    <title>HTML5 viewport</title>
</head>
<body>

<header>
    <div>
        <?=$this->Form->create('Question', array('id' => 'FormBackground', 'type' => 'file', 'target' => 'frameUploadBackground', 'action' => '/drawing_background_add'))?>

            <!--<a id="btn-uno" title="Ongedaan maken" class="btn highlight small mr2 pull-left"><span class="fa fa-mail-reply"></span></a>
            <a id="btn-redo" title="Stap vooruit" class="btn highlight small mr2 pull-left"><span class="fa fa-mail-forward"></span></a>-->
            <a id="btn-tool-freeform" title='<?= __("Tekenen")?>' class="btn highlight small mr2 pull-left"><span class="fa fa-paint-brush"></span></a>
            <a id="btn-tool-line" title='<?= __("Lijn")?>' class="btn highlight small mr2 pull-left"><span class="fa fa-minus"></span></a>
            <a id="btn-tool-arrow" title='<?= __("Pijl")?>' class="btn highlight small mr2 pull-left"><span class="fa fa-long-arrow-right"></span></a>
            <a id="btn-tool-shape-circle" title='<?= __("Cirkel")?>' class="btn highlight small mr2 pull-left"><span class="fa fa-circle-thin"></span></a>
            <a id="btn-tool-shape-rectangle" title='<?= __("Vierkant")?>' class="btn highlight small mr2 pull-left"><span class="fa fa-square-o"></span></a>

            <a class="btn highlight small mr2 pull-left" title='<?= __("Raster")?>'>
                <select id="select-grid" class="btn highlight small mr2 pull-left" style='position: absolute; width:25px; margin-left: -10px; z-index:100; opacity: 0;'>
                    <option value="0"><?= __("Geen")?></option>
                    <option value="1">2</option>
                    <option value="2">3</option>
                    <option value="3">4</option>
                    <option value="4">5</option>
                    <option value="5">6</option>
                    <option value="6">7</option>
                    <option value="7">8</option>
                </select>
                <span class="fa fa-table"></span>
            </a>

            <a class="btn highlight small mr2 pull-left" title='<?= __("Achtergrond")?>'>
                <?=$this->Form->input('background', array('id' => 'btn-image', 'type' => 'file', 'accept' => 'image/jpeg, image/png', 'label' => false, 'div' => false, 'style' => 'position: absolute; width:25px !important; margin-left: -10px; z-index:100; opacity: 0;'))?>
                <span class="fa fa-file-image-o"></span>
            </a>

            <a id="btn-export" class="btn highlight small ml5 pull-right" style="cursor:pointer;" selid="save-drawing-btn"><span class="fa fa-check"></span> <?= __("Opslaan")?></a>
            <a class="btn grey small ml5 pull-right" style="cursor:pointer;" onclick="window.parent.Popup.closeLast();"><span class="fa fa-remove"></span> <?= __("Sluiten")?></a>

            <a id="btn-color-blue" class="btn small mr2 pull-right colorBtn" style="background: blue; width:7px; height:16px; opacity: .3;"></a>
            <a id="btn-color-red" class="btn small mr2 pull-right colorBtn" style="background: red; width:7px; height:16px; opacity: .3;"></a>
            <a id="btn-color-green" class="btn small mr2 pull-right colorBtn" style="background: green; width:7px; height:16px; opacity: .3;"></a>
            <a id="btn-color-black" class="btn small mr2 ml10 pull-right colorBtn" style="background: black; width:7px; height:16px;"></a>

            <a id="btn-thick-1" class="btn small mr2 pull-right thickBtn highlight" style="padding: 7px 12px 2px 12px;" title='<?= __("lijndikte 1")?>' >
                <img src="/img/ico/line1.png" />
            </a>
            <a id="btn-thick-2" class="btn small mr2  pull-right thickBtn highlight" style="padding: 7px 12px 2px 12px;" title='<?= __("lijndikte 2")?>' >
                <img src="/img/ico/line2.png" />
            </a>
            <a id="btn-thick-3" class="btn small mr2 ml10 pull-right thickBtn highlight" style="padding: 7px 12px 2px 12px;" title='<?= __("lijndikte 3")?>'>
                <img src="/img/ico/line3.png" />
            </a>


        <?=$this->Form->end();?>

        <iframe id="frameUploadBackground" name="frameUploadBackground" width="0" height="0" frameborder="0"> </iframe>
    </div>
</header>
<br clear="all" />
<div id="canvas-holder" class="v-center__wrapper" style="border:1px solid gray; width: 970px; float:left;">

</div>

<div id="layers-holder" style="border: 1px solid gray; width: 200px; float:left; margin-left: 10px; height: 481px; overflow: auto;">

</div>

<script type="text/javascript">

    // console.log('here');
    // console.log(typeof isIpad);
    // console.log(typeof isiPad == typeof undefined);

    if(typeof isiPad == typeof undefined) {
        isiPad = false;
    }

    if(!isiPad) {
        $.each($('a'), function () {
            if ($(this).attr('title') != undefined) {
                $(this).tooltip({
                    title: $(this).attr('title'),
                    placement: 'bottom'
                });
            }
        });
    }
</script>

<!-- Vendors -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="/js/filesaver.min.js"></script>
<script src="/js/canvas-toblob.js"></script>

<script src="/js/paint.js"></script>
<script src="/js/loadPaint.js"></script>

</body>
</html>