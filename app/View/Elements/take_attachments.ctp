<div style=" margin-left: 30px; float: right;" id="divAttachments">
    <?
    if($question['note_type'] == 'TEXT') {
        ?>
        <a href="#" class="btn highlight btn-attachment" onclick="Answer.notePad('<?=getUUID($question, 'get');?>');" style="width:260px; text-align: center;">
        <?= __("Tekstblok")?>
        </a>
        <?
    }elseif($question['note_type'] == 'DRAWING') {
        ?>
        <a href="#" class="btn highlight btn-attachment" onclick="Answer.drawingPad('<?=getUUID($question, 'get');?>');" style="width:260px; text-align: center;">
        <?= __("Tekenblok")?>
        </a>
        <?
    }
    ?>

    <? if(count($question['attachments']) > 0) { ?>
        <div style="width:250px; background: #294409; padding:20px; ">
            <div style="color: white; text-align: center; font-size: 22px; margin-bottom: 10px;">
            <?= __("Bronnen")?>
            </div>
            <?
            $i = 0;
            foreach($question['attachments'] as $attachment) {
                $draggable = false;
                $timeout = "";

                if(isset($attachment['json'])) {
                    $settings = json_decode($attachment['json'], true);
                    if(is_array($settings) && count($settings) > 0){
                        $draggable = true;
                    }
                    if(isset($settings['timeout']) && !empty($settings['timeout'])) {
                        $timeout = $settings['timeout'];
                    }
                }

                $i++;
                ?>
                <a href="#" class="btn white btn-attachment" style="margin-bottom: 2px;" onclick="Answer.loadAttachment('<?=getUUID($attachment, 'get');?>',<?=!empty($timeout) ? $timeout : "''"?>,<?= (int) $draggable?>);">
                <?= __("Bijlage")?> #<?=$i?>
                </a>
                <?
            }
            ?>
        </div>
    <? } ?>
</div>