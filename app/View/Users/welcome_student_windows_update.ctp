<div class="m56 block">
    <div class="" style="margin-top:75px;padding:15px 15px 25px 15px;background-color:#ff7f7f;">
        <h1 style="text-align:center"><?= __("Belangrijk: Nieuwe Windows app beschikbaar!")?></h1>
        <p>
        <?= __("Om toetsen te kunnen maken dien je de nieuwe versie van de app te installeren in een drietal stappen:")?>
            <ol>
                <li><?= __("Deinstalleer de huidige Windows App van Test-Correct")?><br/>
                    <small><strong><?= __("Zonder het verwijderen van de oude app, kan de nieuwe app problemen geven bij het maken van de toetsen")?></strong></small>
                </li>
                <li>
                    <a href="https://www.test-correct.nl/downloads/" target="_blank"><?= __("Download the nieuwe app")?></a>
                </li>
                <li><?= __("Installeer de nieuwe app")?></li>
            </ol>
            <strong><?= __("Let op:")?></strong> <?= __("Lukt het niet of loop je tegen problemen aan, laat het ons dan weten via mail: support@test-correct.nl of telefonisch via: 0107 171 171, we helpen je graag verder.")?>
        </p>
    </div>
</div>
<script type="text/javascript" src="/js/welcome-messages.js?<?= time() ?>"></script>
<script> $.i18n().locale = '<?=CakeSession::read('Config.language')?>'; </script>


