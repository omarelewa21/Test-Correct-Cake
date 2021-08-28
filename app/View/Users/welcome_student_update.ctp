<div class="m56 block">
    <div class="" style="margin-top:75px;padding:15px 15px 25px 15px;background-color:#ff7f7f;">
        <h1 style="text-align:center"><?= __("Let op! Je gebruikt een oude versie van Test-Correct.")?></h1>
        <p><?= __("Volg onderstaande stappen om je toets te kunnen maken. Schrijf deze stappen op of maak een foto zodat je ze dadelijk nog weet;")?>
        <ol>
            <li>
            <?= __("Verwijder de huidige Windows App van Test-Correct")?><br/>
                <small><?= __("Om er voor te zorgen dat de nieuwe app goed werkt moet de oude versie helemaal verwijderd zijn voordat je de nieuwe installatie uitvoert.")?></small>
                <br/><br/>
            </li>
            <li>
            <?= __("Ga naar de downloadpagina van Test-Correct (https://www.test-correct.nl/downloads/) en download de meest recente versie voor jouw apparaat.")?>
                <br/><br/>
            </li>
            <li><?= __("Installeer de nieuwe app")?></li>
        </ol>
        <?= __("Mocht je tegen problemen aan lopen tijdens het (de-)installeren van de applicatie, bezoek dan onze kennisbank op https://support.test-correct.nl en zoek daar naar 'uninstall' voor een uitgebreide uitleg. Kom je er dan nog steeds niet uit, stuur je vraag naar support@test-correct.nl of neem contact met ons op.")?><br /><br/>
        <?= __("Veel succes met je toets!")?>
        </p>
    </div>
</div>

<div class="block" style="width:calc(50% - 10px); float: left">
    <div class="block-head"><?= __("Geplande toetsen")?></div>
    <div id="widget_planned" style="height:200px; overflow: auto;">

    </div>
</div>

<div class="block" style="width:calc(50% - 10px); float: right">
    <div class="block-head"><?= __("Laatste cijfers")?></div>
    <div id="widget_rated" style="height:200px; overflow: auto;">

    </div>
</div>

<br clear="all" />

<script type="text/javascript" src="/js/welcome-messages.js?<?= time() ?>"></script>
<script> $.i18n().locale = '<?=CakeSession::read('Config.language')?>'; </script>

<script type="text/javascript">
    $('#widget_planned').load('/test_takes/widget_planned');
    $('#widget_rated').load('/test_takes/widget_rated');
</script>
