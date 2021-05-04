
<div id="buttons">
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1>Becijferde toets</h1>
<div class="block">
    <div class="block-head">Toetsinformatie</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="25%">Toets</th>
                <td width="25%"><?=$take['test']['name']?></td>
                <th width="25%">Afgenomen</th>
                <td width="25%"><?=date('d-m-Y', strtotime($take['time_start']))?></td>
            </tr>
            <tr>
                <th>Vak</th>
                <td>
                    <?=$take['test']['subject']['name']?>
                </td>
                <th>Docent</th>
                <td>
                    <?=$take['user']['name_first']?>
                    <?=$take['user']['name_suffix']?>
                    <?=$take['user']['name']?>
                </td>
            </tr>
            <tr>
                <th>Cijfer</th>
                <td>
                    <?=$rating ?>
                </td>
                <td>Type</td>
                <td><?=$take['retake'] == 0 ? 'Reguliere toets' : 'Inhaal toets'?></td>
            </tr>
        </table>
    </div>
</div>

