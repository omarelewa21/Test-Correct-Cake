
<div id="attachmentContainer" style="display:none">
    <div id="attachmentButtonContainer">
        <a href="#" class="btn red" id="btnAttachmentFrame" style="display:inline-block;position:relative;float:right">
            <span class="fa fa-remove"></span>
        </a>

        <a href="#" class="btn green mr8" id="btnAttachmentFrameMove" style="display:inline-block;position:relative;float:right">
            <span class="fa fa-arrows"></span>
        </a>
    </div>


    <iframe id="attachmentFrame" frameborder="0"></iframe>
</div>
<div id="attachmentFade"></div>



<style>
    #attachmentButtonContainer {
        position : absolute;
        right    : 0;
        top      : 0;
        width    : 120px;
        height   : 40px;
    }
    #attachmentButtonContainer a {
        z-index : 11600;
    }
    #attachmentFrame #presentationMode {
        display : none !important;
    }
    #attachmentContainer #btnAttachmentFrame {
        right   : auto;
        top     : auto;
        line-height: initial;
    }
    #attachmentContainer .green {
        background-color: #3D9D36;
        color : white;
    }
    #attachmentContainer .ui-resizable-handle.ui-resizable-se.ui-icon.ui-icon-gripsmall-diagonal-se {
        z-index : 11600 !important;
        color   : red;
    }
    #attachmentContainer {
        position : absolute;
        border : 0px solid #eeeeee;
        background-color: white;
        -webkit-box-shadow: 3px 3px 24px 0px rgba(0,0,0,0.75);
        -moz-box-shadow: 3px 3px 24px 0px rgba(0,0,0,0.75);
        box-shadow: 3px 3px 24px 0px rgba(0,0,0,0.75);
    }
    #attachmentFrame {
        display: block;
        position: relative;
    }
    #attachmentContainer {
        position:absolute;
        width: 80%;
        height:600px;
    }
    #attachmentContainer.draggable {
        position:absolute;
        width: 500px;
        height:350px;
    }
    button#browsealoud-button--launchpad {
        display:none !important;
    }
</style>

<script>
    jQuery("#btnAttachmentFrameMove").on('mouseenter', function(e){
        jQuery("#attachmentContainer").draggable({handle:'#btnAttachmentFrameMove'});
    });
</script>