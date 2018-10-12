<div class="popup-head">Videofragment</div>
<div class="popup-content">

    <table class="table table-striped">
        <tr>
            <th>URL</th>
            <td>
                <?=$this->Form->input('url', ['label' => false])?>
            </td>
        </tr>
    </table>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" onclick="addVideo()">
        Opslaan
    </a>
</div>

<script type="text/javascript">
    function addVideo() {
        <?
        if($type == 'add') {
            ?>
            Attachments.addVideoAdd();
            <?
        }else{
            ?>
            Attachments.addVideoEdit('<?=$owner?>', '<?=$owner_id?>', '<?=$id?>');
            <?
        }
        ?>
    }
</script>