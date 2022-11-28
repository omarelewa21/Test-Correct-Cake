
<div id="buttons">
    <a href="#" class="btn white mr2"
        <? if (is_null($return_route)) { ?>
            onclick="Navigation.back();"
        <? } else { ?>
       onclick="User.goToLaravel('<?= $return_route ?>')"
    <? } ?>
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
</div>

<h1><?= __("Becijferde toets")?></h1>
<div class="block">
    <div class="block-head"><?= __("Toetsinformatie")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="25%"><?= __("Toets")?></th>
                <td width="25%"><?=$take['test']['name']?></td>
                <th width="25%"><?= __("Afgenomen")?></th>
                <td width="25%"><?=date('d-m-Y', strtotime($take['time_start']))?></td>
            </tr>
            <tr>
                <th><?= __("Vak")?></th>
                <td>
                    <?=$take['test']['subject']['name']?>
                </td>
                <th><?= __("Docent")?></th>
                <td>
                    <?=$take['user']['name_first']?>
                    <?=$take['user']['name_suffix']?>
                    <?=$take['user']['name']?>
                </td>
            </tr>
            <tr>
                <th><?= __("Cijfer")?></th>
                <td>
                    <?=$rating ?>
                </td>
                <th><?= __("Type")?></th>
                <td><?=$take['retake'] == 0 ? __("Reguliere toets") : __("Inhaal toets")?></td>
            </tr>
        </table>
    </div>
</div>

