<div class="popup-head">Selecteer geplande toets</div>
<div class="popup-content">
    <div id="testsContainter" style="height:300px; overflow: auto;">
        <table class="table table-striped" id="TestTakeTestTable">
            <thead>
            <tr>
                <th>Toets</th>
                <th width="200">Ingepland door</th>
                <th width="140">Afname-datum</th>
                <th width="60">Weging</th>
                <th width="40">&nbsp;</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#TestTakeTestTable').tablefy({
                'source' : '/test_takes/select_test_take_list',
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