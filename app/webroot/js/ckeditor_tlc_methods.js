var CkeditorTlcMethods = {


    initRateOpenLong: function(id,spellcheckAvailable,readOnlyForWsc,lang, prefix = 'answer') {
        var editor = CKEDITOR.replace(prefix + id, {
            toolbar: [[]],
            readOnly: readOnlyForWsc,
            extraPlugins: 'wordcount,notification,autogrow,ckeditor_wiris',
            removePlugins: 'resize',
            autoGrow_onStartup: true,
            contentsCss: '/ckeditor/rate.css'
        });

        editor.on('instanceReady', function () {
            setTimeout(function () {
                editor.execCommand('autogrow');
            }, 1000);
            setTimeout(function () {
                Overlay.overCkeditor4('.cke_editor_overlay_'+id, editor);
            }, 5000);
        });

        if (spellcheckAvailable){
            editor.disableAutoInline = true;
            editor.config.removePlugins = 'scayt,wsc';
            editor.on('instanceReady', function (event) {
                CkeditorTlcMethods.initWsc(editor,id,lang);
            });
            editor.on('resize', function (event) {
                Overlay.overCkeditor4('.cke_editor_overlay_'+id, editor);
                CkeditorTlcMethods.triggerWsc(editor,id,lang);
            });
        }
    },
    triggerWsc: function(editor,id,lang){
        if(editor.element.$.parentNode.getElementsByClassName('wsc_badge').length==0){
            CkeditorTlcMethods.initWsc(editor,id,lang);
        }
    },
    initWsc: function(editor,id,lang){
        setTimeout(function () {
            var instance = WEBSPELLCHECKER.init({
                container: editor.window.getFrame() ? editor.window.getFrame().$ : editor.element.$,
                spellcheckLang: lang,
                localization: 'nl'
            });
            try {
                instance.setLang(lang);
            } finally {
                Overlay.overCkeditor4('.cke_editor_overlay_'+id, editor);
            }
        }, 1000);
    }
}