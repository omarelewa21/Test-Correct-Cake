<?
$answer = $rating['answer']['json'];
$answer = json_decode($answer, true);


?>
<div class="cke_editor_overlay_<?=$participantIdentifier;?>" ></div>
<?=$this->Form->input('answer'.$participantIdentifier, ['type' => 'textarea',
                                                        'style' => 'width:99%; height:70px; margin-top:20px;',
                                                        'label' => false,
                                                        'value' => preg_replace('/\<br(\s*)?\/?\>/i', "\n", $answer['value']),
                                                        'tabindex'=>'-1'])
?>
<script type="text/javascript">
    var readOnlyForWsc = true;

    <?php if($spellCheckAvailable){ ?>
    readOnlyForWsc = false;
    <?php } ?>
    var editor<?=$participantIdentifier;?> = CKEDITOR.replace( 'answer<?=$participantIdentifier;?>', {
        toolbar : [ [ ] ],
        readOnly : readOnlyForWsc,
        extraPlugins: 'wordcount,notification,autogrow,ckeditor_wiris',
        removePlugins: 'resize',
        autoGrow_onStartup : true,
        contentsCss : '/ckeditor/rate.css'
    });

    editor<?=$participantIdentifier;?>.on('instanceReady',function(){
        setTimeout(function(){editor<?=$participantIdentifier;?>.execCommand( 'autogrow' )},1000);
        setTimeout(function(){Overlay.overCkeditor4('.cke_editor_overlay_<?=$participantIdentifier;?>',editor<?=$participantIdentifier;?>);},5000);
    });

    <?php if($spellCheckAvailable){ ?>
        editor<?=$participantIdentifier;?>.disableAutoInline = true;
        editor<?=$participantIdentifier;?>.config.removePlugins = 'scayt,wsc';
        editor<?=$participantIdentifier;?>.on('instanceReady', function(event) {
            var editor = event.editor;
            var instance = WEBSPELLCHECKER.init({
                container: editor.window.getFrame() ? editor.window.getFrame().$ : editor.element.$,
                spellcheckLang: '<?=$lang?>',
                localization: 'nl'
            });
            try {
                instance.setLang('<?=$lang?>');
            } finally {
                Overlay.overCkeditor4('.cke_editor_overlay_<?=$participantIdentifier;?>',editor);
            }
        });
        editor<?=$participantIdentifier;?>.on('resize', function(event){
            Overlay.overCkeditor4('.cke_editor_overlay_<?=$participantIdentifier;?>',event.editor);
        });
    <?php } ?>

</script>
<style>
    #cke_answer<?=$participantIdentifier;?>>.cke_inner>.cke_top{
        display: none;
    }
</style>