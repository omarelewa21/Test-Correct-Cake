<?php if($hasSchoolManagerRole) { ?>
    <div class="dashboard">
        <div class="notes">
            <?php if ($should_display_import_incomplete_panel) { ?>
                <div class="notification warning">
                    <div class="title">
                        <h5 style=""><?= __("De importgegevens van klassen zijn bijna compleet")?></h5>
                    </div>
                    <div class="body">
                        <p><?= __("De geïmporteerde gegevens van klassen uit")?> <?= $lvs_type ?> <?= __("zijn bijna compleet. Vul de gegevens aan zodat de docenten aan de slag kunnen.")?></p>
                        <a class="text-button" onclick="displayCompleteUserImport()"><?= __("Importgegevens voor klassen compleet maken")?>.<?php echo $this->element('arrow') ?></a>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
<?php } ?>

<h1 style="text-align:center; margin-top: 200px;"><?= __("Welkom in Test-Correct")?></h1>

<script type="text/javascript" src="/js/welcome-messages.js?<?= time() ?>"></script>
<script>
    function displayCompleteUserImport() {
        Popup.load('users/school_manager_complete_user_import_main_school_class', 1080);
    }
</script>
