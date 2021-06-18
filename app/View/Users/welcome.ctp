<?php if($hasSchoolManagerRole) { ?>
    <div class="dashboard">
        <div class="notes">
            <?php if ($should_display_import_incomplete_panel) { ?>
                <div class="notification warning">
                    <div class="title">
                        <h5 style="">De importgegevens van klassen zijn bijna compleet</h5>
                    </div>
                    <div class="body">
                        <p>De geïmporteerde gegevens van klassen uit Magister of SOMtoday zijn bijna compleet. Vul de gegevens aan zodat de docenten aan de slag kunnen.</p>
                        <a class="text-button" onclick="displayCompleteUserImport()">Importgegevens voor klassen compleet maken.<?php echo $this->element('arrow') ?></a>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
<?php } ?>

<h1 style="text-align:center; margin-top: 200px;">Welkom in Test-Correct</h1>

<script>
    function displayCompleteUserImport() {
        Popup.load('users/school_manager_complete_user_import_main_school_class', 1080);
    }
</script>
