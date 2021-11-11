<html>
<head>
    <style>
        body {
            background: white;
            margin: 0px;
            font-size: 20px;
            text-align: center;
        }
    </style>
    <link href="/css/default.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>
<br /><br /><br />
<span id="audioSupportText"><?= __("Een ogenblik geduld")?>..</span>
<br />
<br />
<a href="#" role="button" id="audioBtn" class="btn disabled mt5"><?= __("Laden..")?></a>
<script type="text/javascript">
    function screenText(pauseable, playonce, playedonce, timesout, canPlay) {
        var text = '<?= __("Geluidsfragment")?>';
        if(playedonce) {
            text = '<?= __("Dit geluidsfragment was éénmalig afspeelbaar en is al beluisterd.")?>';
        } else if(!canPlay) {
            text = '<?= __("Dit apparaat ondersteunt geen geluidsfragmenten.")?>';
        } else {
            if(!pauseable) {
                if(playonce) {
                    text = '<?= __("Dit geluidsfragment is niet te pauzeren en slechts éénmaal te beluisteren.")?>';
                } else {
                    text = '<?= __("Dit geluidsfragment is niet te pauzeren.")?>';
                }
            } else if(playonce) {
                text = '<?= __("Dit geluidsfragment is slechts éénmaal te beluisteren.")?>';
            }

            if(timesout) {
                text = text + '<?= __(" Na sluiten van dit geluidsfragment heb je slechts ")?>' + timesout + '<?= __(" seconden om de vraag te beantwoorden")?>';
            }
        }
        var audioSupportText = document.getElementById("audioSupportText")
        audioSupportText.innerText = text
    }

    function playButtonText(text, disable) {
        var playButton = $("#audioBtn");
        playButton.text(text);
        if(disable) {
            playButton.removeClass("highlight");
            playButton.attr("disabled", true);
        } else {
            playButton.addClass("highlight");
            playButton.attr("disabled", false);
        }
    }

    <?
    $settings = json_decode($attachment['json'], true);
    ?>

    var pauseable = <?= ($settings['pausable'] == '1') ? "true" : "false"  ?>;
    var playonce = <?= ($settings['play_once'] == '1') ? "true" : "false"  ?>;
    var cookieName = 'attachment<?=$attachment_id?>';
    var playedonce = Cookies.get(cookieName);
    var timesout = <?= isset($settings['timeout']) && !empty($settings['timeout']) && $settings['timeout'] > 0 ? $settings['timeout'] : "false" ?>;
    // Testen of deze browser uberhaupt wel audio kan afspelen
    a = document.createElement('audio');
    var canPlay = !!(a.canPlayType && a.canPlayType('audio/mpeg;').replace(/no/, ''));
    if(!canPlay) {
        playButtonText("",true);
    }
    var playable = canPlay && !playedonce;

    function readyPlayer(soundbit) {
        var audio = new Audio('data:audio/mpeg;base64,' + soundbit);
        audio.load()
        // MarkO: Moet anonymous omdat anders de oude Chromium in de app faalt met een CORS melding. Op een data URI.
        audio.crossOrigin = 'Anonymous'
        audio.onended = function() {
            if(!playonce) {
                playButtonText('<?= __("Afspelen")?>');
            } else {
                playable = false;
                playButtonText('<?= __("Afgespeeld")?>', true);
                Cookies.set(cookieName, '1');
            }
        };

        playButtonText('<?= __("Afspelen")?>');

        $('#audioBtn').click(function() {
            if(playable) {
                if (audio.paused) {
                    audio.play();
                    if (pauseable) {
                        playButtonText('<?= __("Speelt af.. (pauzeer)")?>');
                    } else {
                        playButtonText('<?= __("Speelt af.. (niet pauzeerbaar)")?>', true);
                    }
                    // Indien aan het afspelen en pauzeerbaar
                } else if (pauseable) {
                    audio.pause();
                    playButtonText('<?= __("Afspelen")?>');
                }
            }
        })

    }

    $( document ).ready(function() {
        screenText(pauseable, playonce, playedonce, timesout, canPlay);
        if(playable) {
            $.get( "/answers/download_attachment_sound/<?=$attachment_id?>", function( data ) {
                readyPlayer(data);
            }).fail(function() {
                audioSupportText = document.getElementById("audioSupportText");
                audioSupportText.innerText = '<?= __("Er is een fout opgetreden bij het laden van dit geluidsfragment.")?>';
            });
        } else if(playedonce) {
            playButtonText('<?= __("Afgespeeld")?>', true);
        }
    });
</script>
</body>

</html>
