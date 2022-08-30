<div class="popup-head"><?= __("Docent")?></div>
<div class="popup-content">
    <?=$this->Form->create('User') ?>
    <table class="table">
        <tr>
            <th width="130">
            <?= __("Schoollocatie")?>
            </th>
            <td>
                <?=$this->Form->input('school_location_id', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty', 'options' => $school_locations)) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Voornaam")?>
            </th>
            <td>
                <?=$this->Form->input('name_first', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Tussenvoegsel")?>
            </th>
            <td>
                <?=$this->Form->input('name_suffix', array('style' => 'width: 185px', 'label' => false)) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Achternaam")?>
            </th>
            <td>
                <?=$this->Form->input('name', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Afkorting")?>
            </th>
            <td>
                <?=$this->Form->input('abbreviation', array('style' => 'width: 185px', 'label' => false, 'verify' => 'notempty')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("E-mailadres")?>
            </th>
            <td>
                <?=$this->Form->input('username', array('style' => 'width: 185px', 'label' => false, 'verify' => 'email')) ?>
            </td>
        </tr>
        <tr>
            <th width="130">
            <?= __("Wachtwoord")?>
            </th>
            <td>
                <?=$this->Form->input('password', array('style' => 'width: 185px', 'label' => false, 'verify' => 'length-8', 'type' => 'text')) ?>
            </td>
        </tr>
        
        <tr>
            <th width="130">
            <?= __("Externe code")?>
            </th>
            <td>
                <?= $this->Form->input('external_id', array('style' => 'width: 185px','label' => false, 'type' => 'text')) ?>
            </td>
        </tr>

        <tr>
            <th width="130">
            <?= __("Examen coördinator")?>
            </th>
            <td>
                <?= $this->Form->input('is_examcoordinator', array('style' => 'width: 20px','label' => false, 'type' => 'checkbox')) ?>
            </td>
        </tr>

        <tr class="is_examcoordinator-options">
            <td colspan="2">
                <?= $this->Form->input('exam_coordinator_schedule_for', array(
                            'separator' => '<br>',
                            'after' => '<span aria-hidden="true"></span>',
                            'options' => array('SCHOOL_LOCATION' => __('Koppel deze gebruiker aan de schoollocatie'),
                                                'SCHOOL' => __('Koppel deze gebruiker aan de hele scholengemeenschap')),
                            'type' => 'radio',
                            'legend' => false))
                ?>
            </td>
        </tr>

        <tr>
            <th colspan="2"><?= __("Notities")?></th>
        </tr>
        <tr>
            <td colspan="2">
                <?=$this->Form->input('note', [
                    'style' => 'width:330px; height:100px',
                    'type' => 'textarea',
                    'label' => false
                ])?>
            </td>
        </tr>
    </table>
    <?=$this->Form->end();?>
</div>
<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();" selid="cancel-btn">
    <?= __("Annuleer")?>
    </a>
    <a href="#" class="btn highlight mt5 mr5 pull-right" id="btnAddUser">
    <?= __("Aanmaken")?>
    </a>
</div>

<script type="text/javascript">
    $('#UserAddForm').formify(
        {
            confirm : $('#btnAddUser'),
            onsuccess : function(result) {
                Popup.closeLast();
                Notify.notify('<?= __("Gebruiker aangemaakt")?>', "info");
                Navigation.refresh();
            },
            onfailure : function(result) {
                if (result.error == 'username') {
                    Notify.notify('<?= __("E-mailadres al in gebruik")?>', "error");
                }else if(result.error== 'dns'){
                    Notify.notify('<?= __("Het domein van het opgegeven e-mailadres is niet geconfigureerd voor e-mailadressen")?>', "error");
                }else if(result.error== 'external_code'){
                    Notify.notify('<?= __("Deze externe code is al in gebruik")?>', "error");
                }else if (result.error == 'user_roles'){
                    Notify.notify('<?= __("U kunt een docent pas aanmaken nadat u een actuele periode heeft aangemaakt. Dit doet u door als schoolbeheerder in het menu Database -> Schooljaren een schooljaar aan te maken met een periode die in de huidige periode valt.")?>','error')
                }

                var errors = JSON.parse(result.error)?.errors
                if('password' in errors) {
                    return Notify.notify(errors.password, "error");
                }else if('exam_coordinator_schedule_for' in errors){
                    return Notify.notify(errors.exam_coordinator_schedule_for, "error");
                }

                Notify.notify('<?= __("Gebruiker kon niet worden aangemaakt")?>', "error");
            }
        }
    );

    $('input[name="data[User][is_examcoordinator]"]').change(function(){
        if($(this).is(':checked')){
                Popup.message({
                btnOk: '<?= __('Ok') ?>',
                title: '<?= __('Examen coördinator') ?>',
                message: '<?= __('De gebruiker is nu examen coördinator. Kies één van onderstaande acties om de examen coördinator te koppelen aan alle Klassen') ?>'
            }, function() {
                $('.is_examcoordinator-options').css({'visibility': 'visible', 'position': 'relative'});
            })
        }else{
            $('.is_examcoordinator-options').css({'visibility': 'hidden', 'position': 'absolute'});
        }
    })

</script>

<style>
    .is_examcoordinator-options {
        visibility: hidden;
        position: absolute;
    }
</style>