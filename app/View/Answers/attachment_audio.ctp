
<html>
<head>
<style>
    body {
        background: black;
        margin: 0px;
        color: white;
        font-family: Arial;
        font-size: 20px;
        text-align: center;
    }
    
    a {
        background: blue;
        color: white;
        padding: 6px 15px 6px 15px;
        margin: 2px 2px 2px 2px;
        text-decoration: none;
    }
</style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/soundmanager2-jsmin.js"></script>
</head>
<body>
<br /><br /><br />
Dit geluidsfragment is
<?
$settings = json_decode($attachment['json'], true);

if($settings['pausable'] == '1') {
    echo 'te pauzeren ';
}else{
    echo 'niet te pauzeren';
}

if($settings['play_once'] == '1') {
    echo ' en maar een keer af te spelen ';
}else{
    echo ' en oneindig vaak af te spelen';
}

if(isset($settings['timeout']) && !empty($settings['timeout'])) {
    echo '<br />Na het sluiten van dit scherm heb je ' . $settings['timeout'] . ' sec om de vraag te beantwoorden.';
}

?>

<br />
<br />
<a href="#" id="btnPlay">Afspelen</a>
<? if($settings['pausable'] == '1') { ?>
    <a href="#" id="btnPause" style="display: none;">Pauze</a>
<? } ?>

<script type="text/javascript">

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
        }
        return "";
    }

    <? if($settings['play_once'] == '1') { ?>
        var cookie = getCookie('attachment<?=$attachment_id?>');
            if(cookie != '') {
            $('body').html('<br /><br />Je hebt dit fragment al een keer afgespeeld.');
        }
    <? } ?>


    soundManager.setup({
        url: '/swf/',
        onready: function() {
            window.mySound = soundManager.createSound({
                id: 'aSound',
                url: '/answers/download_attachment/<?=$attachment_id?>'
            });

            $('#btnPlay').click(function() {
                $('#btnPause').show();
                $('#btnPlay').html('Laden..');
                <?
                if($settings['play_once'] == '1') {
                    ?>
                    $(this).hide();
                    $.cookie('attachment<?=$attachment_id?>', 'played');
                    <?
                }
                ?>

                window.mySound.play({
                    whileplaying : function() {
                        $('#btnPlay').html('Afspelen');
                    },

                    whileloading : function() {
                        $('#btnPlay').html('Laden..');
                    }
                });
            });

            $('#btnPause').click(function() {
                $(this).hide();
                $('#btnPlay').show();
                window.mySound.pause();
            });
        }
    });


    (function (factory) {
        if (typeof define === 'function' && define.amd) {
            // AMD (Register as an anonymous module)
            define(['jquery'], factory);
        } else if (typeof exports === 'object') {
            // Node/CommonJS
            module.exports = factory(require('jquery'));
        } else {
            // Browser globals
            factory(jQuery);
        }
    }(function ($) {

        var pluses = /\+/g;

        function encode(s) {
            return config.raw ? s : encodeURIComponent(s);
        }

        function decode(s) {
            return config.raw ? s : decodeURIComponent(s);
        }

        function stringifyCookieValue(value) {
            return encode(config.json ? JSON.stringify(value) : String(value));
        }

        function parseCookieValue(s) {
            if (s.indexOf('"') === 0) {
                // This is a quoted cookie as according to RFC2068, unescape...
                s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
            }

            try {
                // Replace server-side written pluses with spaces.
                // If we can't decode the cookie, ignore it, it's unusable.
                // If we can't parse the cookie, ignore it, it's unusable.
                s = decodeURIComponent(s.replace(pluses, ' '));
                return config.json ? JSON.parse(s) : s;
            } catch(e) {}
        }

        function read(s, converter) {
            var value = config.raw ? s : parseCookieValue(s);
            return $.isFunction(converter) ? converter(value) : value;
        }

        var config = $.cookie = function (key, value, options) {

            // Write

            if (arguments.length > 1 && !$.isFunction(value)) {
                options = $.extend({}, config.defaults, options);

                if (typeof options.expires === 'number') {
                    var days = options.expires, t = options.expires = new Date();
                    t.setMilliseconds(t.getMilliseconds() + days * 864e+5);
                }

                return (document.cookie = [
                    encode(key), '=', stringifyCookieValue(value),
                    options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                    options.path    ? '; path=' + options.path : '',
                    options.domain  ? '; domain=' + options.domain : '',
                    options.secure  ? '; secure' : ''
                ].join(''));
            }

            // Read

            var result = key ? undefined : {},
            // To prevent the for loop in the first place assign an empty array
            // in case there are no cookies at all. Also prevents odd result when
            // calling $.cookie().
                cookies = document.cookie ? document.cookie.split('; ') : [],
                i = 0,
                l = cookies.length;

            for (; i < l; i++) {
                var parts = cookies[i].split('='),
                    name = decode(parts.shift()),
                    cookie = parts.join('=');

                if (key === name) {
                    // If second argument (value) is a function it's a converter...
                    result = read(cookie, value);
                    break;
                }

                // Prevent storing a cookie that we couldn't decode.
                if (!key && (cookie = read(cookie)) !== undefined) {
                    result[name] = cookie;
                }
            }

            return result;
        };

        config.defaults = {};

        $.removeCookie = function (key, options) {
            // Must not alter options, thus extending a fresh object...
            $.cookie(key, '', $.extend({}, options, { expires: -1 }));
            return !$.cookie(key);
        };

    }));
</script>
</body>

</html>