<div id="buttons">
    <a href="#" class="btn white" onclick="Popup.load('/school_years/add', 400);">
        <span class="fa fa-plus mr5"></span>
        <?= __("Nieuwe Schooljaar")?>
    </a>
</div>

<h1><?= __("Schooljaar")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Schooljaar")?></div>
    <div class="block-content" id="SchoolYearsContainer">
        <table class="table table-striped" id="SchoolYearsTable">
            <thead>
            <tr>
                <th><?= __("Schooljaar")?></th>
                <th><?= __("Schoollocaties")?></th>
                <th></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script type="text/javascript">
            $('#SchoolYearsTable').tablefy({
                'source' : '/school_years/load',
                'filters' : null,
                'container' : $('#SchoolYearsContainer')
            });
        </script>
    </div>
    <div class="block-footer"></div>
</div>