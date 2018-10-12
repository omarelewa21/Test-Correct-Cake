<h1>Berichten</h1>

<div class="block autoheight">
    <div class="block-head">Berichten</div>
    <div class="block-content" id="messagesContainter">
        <table class="table table-striped" id="messagesTable">
            <thead>
            <tr>
                <th width="30"></th>
                <th>Onderwerp</th>
                <th width="70"></th>
                <th width="250"></th>
                <th width="130">Datum/tijd</th>
                <th width="75"></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#messagesTable').tablefy({
                'source' : '/messages/load',
                'filters' : $('#MessageIndexForm'),
                'container' : $('#messagesContainter')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>

<script type="text/javascript">
    $('#messages .counter').fadeOut();
</script>