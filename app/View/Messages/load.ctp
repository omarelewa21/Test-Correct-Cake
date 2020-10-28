<?
foreach($messages as $message) {
    ?>
    <tr>
        <td>
            <span class="fa fa-<?= $message['user_id'] == AuthComponent::user('id') ?  'mail-forward' : 'mail-reply' ?>"></span>
        </td>
        <td>
            <strong><?=$message['subject']?></strong><br />
            <?=strlen($message['message']) > 140 ? substr($message['message'], 0, 140) . '...' : $message['message']?>
        </td>
        <td>
            <? if ($message['message_receivers'][0]['read'] == 0) { ?>
                <div class="label label-success" id="label_read_<?=$message['id']?>">Ongelezen</div>
            <? }else{
                ?>
                <div class="label" id="label_read_<?=$message['id']?>">Gelezen</div>
                <?
            } ?>
        </td>
        <td>
            <?
            if($message['user_id'] == AuthComponent::user('id')) {
                ?>
                aan
                <?=$message['message_receivers'][0]['user']['name_first']?>
                <?=$message['message_receivers'][0]['user']['name_suffix']?>
                <?=$message['message_receivers'][0]['user']['name']?>
                <?
            }else{
                ?>
                van
                <?=$message['user']['name_first']?>
                <?=$message['user']['name_suffix']?>
                <?=$message['user']['name']?>
                <?
            }
            ?>
        </td>
        <td><?=date('d-m-Y H:i', strtotime($message['created_at']))?></td>
        <td class="nopadding">
            <? if( $message['user_id'] == AuthComponent::user('id')) { ?>
                <a href="#" class="btn white pull-right" onclick="Popup.load('/messages/show/<?=getUUID($message, 'get');?>', 400);">
                    <span class="fa fa-eye"></span>
                </a>
            <? }else{ ?>
                <a href="#" class="btn white pull-right" onclick="Popup.load('/messages/show/<?=getUUID($message, 'get');?>', 400); $('#label_read_<?=getUUID($message, 'get');?>').fadeOut();">
                    <span class="fa fa-eye"></span>
                </a>
            <? } ?>
        </td>
    </tr>
    <?
}
?>