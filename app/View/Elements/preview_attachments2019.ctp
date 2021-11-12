<? if(count($question['attachments']) > 0 && !$hideExtra) { ?>
<div style="width:250px; padding:20px; background: #294409; margin-left: 30px; float: right;">
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