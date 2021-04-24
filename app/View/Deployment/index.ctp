    <div id="buttons">
        <a href="#" class="btn white" onclick="Popup.load('/deployment/add',800);">
            <span class="fa fa-plus mr5"></span>
            <?= __("Nieuwe deployment")?>
        </a>
    </div>


<h1><?= __("Deployments")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Overzicht")?></div>
    <div class="block-content" id="schoolsContainer">
        <table class="table table-striped" id="deploymentsTable">
            <thead>
            <tr>
                <th><?= __("Datum")?></th>
                <th><?= __("Status")?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($list as $deployment) ?>
                    <tr>
                        <td><?= $deployment->deployment_day?></td>
                        <td><?= $deployment->status?></td>
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
    <div class="block-footer"></div>
</div>