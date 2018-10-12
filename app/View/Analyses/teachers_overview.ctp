<h1>Docenten</h1>
<div class="block autoheight">
    <div class="block-head">Docenten</div>
    <div class="block-content" id="teachersContainer">
        <table class="table" id="teachersTable" cellpadding="1">
            <tr>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>Afkorting</th>
                <th width="30"></th>
            </tr>
        </table>
    </div>
</div>

<script type="text/javascript">
    $('#teachersTable').tablefy({
        'source' : '/analyses/load_teachers',
        'container' : $('#teachersContainer'),
        'hideEmpty' : true
    });
</script>