<div class="popup-head">Verplaats naar groep</div>
<div class="popup-content">
    <table class="table table-striped">
        <tr>
            <th>Groep</th>
            <th></th>
        </tr>
        <?
        foreach($groups as $id => $name) {
            ?>
            <tr>
                <td><?=$name?></td>
                <td class="nopadding">
                    <a href="#" class="btn white pull-right" onclick="Questions.moveToGroup(<?= $id ?>);">
                        <span class="fa fa-mail-forward"></span>
                    </a>
                </td>
            </tr>
            <?
        }
        ?>
    </table>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
</div>