<h1><?= __("Welkom in Test-Correct")?></h1>


<div class="" style="width:calc(50% - 10px); float: left">

    <div class="block autoheight">
        <div class="block-head"><?= __("Onderhoudsvensters")?>
            <div id="buttons">
                <a href="#" class="btn btn-sm white" onclick="Popup.load('/deployment/add',800);">
                    <span class="fa fa-plus mr5"></span>
                    <?= __("Nieuwe deployment")?>
                </a>
            </div>
        </div>
        <div class="block-content" id="schoolsContainer" style="padding-bottom:0">
            <table class="table table-striped" id="deploymentsTable">
                <thead>
                <tr>
                    <th><?= __("Datum")?></th>
                    <th><?= __("Status")?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($deployments as $deployment) { ?>
                <tr>
                    <td><?= $deployment['deployment_day']?></td>
                    <td><?= $deploymentStatuses[$deployment['status']]?></td>
                    <td class="nopadding">
                        <a href="#" class="btn white pull-right dropblock-left" onclick="Popup.load('/deployment/edit/<?=getUUID($deployment, 'get');?>',800);">
                            <span class="fa fa-folder-open-o"></span>
                        </a>

                        <a href="#" class="btn white pull-right dropblock-left" onclick="Popup.load('/deployment/add/<?=getUUID($deployment, 'get');?>',800);">
                            <span class="fa fa-files-o"></span>
                        </a>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<div style="width:calc(50% - 10px); float: right">
    <div class="block autoheight">
        <div class="block-head"><?= __("Whitelisted ip adressen")?>
            <div id="buttons">
                <a href="#" class="btn btn-sm white" onclick="Popup.load('/whitelist_ip/add',450);">
                    <span class="fa fa-plus mr5"></span>
                    <?= __("Nieuw ip adres")?>
                </a>
            </div>
        </div>
        <div class="block-content" id="schoolsContainer" style="padding-bottom:0">
            <table class="table table-striped" id="deploymentsTable">
                <thead>
                <tr>
                    <th><?= __("ip")?></th>
                    <th><?= __("naam")?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($whitelistIps as $ip) { ?>
                    <tr>
                        <td><?= $ip['ip']?></td>
                        <td><?= $ip['name']?></td>
                        <td class="nopadding">
                            <a href="#" class="btn white pull-right dropblock-left" onclick="deleteIp('<?=$ip['ip']?>','<?=getUUID($ip, 'get');?>');">
                                <span class="fa fa-trash"></span>
                            </a>

                            <a href="#" class="btn white pull-right dropblock-left" onclick="Popup.load('/whitelist_ip/edit/<?=getUUID($ip, 'get');?>',800);">
                                <span class="fa fa-pencil"></span>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<br clear="all" />

<script>
    function deleteIp(ip,uuid){
        if(confirm('<?= __("Weet je zeker dat je het ip adres ")?>'+ip+'<?= __(" wil verwijderen?")?>')){
            Loading.show();
            $.ajax({
                    url: '/whitelist_ip/delete/'+uuid,
                    method: 'POST',
                    success: function (data) {
                        Navigation.refresh();
                        Loading.hide();
                    },
                    onfailure: function (data) {
                        Navigation.refresh();
                        Loading.hide();
                    },
            });
        }
    }
</script>

<style>
    #container #buttons {
        margin-top: -5px;
        margin-right: 10px;
        font-size: 14px;
    }
</style>

