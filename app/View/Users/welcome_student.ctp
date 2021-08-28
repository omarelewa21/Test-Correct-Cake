<h1><?= __("Welkom in Test-Correct")?></h1>
<?php if ($this->Session->read('TLCVersionCheckResult') == 'NEEDSUPDATE') { ?>
<div class="dashboard">
    <div class="notes">
        <div class="notification warning">
            <div class="title">
                <?php echo $this->element('warning', array('color' => 'var(--error-text)')) ?><h5
                    style="margin-left: 20px;"><?= __("Let op!")?></h5>
            </div>
            <div class="body">
                <?php if ($needsUpdateDeadline) { ?>
                <p>
                <?= __("De versie van de app die je gebruikt wordt per")?> <?=$needsUpdateDeadline?> <?= __("niet meer toegelaten. Update de app zo spoedig mogelijk. Heb je de app via een app store geïnstalleerd, dan gaat het updaten meestal automatisch.")?> <a onclick="Popup.showExternalPage('https://support.test-correct.nl/knowledge/melding-verouderde-versie')" href="#"><?= __("Lees meer.")?> </a>
                </p>
                <?php }else{ ?>
                    <p>
                    <?= __("De versie van de app die je gebruikt wordt binnenkort niet meer toegelaten. Update de app zo spoedig mogelijk. Heb je de app via een app store geïnstalleerd, dan gaat het updaten meestal automatisch.")?> <a onclick="Popup.showExternalPage('https://support.test-correct.nl/knowledge/melding-verouderde-versie')" href="#"><?= __("Lees meer.")?> </a>
                    </p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if (($this->Session->read('TLCVersionCheckResult') == 'NOTALLOWED')&&!$isInBrowser) { ?>
    <div class="dashboard">
        <div class="notes">
            <div class="notification error">
                <div class="title">
                    <?php echo $this->element('warning', array('color' => 'var(--error-text)')) ?><h5
                            style="margin-left: 20px;"><?= __("Let op!")?></h5>
                </div>
                <div class="body">
                    <p>
                    <?= __("De versie van de app die je gebruikt wordt niet meer toegelaten. Update de app om toetsen te kunnen maken.")?> <a onclick="Popup.showExternalPage('https://support.test-correct.nl/knowledge/melding-verouderde-versie')" href="#"><?= __("Lees meer.")?> </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

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

    <? if($this->Session->check('TLCVersion')){//} && strlen($this->Session->read('TLCVersion')) > 2){
        if($this->Session->check('TLCVersionCheckResult')){
            // new setup
            $version = $this->Session->read('TLCVersion');
            $versionClassAr = [
                'OK' => '',
                'NEEDSUPDATE' => 'label-warning',
                //'NOTALLOWED' => 'label-danger-blink',
                'NOTALLOWED' => 'label-danger',
        ];
            $extraClass = $versionClassAr[$this->Session->read('TLCVersionCheckResult')];
        } else {
            // old setup
            $version = explode('|',$this->Session->read('TLCVersion'))[1];
            $extraClass = (version_compare($version,'2.1','<') ? 'label-danger' : '');
        }
            ?>
    jQuery("#versionBadge").attr("class","versionBadge <?=$extraClass?>").text("<?=$version?>");
        <? } ?>

</script>


<!-- <script type='text/javascript'>

    (function()
    {
    if( window.localStorage )
    {
        if( !localStorage.getItem('firstLoad') )
        {
        localStorage['firstLoad'] = true;
        window.location.reload();
        }  
        else
        localStorage.removeItem('firstLoad');
    }
    })();

</script> -->