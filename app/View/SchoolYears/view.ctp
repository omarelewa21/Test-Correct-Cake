<div id="buttons">
    <a href="#" class="btn white" onclick="Popup.load('/school_years/edit/<?=getUUID($school_year, 'get');?>', 400);">
        <span class="fa fa-edit mr5"></span>
        <?= __("Wijzigen")?>
    </a>
    <a href="#" class="btn white" onclick="SchoolYear.delete(<?=getUUID($school_year, 'getQuoted');?>, true);">
        <span class="fa fa-remove mr5"></span>
        <?= __("Verwijderen")?>
    </a>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
</div>

<h1><?=$school_year['year']?></h1>


<div class="block">
    <div class="block-head"><?= __("Periodes")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <table class="table table-striped">
                <tr>
                    <th><?= __("Naam")?></th>
                    <th><?= __("Datum van")?></th>
                    <th><?= __("Datum tot")?></th>
                    <th></th>
                </tr>
                <?
                foreach($school_year['periods'] as $period) {
                    ?>
                    <tr>
                        <td><?=$period['name']?></td>
                        <td><?=date('d-m-Y', strtotime($period['start_date']))?></td>
                        <td><?=date('d-m-Y', strtotime($period['end_date']))?></td>
                        <td class="nopadding">
                            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="Period_<?=getUUID($period, 'get');?>">
                                <span class="fa fa-list-ul"></span>
                            </a>

                            <div class="dropblock blur-close" for="Period_<?=getUUID($period, 'get');?>">
                                <a href="#" class="btn highlight white" onclick="Popup.load('/school_years/edit_period/<?=getUUID($period, 'get');?>', 400);">
                                    <span class="fa fa-edit mr5"></span>
                                    <?= __("Wijzigen")?>
                                </a>
                                <a href="#" class="btn highlight white" onclick="Period.delete(<?=getUUID($period, 'getQuoted');?>);">
                                    <span class="fa fa-remove mr5"></span>
                                    <?= __("Verwijderen")?>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?
                }
                ?>
            </table>

            <br />

            <center>
                <a href="#" class="btn highlight inline-block" onclick="Popup.load('/school_years/add_period/<?=getUUID($school_year, 'get');?>', 400);">
                    <span class="icon icon-plus"></span>
                    <?= __("Nieuwe periode")?>
                </a>
            </center>
        </table>
    </div>
</div>