<div id="buttons">
    <?php if((bool) $user['demo'] !== true){?>
    <a href="#" class="btn white" onclick="Popup.load('/users/edit/<?=$user['id']?>', 400);">
        <span class="fa fa-edit mr5"></span>
        Wijzigen
    </a>
    <?php } ?>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1>
    <?=$user['name_first']?>
    <?=$user['name_suffix']?>
    <?=$user['name']?>
</h1>

<div class="block">
    <div class="block-head">Informatie</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th width="10%">Naam</th>
                <td width="23%">
                    <?=$user['name_first']?>
                    <?=$user['name_suffix']?>
                    <?=$user['name']?>
                </td>
                <th width="10%">Vraag items</th>
                <td width="23%"><?=$user['count_questions']?></td>
            </tr>
            <tr>
                <th width="10%">Toets items</th>
                <td width="23%"><?=$user['count_tests']?></td>
                <th>Afgenomen toetsen</th>
                <td><?=$user['count_tests_taken']?></td>
            </tr>
        </table>
    </div>
</div>

<div class="block">
    <div class="block-head">Klassen en vakken</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th>Vak</th>
                <th>Klas</th>
                <th>Leerjaar</th>
            </tr>
            <?
            foreach($user['teacher'] as $teacher) {
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