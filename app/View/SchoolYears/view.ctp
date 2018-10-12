<div id="buttons">
    <a href="#" class="btn white" onclick="Popup.load('/school_years/edit/<?=$school_year['id']?>', 400);">
        <span class="fa fa-edit mr5"></span>
        Wijzigen
    </a>
    <a href="#" class="btn white" onclick="Organisation.delete(<?=$school_year['id']?>);">
        <span class="fa fa-remove mr5"></span>
        Delete
    </a>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1><?=$school_year['year']?></h1>


<div class="block">
    <div class="block-head">Periodes</div>
    <div class="block-content">
        <table class="table table-striped">
            <table class="table table-striped">
                <tr>
                    <th>Naam</th>
                    <th>Datum van</th>
                    <th>Datum tot</th>
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
                            <a href="#" class="btn white pull-right dropblock-owner dropblock-left" id="Period_<?=$period['id']?>">
                                <span class="fa fa-list-ul"></span>
                            </a>

                            <div class="dropblock blur-close" for="Period_<?=$period['id']?>">
                                <a href="#" class="btn highlight white" onclick="Popup.load('/school_years/edit_period/<?=$period['id']?>', 400);">
                                    <span class="fa fa-edit mr5"></span>
                                    Wijzigen
                                </a>
                                <a href="#" class="btn highlight white" onclick="Period.delete(<?=$period['id']?>);">
                                    <span class="fa fa-remove mr5"></span>
                                    Delete
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
                <a href="#" class="btn highlight inline-block" onclick="Popup.load('/school_years/add_period/<?=$school_year['id']?>', 400);">
                    <span class="icon icon-plus"></span>
                    Nieuwe periode
                </a>
            </center>
        </table>
    </div>
</div>