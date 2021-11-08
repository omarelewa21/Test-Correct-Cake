<style>
    body {
        font-family: Myriad Pro, Arial;
        font-size: 12px;
    }

    .alert {
        padding: 12px 25px 12px 25px;
        background: darkred;
        color: white;
        width: 220px;
        margin: 0px auto;
        text-align: center;
    }
</style>

<?
if(isset($result)) {
    if($result == 200) {
        ?>
        <script type="text/javascript">
            alert('Wachtwoord succesvol gereset');
            window.location = '/';
        </script>
        <?
    }else{
        ?>
        <div class="alert">
        <?= __("Je wachtwoord kon niet worden gereset. Zorg ervoor dat het wachtwoord minimaal 6 karakters bevat.")?>
        </div>
        <?
    }
}
?>

<div style="padding: 15px; background: #f1f1f1; margin: 0px auto; width: 200px; text-align: center; margin-top:20px;">
    <form method="post">
        <strong><?= __("E-mailadres")?></strong><br />
        <input type="text" name="email" /><br /><br />
        <strong><?= __("Nieuw wachtwoord:")?></strong><br />
        <span style="color: #888"><?= __("Minimaal 6 karakters")?></span>
        <input type="password" name="password" /><br /><br />
        <input type="submit" value='<?= __("Wachtwoord resetten")?>' />
    </form>
</div>