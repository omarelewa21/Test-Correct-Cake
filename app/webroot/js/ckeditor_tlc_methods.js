var CkeditorTlcMethods = {


    initRateOpenLong: function(id,spellcheckAvailable,readOnlyForWsc,lang, prefix = 'answer',showOverlay = true, editor = null) {
        console.log('inside initRateOpenLog');
        console.log('id '+prefix + id);
        if(null == editor) {
            var editor = CKEDITOR.replace(prefix + id, {
                toolbar: [[]],
                readOnly: readOnlyForWsc,
                extraPlugins: 'wordcount,notification,autogrow,ckeditor_wiris',
                removePlugins: 'resize',
                autoGrow_onStartup: true,
                contentsCss: '/ckeditor/rate.css'
            });
        }
console.log('going to wait for instance ready');
        editor.on('instanceReady', function () {
            console.log('instance ready');
            setTimeout(function () {
                editor.execCommand('autogrow');
            }, 1000);
            if(showOverlay) { console.log('show overlay');
                setTimeout(function () {
                    Overlay.overCkeditor4('.cke_editor_overlay_' + id, editor);
                }, 5000);
            }
        });

        if (spellcheckAvailable){console.log('spell check available');
            editor.disableAutoInline = true;
            editor.config.removePlugins = 'scayt,wsc';
            editor.on('instanceReady', function (event) {
                CkeditorTlcMethods.initWsc(editor,id,lang, showOverlay);
            });
            if(showOverlay) {
                editor.on('resize', function (event) {
                    Overlay.overCkeditor4('.cke_editor_overlay_' + id, editor);
                    CkeditorTlcMethods.triggerWsc(editor, id, lang, showOverlay);
                });
            }
        }
    },
    triggerWsc: function(editor,id,lang, showOverlay = true){
        if(editor.element.$.parentNode.getElementsByClassName('wsc_badge').length==0){
            CkeditorTlcMethods.initWsc(editor,id,lang, showOverlay);
        }
    },
    initWsc: function(editor,id,lang, showOverlay = true){
        setTimeout(function () {
            var instance = WEBSPELLCHECKER.init({
                container: editor.window.getFrame() ? editor.window.getFrame().$ : editor.element.$,
                spellcheckLang: lang,
                localization: 'nl'
            });
            try {
                instance.setLang(lang);
            } finally {
                if(showOverlay) {
                    Overlay.overCkeditor4('.cke_editor_overlay_' + id, editor);
                }
            }
        }, 1000);
    }
}