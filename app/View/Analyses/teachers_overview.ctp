<h1><?= __("Docenten")?></h1>
<div class="block autoheight">
    <div class="block-head"><?= __("Docenten")?></div>
    <div class="block-content" id="teachersContainer">
        <table class="table" id="teachersTable" cellpadding="1">
            <tr>
                <th><?= __("Voornaam")?></th>
                <th><?= __("Achternaam")?></th>
                <th><?= __("Afkorting")?></th>
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