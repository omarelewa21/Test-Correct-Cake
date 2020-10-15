
<div class="block">
    <div class="block-head">Leerdoel analyse</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th colspan="9">Leerdoel</th>
            </tr>
            <tr>
                <td style="width:15px"></td>
                <td style="width:15px"></td>
                <td></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-2px">0</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-3px">5</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-6px">10</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-5px">20</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-5px">40</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-5px">80</span></td>
            </tr>
            <?
            foreach($analysis as $analyse) {
            ?>
            <tr style="cursor:pointer" title="Klik om de details te zien/ te verbergen" onClick="showHideTestTakeAttainmentParticipants(this,'<?=$test_take_id?>','<?=$analyse['uuid']?>');">
                <td><i class="fa fa-caret-right"></i></td>
                <td colspan="2">
                    <?= $analyse['code'] ?><?= $analyse['subcode']?>
                    <?= $analyse['description'] ?>
                </td>
                <td colspan="6">
                    <?php echo AppHelper::getDivForAttainmentAnalysis($analyse,false); ?>
                </td>
            </tr>
            <?
            }
            ?>
            <tr>
                <td style="width:15px"></td>
                <td style="width:15px"></td>
                <td></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-2px">0</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-3px">5</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-6px">10</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-5px">20</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-5px">40</span></td>
                <td style="width:60px;padding-left:0;padding-right:0"><span style="margin-left:-5px">80</span></td>
            </tr>

        </table>
    </div>
</div>

<script>
    function showHideTestTakeAttainmentParticipants(row,testTakeId,attainmentId){
        var tr = jQuery("."+testTakeId+"-"+attainmentId);
        if(tr.length == 0){
            tr = jQuery('<tr><td colspan="9">Een moment de relevante data wordt opgezocht...</td></tr>');
            tr.insertAfter((row));
            TestTake.getTestTakeAttainmentAnalysisDetails(testTakeId,attainmentId,function(data){
                tr.replaceWith(data);
            });
        }
        else if(tr.first().is(':visible')){
            tr.hide();
            jQuery(row).find('i:first').removeClass('fa-caret-down').addClass('fa-caret-right');
        } else {
            tr.show();
            jQuery(row).find('i:first').removeClass('fa-caret-right').addClass('fa-caret-down');
        }
    }
</script>