<div id="buttons">
    <?
    $isStudent = false;
    foreach(AuthComponent::user()['roles'] as $role) {
        if($role['name'] == 'Student') {
            $isStudent = true;
        }
    }
    ?>
    <? if(!$isStudent) { ?>
        <a href="#" class="btn white white" onclick="Popup.load('/messages/send/<?=getUUID($student, 'get')?>', 500);">
        <?= __("Bericht sturen")?>
        </a>
    <? } ?>

    <?php if (!AuthComponent::user('school_location.allow_new_student_environment')) { ?>
    <a href="#" class="btn white mr2" onclick="Navigation.back();">
        <span class="fa fa-backward mr5"></span>
        <?= __("Terug")?>
    </a>
    <?php } ?>
</div>

<h1><?= __("Analyse van")?> <?=$student['name_first']?> <?=$student['name_suffix']?> <?=$student['name']?></h1>

<?
if(!$isStudent) {
    if ($student['has_text2speech']) {
        ?>
        <div style="background: #85a959; color: white; padding: 15px 0px 10px 0px; text-align:center; margin-bottom: 15px;"><?= __("Deze student mag tekst naar spraak omzetten.")?>
            <?php if(!$student['active_text2speech']) {
            ?>
            <div><b><?= __("Let op: Deze functionaliteit is tijdelijk uitgeschakeld voor deze student")?></b></div>
            <?php
            }
            ?>
        </div>
        <?
    } else {
        ?>
        <div style="background: #c51515; color: white; padding: 15px 0px 10px 0px; text-align:center; margin-bottom: 15px;"><?= __("Deze student mag tekst")?> <b><?= __("niet")?></b> <?= __("naar spraak omzetten.")?></div>
        <?
    }
    if ($student['time_dispensation']) {
        ?>
        <div style="background: #85a959; color: white; padding: 15px 0px 10px 0px; text-align:center; margin-bottom: 15px;">
        <?= __("Deze student heeft tijdsdispensatie")?>
        </div>
        <?
    } else { ?>
        <div style="background: #c51515; color: white; padding: 15px 0px 10px 0px; text-align:center; margin-bottom: 15px;"><?= __("Deze student heeft")?> <b><?= __("geen")?></b> <?= __("tijdsdispensatie")?></div>
    <?php }
}
?>



<div class="block" style="width:180px; float:right;">
    <div class="block-head"><?= __("Profielfoto")?></div>
    <div class="block-content">
        <img src="/users/profile_picture/<?=getUUID($student, 'get')?>" style="max-width:130px;" />
    </div>
</div>
<div class="block" style="float:left; width: calc(100% - 200px)">
    <div class="block-head"><?= __("Prestaties per vak")?></div>
    <div class="block-content" style="max-height: 300px; overflow: auto">

        <?
        if(!isset($student['average_ratings']) || empty($student['average_ratings'])) {
            ?>
            <center><?= __("Geen gegevens")?></center>
            <?
        }else {
            ?>

            <table class="table">
                <tr>
                    <th><?= __("Vak")?></th>
                    <th><?=$isStudent ? __("Jouw cijfe") : __("Student cijfe") ?></th>
                    <th><?= __("Gemiddeld klassecijfer")?></th>
                    <th><?= __("Gemiddelde cijfer zelfde")?><br />
                    <?= __("niveau / leerjaar")?></th>
                </tr>
                <?
                foreach ($student['average_ratings'] as $ratings) {
                    ?>
                    <tr>
                        <td><?= $ratings['subject']['name'] ?></td>
                        <td><?= round($ratings['rating'], 1) ?></td>
                        <td><?= round($ratings['school_class_average'], 1) ?></td>
                        <td><?= round($ratings['global_average'], 1) ?></td>
                    </tr>
                    <?
                }
                ?>
            </table>
            <?
        }
        ?>
    </div>
</div>

<br clear="all" />
<div class="block">
    <div class="block-head"><?= __("Toetsafnames")?></div>
    <div class="block-content" style="max-height: 400px;">
        <?
        if(empty($student['test_participants'])) {
            echo '<center><?= __("Geen notities")?></center>';
        }else{
            ?>
            <table class="table table-striped" width="100%">
                <tr>
                    <th><?= __("Toetsnaam")?></th>
                    <?if(!$isStudent){ ?><th><?= __("Notities")?></th><? } ?>
                    <th><?= __("Cijfer")?></th>
                    <th width="150"><?= __("Moment")?></th>
                    <th width="30"></th>
                </tr>
                <?
                foreach($student['test_participants'] as $participant) {

                    if($participant['test_take_test_take_status_id'] != 9) {
                        continue;
                    }
                    ?>
                    <tr>
                        <td><?=$participant['name']?></td>
                        <?if(!$isStudent){ ?><td><?=empty($participant['invigilator_note']) ? '-' : $participant['invigilator_note']?></td><? } ?>
                        
                        <? if(!$participant['show_grades']) {?>
                            <td title="<?= __("Bij formatieve toetsen staan inzicht en voortgang centraal. Daarom zie je hier geen cijfer.") ?>">
                                <?= __('N.v.t.') ?>
                            </td>
                        <? } elseif(!empty($participant['rating'])) { ?>
                            <td>
                                <?= number_format($participant['rating'], 1) ?>
                            </td>
                        <?}else{?>
                            <td>
                                <i class="fa fa-clock-o" aria-hidden="true" title="<?= __('Bezig met nakijken & becijferen. Cijfer nog niet beschikbaar') ?>"></i>
                            </td>
                        <?}?>
                        
                        <td><?=date('d-m-Y', strtotime($participant['time_start']))?></td>
                        <td class="nopadding" width="30">
                            <a href="#" onclick="Navigation.load('/test_takes/view/<?=getUUID($participant, 'get',['uuid_key' => 'test_take_uuid'])?>');" class="btn white">
                                <span class="fa fa-folder-open-o"></span>
                            </a>
                        </td>
                    </tr>
                    <?
                }
                ?>
            </table>
            <?
        }
        ?>
    </div>
