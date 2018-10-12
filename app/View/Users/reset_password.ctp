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
            Je wachtwoord kon niet worden gereset
        </div>
        <?
    }
}
?>

<div style="padding: 15px; background: #f1f1f1; margin: 0px auto; width: 200px; text-align: center; margin-top:20px;">
    <form method="post">
        <strong>E-mailadres</strong><br />
        <input type="text" name="email" /><br /><br />
        <strong>Nieuw wachtwoord:</strong><br />
        <span style="color: #888">Minimaal 6 karakters</span>
        <input type="password" name="password" /><br /><br />
        <input type="submit" value="Wachtwoord resetten" />
    </form>
</div>