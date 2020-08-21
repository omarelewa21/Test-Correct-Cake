<div id="buttons">
    <a href="#" class="btn highlight mr2" onclick="TestTake.saveNormalization('<?=$take_id?>');">
        <span class="fa fa-check mr5"></span>
        Normering opslaan
    </a>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        Terug
    </a>
</div>

<h1>Normeren</h1>

<?=$this->Form->create('TestTake')?>

<?
$score = 0;
foreach($test_take['questions'] as $question) {
    $score += $question['score'];
}
?>

    <div class="block">
        <div class="block-head">Normering</div>
        <div class="block-content">
            <table class="table table-striped">
                <tr>
                    <td width="20"><input name="data[TestTake][type]" type="radio" value="1" checked onclick="TestTake.normalizationPreview('<?=$take_id?>');" /></td>
                    <th width="300">
                        Goed per punt
                    </th>
                    <td width="150">Goed per punt</td>
                    <td colspan="4">
                        <input type="text" name="data[TestTake][value_1]" value="1" style="width:50px;" onkeyup="setNotmalizationType(1)" />
                    </td>
                </tr>
                <tr>
                    <td width="20"><input name="data[TestTake][type]" type="radio" value="4" onclick="TestTake.normalizationPreview('<?=$take_id?>');" /></td>
                    <th width="300">
                        Fouten per punt
                    </th>
                    <td width="150">Fouten per punt</td>
                    <td colspan="4">
                        <input type="text" name="data[TestTake][value_4]" value="1" style="width:50px;" onkeyup="setNotmalizationType(4)"/>
                    </td>
                </tr>
                <tr>
                    <td><input name="data[TestTake][type]" type="radio" value="2" onclick="TestTake.normalizationPreview('<?=$take_id?>');" /></td>
                    <th>
                        Normeren o.b.v. gemiddeld cijfer
                    </th>
                    <td width="150">Gemiddeld cijfer</td>
                    <td colspan="4">
                        <input type="text" name="data[TestTake][value_2]" value="7.5" style="width:50px;" onkeyup="setNotmalizationType(2)" />
                    </td>
                </tr>
                <tr>
                    <td><input name="data[TestTake][type]" type="radio" value="3" onclick="TestTake.normalizationPreview('<?=$take_id?>');" /></td>
                    <th>
                        Normeren o.b.v. n-term
                    </th>
                    <td width="60">
                        N-term:
                    </td>
                    <td colspan="4" width="100">
                        <input type="text" name="data[TestTake][value_3]" value="1" style="width:50px;" onkeyup="setNotmalizationType(3)" />
                    </td>
                </tr>
                <tr>
                    <td><input name="data[TestTake][type]" type="radio" value="5" onclick="TestTake.normalizationPreview('<?=$take_id?>');" /></td>
                    <th>
                        Normeren o.b.v. cesuur
                    </th>
                    <td width="60">
                        N-term:
                    </td>
                    <td width="100">
                        <input type="text" name="data[TestTake][value_5]" value="1" style="width:50px;" onkeyup="setNotmalizationType(5)" />
                    </td>
                    <td width="60">
                        Cesuur:
                    </td>
                    <td width="100">
                        <input type="text" name="data[TestTake][value_6]" value="50" style="width:50px;" onkeyup="setNotmalizationType(5)" />
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="block">
        <div class="block-head">Voorbeeld</div>
        <div class="block-content" id="divPreview"></div>
    </div>


    <div class="block">
        <div class="block-head">Vragen</div>
        <div class="block-content">
            <table class="table table-striped">
                <tr>
                    <th>Vragen</th>
                    <th>Beoordelingen</th>
                    <th>Gem. score</th>
                    <th>Max score</th>
                    <th>Overslaan</th>
                </tr>
                <?
                foreach($test_take['questions'] as $question_id => $question) {
                    ?>
                    <tr>
                        <td><?=substr(strip_tags($question['question']), 0, 100)?></td>
                        <td><?=isset($question['ratings']) ? $question['ratings'] : 0?></td>
                        <td>
                            <?
                            if(isset($question['ratings']) && $question['ratings'] > 0) {
                                echo round($question['total_score'] / $question['ratings']);
                            }else{
                                echo '-';
                            }
                            ?>
                        </td>
                        <td><?=$question['score']?></td>
                        <td>
                            <input name="data[Question][<?=$question_id?>]" value="1" type="checkbox" onchange="TestTake.normalizationPreview('<?=$take_id?>');" />
                        </td>
                    </tr>
                    <?
                }
                ?>
            </table>
        </div>
    </div>
<script type="text/javascript">
    $('input').keyup(function() {
       clearTimeout(window.normalizeTimeout);
        window.normalizeTimeout = setTimeout(function() {
            TestTake.normalizationPreview('<?=$take_id?>');
        }, 1000);
    });

    function setNotmalizationType(type) {
        $('input:radio[name="data[TestTake][type]"][value="' + type + '"]').click();
    }
</script>

<?=$this->Form->end();?>