</div>

<div class="block">
    <div class="block-head"><?= __("Eindtermen")?></div>
    <div class="block-content">
        <?=$this->Form->create('StudentEndterms')?>
            <table class="table">
                <tr>
                    <th width="130">
                    <?= __("Vak")?>
                    </th>
                    <td>
                        <?=$this->Form->input('subject_id', array('style' => 'width: 185px', 'label' => false, 'options' => $subjects)) ?>
                    </td>
                    <td align="right">
                        <a href="#" onclick="Analyses.loadStudentEndterms('<?=$user_id?>'); return false;" class="btn highlight inline-block"><?= __("Analyse laden")?></a>
                    </td>
                </tr>
            </table>
        <?=$this->Form->end();?>

        <div id="divAnalyseTerms" style="height: 400px;"></div>
    </div>
</div>

<div class="block">
    <div class="block-head"><?= __("Cijfers")?></div>
    <div class="block-content">
        <?=$this->Form->create('StudentRatings')?>
        <table class="table">
            <tr>
                <th width="130">
                <?= __("Vak")?>
                </th>
                <td>
                    <?=$this->Form->input('subject_id', array('style' => 'width: 185px', 'label' => false, 'options' => $subjects)) ?>
                </td>
                <th width="130">
                <?= __("Examenvak")?>
                </th>
                <td>
                    <?=$this->Form->input('base_subject_id', array('style' => 'width: 185px', 'label' => false, 'options' => $base_subjects)) ?>
                </td>
                <th width="130">
                <?= __("Score in")?>
                </th>
                <td>
                    <?=$this->Form->input('score_type', array('style' => 'width: 185px', 'label' => false, 'options' => [
                        'rates' => __("Cijfer"),
                        'percentages' => __("Percentage")
                    ])) ?>
                </td>

                <td align="right">
                    <a href="#" onclick="Analyses.loadStudentSubjectRatings('<?=$user_id?>'); return false;" class="btn highlight inline-block"><?= __("Analyse laden")?></a>
                </td>
            </tr>
        </table>
        <?=$this->Form->end();?>

        <div id="divAnalyseSubjectRatings" style="height: 400px;"></div>
    </div>
</div>
<? if(!$isStudent) { ?>
    <div class="block">
        <div class="block-head"><?= __("Berichten")?></div>
        <div class="block-content" style="overflow: auto; max-height: 400px;">

            <? if(!$isStudent) { ?>
                <a href="#" class="btn highlight" onclick="Popup.load('/messages/send/<?=getUUID($student,'get')?>', 500);" style="position: absolute; right: 50px;">
                <?= __("Bericht sturen")?>
                </a>
            <? } ?>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th width="30"></th>
                    <th><?= __("Onderwerp")?></th>
                    <th width="70"></th>
                    <th width="250"></th>
                    <th width="130"><?= __("Datum/tijd")?></th>
                    <th width="75"></th>
                </tr>
                </thead>
                <tbody>
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
                                <div class="label label-success" id="label_read_<?=getUUID($message,'get')?>"><?= __("Ongelezen")?></div>
                            <? }else{
                                ?>
                                <div class="label" id="label_read_<?=getUUID($message, 'get')?>"><?= __("Gelezen")?></div>
                                <?
                            } ?>
                        </td>
                        <td>
                            <?
                            if($message['user_id'] == AuthComponent::user('id')) {
                                ?>
                                <?= __("aan")?>
                                <?=$message['message_receivers'][0]['user']['name_first']?>
                                <?=$message['message_receivers'][0]['user']['name_suffix']?>
                                <?=$message['message_receivers'][0]['user']['name']?>
                                <?
                            }else{
                                ?>
                                <?= __("van")?>
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
                                <a href="#" class="btn white pull-right" onclick="Popup.load('/messages/show/<?=getUUID($message, 'get');?>', 400); return false;">
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
                </tbody>
            </table>
        </div>
        <div class="block-footer"></div>
    </div>
<? } ?>
<script type="application/javascript">
    $(document).ready(function() {
        $('#analyses').addClass('active');
    });
</script>
