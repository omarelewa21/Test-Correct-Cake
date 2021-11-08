<?
if(empty($results['test_participants'])) {
    echo __("<center>Er konden geen cijfers berekend worden op basis van de gekozen normering.</center>");
}else{
    ?>
    <table class="table table-striped">
        <div style="display: none"><input type="hidden" value="<?=$currentIndex?>" id="currentIndex"></div>
        <tr>
            <th><?= __("Student")?></th>
            <th><?= __("Cijfer")?></th>
        </tr>
        <?
        $resultTotal = 0;
        $resultCount = 0;
        foreach($results['test_participants'] as $participant) {

            if(!empty($participant['rating'])) {
                $resultTotal += $participant['rating'];
                $resultCount ++;
            }

            ?>
            <tr>
                <td>
                    <?=$participant['user']['name_first']?>
                    <?=$participant['user']['name_suffix']?>
                    <?=$participant['user']['name']?>
                </td>
                <Td>
                    <?=empty($participant['rating']) ? ' - ' : $participant['rating']?>
                </Td>
            </tr>
            <?
        }
        ?>
        <tr>
            <th style="text-align: right;"><?= __("Gemiddelde")?></th>
            <th>
                <? if($resultCount > 0) { ?>
                    <?=round($resultTotal / $resultCount, 1)?>
                <? }else{ ?>
                    -
                <? } ?>
            </th>
        </tr>
    </table>
    <?
}
?>