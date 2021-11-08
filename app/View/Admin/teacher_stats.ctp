
<h1><?= __("Statistieken")?></h1>

<div class="block ">
    <div class="block-head" title='<?= __("Nog geen toets aangemaak")?>'><?php echo count($data->nonUsers)?> <?= __("Non users")?></div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th sortkey="email" width="30"><?= __("E-mail")?></th>
                <th sortkey="School" width="30"><?= __("School")?></th>
                <th width="30"><?= __("Aantal afgenomen toetsen")?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data->nonUsers as $user){?>
                <tr>
                    <td><?php echo $user['email']?></td>
                    <td><?php echo $user['school']?></td>
                    <td style="text-align:right;"><?php echo $user['totalTestTakes']?></td>
                </tr>
            </tbody>
            <?php  } ?>
        </table>

    </div>
    <div class="block-footer"></div>
</div>

<div class="block ">
    <div class="block-head" title='<?= __("afgelopen 6 maanden een toets afgenomen maar laatste 2 maanden niet")?>'><?php echo count($data->smallUsers)?> <?= __("Small users")?></div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th sortkey="email" width="30"><?= __("E-mail")?></th>
                <th sortkey="School" width="30"><?= __("School")?></th>
                <th width="30"><?= __("Aantal afgenomen toetsen")?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data->smallUsers as $user){?>
            <tr>
                <td><?php echo $user['email']?></td>
                <td><?php echo $user['school']?></td>
                <td style="text-align:right;"><?php echo $user['totalTestTakes']?></td>
            </tr>
            </tbody>
            <?php  } ?>
        </table>

    </div>
    <div class="block-footer"></div>
</div>

<div class="block ">
    <div class="block-head" title='<?= __("Afgelopen 2 maanden 1 toets afgenomen")?>'><?php echo count($data->mediumUsers)?> <?= __("Medium users")?></div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th sortkey="email" width="30"><?= __("E-mail")?></th>
                <th sortkey="School" width="30"><?= __("School")?></th>
                <th width="30"><?= __("Aantal afgenomen toetsen")?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data->mediumUsers as $user){?>
            <tr>
                <td><?php echo $user['email']?></td>
                <td><?php echo $user['school']?></td>
                <td style="text-align:right;"><?php echo $user['totalTestTakes']?></td>
            </tr>
            </tbody>
            <?php  } ?>
        </table>

    </div>
    <div class="block-footer"></div>
</div>

<div class="block ">
    <div class="block-head" title='<?= __("Afgelopen maand tenminste 1 test afgenomen")?>'><?php echo count($data->heavyUsers)?> <?= __("Heavy users")?></div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th sortkey="email" width="30"><?= __("E-mail")?></th>
                <th sortkey="School" width="30"><?= __("School")?></th>
                <th width="30"><?= __("Aantal afgenomen toetsen")?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data->heavyUsers as $user){?>
            <tr>
                <td><?php echo $user['email']?></td>
                <td><?php echo $user['school']?></td>
                <td style="text-align:right;"><?php echo $user['totalTestTakes']?></td>
            </tr>
            </tbody>
            <?php  } ?>
        </table>

    </div>
    <div class="block-footer"></div>
</div>
