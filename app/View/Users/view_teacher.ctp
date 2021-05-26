<div id="buttons">
    <?php if((bool) $user['demo'] !== true){?>
    <a href="#" class="btn white" onclick="Popup.load('/users/edit/<?=getUUID($user, 'get');?>', 400);">
        <span class="fa fa-edit mr5"></span>
        <?= __("Wijzigen")?>
    </a>
    <?php } ?>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
</div>

<h1>
    <?=$user['name_first']?>
    <?=$user['name_suffix']?>
    <?=$user['name']?>
</h1>

<div class="block">
    <div class="block-head"><?= __("Informatie")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="10%"><?= __("Naam")?></th>
                <td width="23%">
                    <?=$user['name_first']?>
                    <?=$user['name_suffix']?>
                    <?=$user['name']?>
                </td>
                <th width="10%"><?= __("Vraag items")?></th>
                <td width="23%"><?=$user['count_questions']?></td>
            </tr>
            <tr>
                <th width="10%"><?= __("Toets items")?></th>
                <td width="23%"><?=$user['count_tests']?></td>
                <th><?= __("Afgenomen toetsen")?></th>
                <td><?=$user['count_tests_taken']?></td>
            </tr>
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head"><?= __("Klassen en vakken")?></div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th><?= __("Vak")?></th>
                <th><?= __("Klas")?></th>
                <th><?= __("Leerjaar")?></th>
            </tr>
            <?
            foreach($user['own_teachers'] as $teacher) {
                ?>
                <tr>
                    <td><?=$teacher['subject']['name']?></td>
                    <td><?=$teacher['school_class']['name']?></td>
                    <td><?=$school_years[$teacher['school_class']['school_year_id']]?></td>

                </tr>
                <?
            }
            ?>
        </table>
    </div>
</div>