
<div class="block" style="<?= $extra_style ? $extra_style : ''?>">
    <div class="block-head">Leerdoel analyse</div>
    <div class="block-content">
        <table class="table table-striped">
            <tr>
                <th colspan="3">Leerdoel</th>
                <th colspan="6">Aantal vragen</th>
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
            <tr>
                <td colspan="3"></td>
                <td colspan="2" valign="top"><small>Legenda:</small></td>
                <td colspan="4">
                    <small><span style="display:inline-block;width:10px;height:10px;background:#ff6666;border:1px solid #888;"></span> P-waarde kleinder dan 55 </small><br/>
                    <small><span style="display:inline-block;width:10px;height:10px;background:#ffff33;border:1px solid #888;"></span> P-waarde tussen 55 en en 65</small><br/>
                    <small><span style="display:inline-block;width:10px;height:10px;background:#85e085;border:1px solid #888;"></span> P-waarde groter dan 65</small>
                </td>
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