<?php

foreach($participants as $participant) {
            ?>
    <tr class="<?=$test_take_id?>-<?=$attainment_id?>">
        <td></td>
        <td></td>
        <td>
            <?= $participant['name_first'] ?>
            <?= $participant['name_suffix'] ?>
            <?= $participant['name'] ?>
        </td>
        <td colspan="6">
            <?= AppHelper::getDivForAttainmentAnalysis($participant,true); ?>
        </td>
    </tr>
            <?php
}
