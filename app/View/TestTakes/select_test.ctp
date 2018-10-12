<div class="popup-head">Selecteer toets</div>
<div class="popup-content">
    <div id="testsContainter" style="height:300px; overflow: auto;">
        <table class="table table-striped" id="TestTakeTestTable">
            <thead>
            <tr>
                <th width="30">Afk.</th>
                <th>Titel</th>
                <th>Vak</th>
                <th width="130">Leerjaar</th>
                <th width="130">Auteur</th>
                <th width="90">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#TestTakeTestTable').tablefy({
                'source' : '/test_takes/select_test_list',
                'filters' : $('#testsFilter'),
                'container' : $('#testsContainter')
            });
        </script>
    </div>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Annuleer
    </a>
</div>