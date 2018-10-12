<table width="100%" class="table table-striped">
    <?
    foreach($question['question']['p_value'] as $pvalue) {
        ?>
        <tr>
            <th width="200">P-waarde <?=$pvalue['education_level_year']?> <?=$pvalue['education_level']['name']?></th>
            <td><?=number_format($pvalue['p_value'], 2);?></td>
            <td><?=$pvalue['p_value_count']?> keer afgenomen</td>
            <td>
                <?

                $error = '';

                if($pvalue['p_value'] > 0.9) {
                    $error = 'De vraag is te makkelijk voor dit niveau.';
                }elseif($pvalue['p_value'] < 0.2) {
                    $error = 'De vraag is te moeilijk voor dit niveau. (controleer de vraag op eventuele vormfouten als u van mening bent dat de vraag geschikt is voor dit niveau)';
                }

                if(!empty($error)) {
                    ?>
                    <span class="fa fa-warning" onclick="Questions.showPvalueError('<?=$error?>');" style="cursor:pointer; color:orange"></span>
                    <?
                }
                ?>
            </td>
        </tr>
        <?
    }
    ?>
    <tr>
        <th>Uniek ID</th>
        <td colspan="3"><?=$question['question']['id']?></td>
    </tr>
    <tr>
        <th valign="top">Auteurs</th>
        <td colspan="3">
            <? foreach($question['question']['authors'] as $author) {
                ?><?= $author['name_first'].' '. $author['name_suffix'] . ' '. $author['name'] ?><br />
            <? } ?>
        </td>
    </tr>
</table>