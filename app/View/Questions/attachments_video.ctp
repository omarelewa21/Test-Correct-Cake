<div class="popup-head"><?= __("Videofragment")?></div>
<div class="popup-content">

    <table class="table table-striped">
        <tr>
            <th><?= __("URL")?></th>
            <td>
                <?=$this->Form->input('url', ['label' => false])?>
            </td>
        </tr>
    </table>
</div>
<div class="popup-footer">
    <a href="javascript:void(0);" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
    <a href="javascript:void(0);" class="btn highlight mt5 mr5 pull-right" onclick="addVideo()">
    <?= __("Opslaan")?>
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