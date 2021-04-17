<?
if(!empty($attachments)) {
    if($type == 'add') {
        ?>
        <table class="table">
            <tr>
                <th><?= __("Omschrijving / Pad")?></th>
                <th><?= __("Type")?></th>
                <th width="200"><?= __("Instellingen")?></th>
                <th width="30"></th>
            </tr>
            <?
            $i = 0;
            foreach ($attachments as $key => $attachment) {
                $i++;
                ?>
                <tr>
                    <td>
                    <?= __("Bijlage")?> #<?=$i?>
                    </td>
                    <td>
                        <?
                        if ($attachment['type'] == 'video') {
                            echo __("Video");
                        }elseif (strstr($attachment['file']['type'], 'audio')) {
                            echo __("Audio");
                        }elseif (strstr($attachment['file']['type'], 'image')) {
                            echo __("Afbeelding");
                        }elseif (strstr($attachment['file']['type'], 'pdf')) {
                            echo __("PDF");
                        }
                        ?>
                    </td>
                    <td>
                        <?
                        if(isset($attachment['settings'])) {
                            if(isset($attachment['settings']['pausable'])) {
                                if($attachment['settings']['pausable'] == '1') {
                                    echo __("- Te pauzeren");
                                }else{
                                    echo __("- Niet te pauzeren");
                                }

                                echo '<br />';
                            }
                            if(isset($attachment['settings']['play_once'])) {
                                if($attachment['settings']['play_once'] == '1') {
                                    echo __("- Eenmalig af te spelen");
                                }else{
                                    echo __("- Oneindig af te spelen");
                                }

                                echo '<br />';
                            }

                            if(isset($attachment['settings']['timeout'])) {
                                echo __("- Binnen ") . $attachment['settings']['timeout'] . __("s beantwoorden");
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <a href="#" class="btn red small"
                        onclick="Attachments.removeAddAttachment(<?= $key ?>);">
                            <span class="fa fa-remove"></span>
                        </a>
                    </td>
                </tr>
            <?
            }
            ?>
        </table>
    <?
    }else{
        ?>
        <table class="table">
            <tr>
                <th><?= __("Omschrijving / Pad")?></th>
                <th><?= __("Type")?></th>
                <th width="200"><?= __("Instellingen")?></th>
                <th width="30"></th>
            </tr>
            <?
            $i = 0;
            foreach ($attachments as $key => $attachment) {
                $i++;
                ?>
                <tr>
                    <td>
                    <?= __("Bijlage")?> #<?=$i?>
                    </td>
                    <td>
                        <?
                        if (strstr($attachment['file_mime_type'], 'audio')) {
                            echo __("Audio");
                        }elseif (strstr($attachment['file_mime_type'], 'image')) {
                            echo __("Afbeelding");
                        } elseif ($attachment['type'] == 'video') {
                            echo __("Video");
                        } elseif (strstr($attachment['file_mime_type'], 'pdf')) {
                            echo __("PDF");
                        }
                        ?>
                    </td>
                    <td>
                        <?
                        if(!empty($attachment['json'])) {
                            $settings = json_decode($attachment['json'], true);

                            if(isset($settings['pausable'])) {
                                if($settings['pausable'] == '1') {
                                    echo __("- Te pauzeren");
                                }else{
                                    echo __("- Niet te pauzeren");
                                }

                                echo '<br />';
                            }
                            if(isset($settings['play_once'])) {
                                if($settings['play_once'] == '1') {
                                    echo __("- Eenmalig af te spelen");
                                }else{
                                    echo __("- Oneindig af te spelen");
                                }

                                echo '<br />';
                            }

                            if(isset($settings['timeout'])) {
                                echo __("- Binnen ") . $settings['timeout'] . __("s beantwoorden");
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <? if($editable) { ?>
                            <a href="#" class="btn red small"
                            onclick="Attachments.removeEditAttachment('<?=$owner?>', '<?=$id?>', '<?=getUUID($attachment, 'get');?>');">
                                <span class="fa fa-remove"></span>
                            </a>
                        <? } ?>
                    </td>
                </tr>
            <?
            }
            ?>
        </table>
    <?
    }
}

if($type == 'add') {
    $action = '/upload_attachment/add';
}else{
    $action = '/upload_attachment/edit/'.$owner . '/' . $owner_id . '/' . $id;
}

?>
<? if($editable) { ?>
    <?=$this->Form->create('Question', array('id' => 'FormAddAttachment', 'type' => 'file', 'target' => 'frameUploadAttachment', 'action' => $action))?>
        <center>
            <a href="#" class="btn highlight small inline-block">
                <?=$this->Form->input('file', array('type' => 'file', 'style' => 'width:120px; position: absolute; opacity: 0;', 'label' => false, 'div' => false, 'onchange' => 'Attachments.fileInserted();')) ?>
                <span class="fa fa-picture-o"></span>
                <?= __("Afbeelding uploaden")?>
            </a>
            <a href="#" class="btn highlight small inline-block">
                <?=$this->Form->input('file2', array('type' => 'file', 'style' => 'width:120px; position: absolute; opacity: 0;', 'label' => false, 'div' => false, 'onchange' => 'Attachments.fileInserted();')) ?>
                <span class="fa fa-file"></span>
                <?= __("PDF uploaden")?>
            </a>
            <? if($type == 'add') { ?>
                <a href="#" class="btn highlight small inline-block" onclick="Popup.load('/questions/attachments_sound/add', 600);">
                    <span class="fa fa-file-sound-o"></span>
                    <?= __("Geluidsfragment uploaden")?>
                </a>
                <a href="#" class="btn highlight small inline-block"  onclick="Popup.load('/questions/attachments_video/add', 600);">
                    <span class="fa fa-video-camera"></span>
                    <?= __("Video toevoegen")?>
                </a>
            <? }else{ ?>
                <a href="#" class="btn highlight small inline-block" onclick="Popup.load('/questions/attachments_sound/edit/<?=$owner?>/<?=$owner_id?>/<?=$id?>', 600);">
                    <span class="fa fa-file-sound-o"></span>
                    <?= __("Geluidsfragment uploaden")?>
                </a>
                <a href="#" class="btn highlight small inline-block" onclick="Popup.load('/questions/attachments_video/edit/<?=$owner?>/<?=$owner_id?>/<?=$id?>', 600);">
                    <span class="fa fa-video-camera"></span>
                    <?= __("Video toevoegen")?>
                </a>
            <? } ?>
        </center>
    <?=$this->Form->end?>
    <iframe id="frameUploadAttachment" name="frameUploadAttachment" width="0" height="0" frameborder="0" style="position: absolute"></iframe>
<? } ?>