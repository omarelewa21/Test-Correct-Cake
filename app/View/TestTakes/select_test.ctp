<div class="popup-head"><?= __("Selecteer toets")?></div>
<div class="popup-content">
    <div id="selectableTestsContainter" style="height:300px; overflow: auto;">
        <table class="table table-striped" id="TestTakeTestTable">
            <thead>
            <tr>
                <th width="30"><?= __("Afk")?>.</th>
                <th><?= __("Titel")?></th>
                <th><?= __("Vak")?></th>
                <th width="130"><?= __("Leerjaar")?></th>
                <th width="130"><?= __("Auteur")?></th>
                <th width="90">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#TestTakeTestTable').tablefy({
                'source' : '/test_takes/select_test_list',
                'filters' : $('#testsFilter'),
                'container' : $('#selectableTestsContainter')
            });
        </script>
    </div>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
    <?= __("Annuleer")?>
    </a>
</div>