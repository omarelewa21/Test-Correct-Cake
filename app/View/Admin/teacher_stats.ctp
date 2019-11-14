

<h1>Statistieken</h1>

<div class="block ">
    <div class="block-head" title="Nog geen toets aangemaakt"><?php echo count($data->nonUsers)?> Non users</div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th sortkey="email" width="30">E-mail</th>
                <th sortkey="School" width="30">School</th>
                <th width="30">Aantal afgenomen toetsen</th>
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
    <div class="block-head" title="afgelopen 6 maanden een toets afgenomen maar laatste 2 maanden niet"><?php echo count($data->smallUsers)?> Small users</div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th sortkey="email" width="30">E-mail</th>
                <th sortkey="School" width="30">School</th>
                <th width="30">Aantal afgenomen toetsen</th>
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
    <div class="block-head" title="Afgelopen 2 maanden 1 toets afgenomen"><?php echo count($data->mediumUsers)?> Medium users</div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th sortkey="email" width="30">E-mail</th>
                <th sortkey="School" width="30">School</th>
                <th width="30">Aantal afgenomen toetsen</th>
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
    <div class="block-head" title="Afgelopen maand tenminste 1 test afgenomen"><?php echo count($data->heavyUsers)?> Heavy users</div>
    <div class="block-content" id="testsContainter">
        <table class="table table-striped" id="testsTable">
            <thead>
            <tr>
                <th sortkey="email" width="30">E-mail</th>
                <th sortkey="School" width="30">School</th>
                <th width="30">Aantal afgenomen toetsen</th>
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
