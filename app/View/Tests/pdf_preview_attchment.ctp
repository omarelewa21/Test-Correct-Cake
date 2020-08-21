<div class="popup-head">Toets in PDF</div>
<?
if(isset($attachmentArray)){
?>

<p>Selecteer de bijlages die u mee wilt printen.</p>

<form action="/tests/pdf_attachment_select/<?= $test_id ?>" id="AttachmentFormDownload" method="post">
    <? foreach($attachmentArray as $attKey => $attVal) : ?>
        
        <? foreach($attVal as $att): ?>
            <input type="checkbox" name="attachment" value="<?=getUUID($att['attachments'], 'get');?>"><?= $att['attachments']['title']; ?>
            <br>
        <? endforeach; ?>        
    <? endforeach; ?>
    
    <div class="popup-footer">
        <a href="#" class="btn grey mt5 mr5 pull-right " onclick="Popup.closeLast();">
            Terug
        </a>
        <a href="#" class="btn highlight mt5 mr5 pull-right show_pdf_attachments closePDF" id="btnAttach" onclick="">
            Print PDF
        </a>
    </div>
</form>

<?
}else{
?>
<iframe src="/tests/pdf_container/<?= $test_id ?>?file=/tests/pdf/<?= $test_id ?>" width="100%" height="550" frameborder="0" class=""></iframe>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right " onclick="Popup.closeLast();">
        Sluiten
    </a>
</div>
<?
}
?>

<script type="text/javascript">
    // MarkO: Chrome wil geen grote PDFs als dara uri. Met deze hack zet ik die eerst om naar een blob
    // https://stackoverflow.com/questions/16245767/creating-a-blob-from-a-base64-string-in-javascript
    var BASE64_MARKER = ';base64,';

    function handleBase64PDF(iframeId, filename, dataURI) {
        var base64Index = dataURI.indexOf(BASE64_MARKER) + BASE64_MARKER.length;
        var base64 = dataURI.substring(base64Index);
        var raw = window.atob(base64);
        var rawLength = raw.length;
        var array = new Uint8Array(new ArrayBuffer(rawLength));

        for(i = 0; i < rawLength; i++) {
            array[i] = raw.charCodeAt(i);
        }
        blob = new Blob([array], {type : 'application/pdf'});

        // IE heeft geen iframe / BLOB of Base64 support.
        isIE11 = !!(window.navigator && window.navigator.msSaveOrOpenBlob);
        if(isIE11) {
            window.navigator.msSaveOrOpenBlob(blob, filename);
            Popup.closeLast();
        } else {
            blobUrl = URL.createObjectURL(blob);
            document.getElementById(iframeId).setAttribute("src",blobUrl);
        }
    }

$(".show_pdf_attachments").on("click",function(){

        var checked = false;
        var checkedValues = [];

        $("#AttachmentFormDownload").find("input:checkbox[name=attachment]:checked").each(function(){
            
            checked = true;
            checkedValues.push($(this).val());

        });

        if(checked === true ) {

            for (var i = checkedValues.length - 1; i >= 0; i--) {
                Popup.load('/tests/load_att_pdf/<?=$test_id?>/'+checkedValues[i]);
                // console.log(checkedValues[i]);
                // Popup.show('<iframe src="/app/tmp/'+checkedValues[i]+'" width="100%" height="550" frameborder="0" class=""></iframe>', 1000);
               // console.log('here');
               // $(".closePDF").on('click',function(){
               //      console.log('yolo');
               //  $(".pdf-iframe").attr('src','/app/tmp/' + checkedValues[i] );
               // });
            };

            Popup.load('/tests/pdf_attachment_select/<?=$test_id?>', 1000);

        } else {
            $(".closePDF").on('click',Popup.closeLast);
            Popup.load('/tests/pdf_attachment_select/<?=$test_id?>', 1000);
        }

    });
    
</script>

