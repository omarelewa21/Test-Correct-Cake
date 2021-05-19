<h1 style="text-align:center; margin-top: 200px;">Welkom in Test-Correct</h1>
<?php if($hasSchoolManagerRole) { ?>
<div class="dashboard">
    <div class="notes">
        <div class="notification error">
            <div class="title">
                <?php echo $this->element('warning', array('color' => 'var(--error-text)')) ?><h5
                    style="margin-left: 20px;">Import gegevens van klassen zijn incompleet</h5>
            </div>
            <div class="body">
                <p>Van de geïmporteerde klassen gegevens uit Magister zijn incompleet. Er missen gegevens van één of meerdere klassen. Deze kunnen niet ingepland worden voor toetsen. Maak deze eerst compleet door op onderstaande link te klikken.</p>
                <a class="text-button" onclick="displayCompleteUserImport()">Inloggegevens voor klassen compleet maken.<?php echo $this->element('arrow') ?></a>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script>
    function displayCompleteUserImport() {
        Popup.load('users/school_manager_complete_user_import_main_school_class', 1080);
    }
</script>
