<h1>Welkom in Test-Correct</h1>

<div class="block" style="width:calc(50% - 10px); float: left">
    <div class="block-head">Geplande toetsen</div>
    <div id="widget_planned" style="height:200px; overflow: auto;">

    </div>
</div>

<div class="block" style="width:calc(50% - 10px); float: right">
    <div class="block-head">Laatste cijfers</div>
    <div id="widget_rated" style="height:200px; overflow: auto;">

    </div>
</div>

<br clear="all" />

<script type="text/javascript">
    $('#widget_planned').load('/test_takes/widget_planned');
    $('#widget_rated').load('/test_takes/widget_rated');


    <? if($this->Session->check('TLCVersion')){//} && strlen($this->Session->read('TLCVersion')) > 2){
        if($this->Session->check('TLCVersionCheckResult')){
            // new setup
            $version = $this->Session->read('TLCVersion');
            $versionClassAr = [
                'OK' => '',
                'NEEDSUPDATE' => 'label-danger',
                'NOTALLOWED' => 'label-danger-blink'
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