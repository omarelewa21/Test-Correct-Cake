<div class="popup-head"><?= __("Studenten toevoegen")?></div>
<div class="popup-content">
    <div id="classesContainer" style="height:300px; overflow: auto;">
        <table class="table table-striped" id="classesTable">
            <thead>
                <tr>
                    <th><?= __("Klas")?></th>
                    <th width="315"></th>
                </tr>
            </thead>
            <tbody>
            <?
            foreach($classes as $class_id => $name) {
                ?>
                <tr>
                    <td><?=$name?></td>
                    <td class="nopadding">
                        <a href="#" class="btn highlight pull-right ml2 mt1 mb1" onclick="TestTake.addClass(<?=$class_id?>);">
                            <span class="fa fa-plus"></span>
                            <?= __("Voeg klas toe")?>
                        </a>
                        <a href="#" class="btn highlight pull-right  mt1 mb1" onclick="TestTake.loadClassParticipants(<?=$class_id?>)">
                            <span class="fa fa-users"></span>
                            <?= __("Selecteer Studenten")?>
                        </a>
                    </td>
                </tr>
                <?
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
</div>
