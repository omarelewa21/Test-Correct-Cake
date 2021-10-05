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
        <a href="#" onclick="saveNotePad();" class="btn highlight small ml5 pull-right"><span class="fa fa-check"></span> <?= __("Klaar")?></a>
    </div>
</header>

<textarea style="width:99%; height:480px;" id="txtNote"></textarea>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
    function saveNotePad() {
        $.post('/answers/note_pad/<?=$question_id?>',
            {
                'text' : $('#txtNote').val()
            },
            function() {
                window.parent.Answer.notePadClose('<?=$question_id?>');
            }
        );
    }
</script>
</body>
</html>