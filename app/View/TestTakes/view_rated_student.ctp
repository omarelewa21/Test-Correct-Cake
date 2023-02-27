
<div id="buttons">
    <?= $this->element('back-button'); ?>
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

                <? if(!$take['show_grades']) {?>
                    <td title="<?= __("Bij formatieve toetsen staan inzicht en voortgang centraal. Daarom zie je hier geen cijfer.") ?>">
                        <?= __('N.v.t.') ?>
                    </td>
                <?}elseif($rating){?>
                    <td>
                        <?= $rating ?>
                    </td>
                <?}else{?>
                    <td>
                        <i class="fa fa-clock-o" aria-hidden="true" title="<?= __('Bezig met nakijken & becijferen. Cijfer nog niet beschikbaar') ?>"></i>
                    </td>
                <?}?>
                
                <th><?= __("Type")?></th>
                <td><?=$take['retake'] == 0 ? __("Reguliere toets") : __("Inhaal toets")?></td>
            </tr>
        </table>
    </div>
</div>

