    <div id="buttons">
        <a href="#" class="btn white" onclick="Popup.load('/infos/add',800);">
            <span class="fa fa-plus mr5"></span>
            <?= __("Nieuwe Info message")?>
        </a>
    </div>


<h1><?= __("Info Messages")?></h1>

<div class="block autoheight">
    <div class="block-head"><?= __("Overzicht")?></div>
    <div class="block-content" id="schoolsContainer">
        <table class="table table-striped" id="deploymentsTable">
            <thead>
            <tr>
                <th><?= __("Titel")?></th>
                <th><?= __("Tonen van")?></th>
                <th><?= __("Tonen tot")?></th>
                <th><?= __("Status")?></th>
                <th><?= __("Aan")?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($infos as $info){ ?>
                    <tr>
                        <td><?= $info['title']?></td>
                        <td><?= $info['show_from']?></td>
                        <td><?= $info['show_until']?></td>
                        <td><?= $info['status']?></td>
                        <td><?
                            if($info['for_all']) {
                                echo __("Iedereen");
                            } else {
                                $roleList = [];
                                foreach($info['roles'] as $roleAr){
                                    $roleList[] = $roleAr['name'];
                                }
                                if(count($roleList)) {
                                    echo implode(', ', $roleList);
                                } else {
                                    echo '-';
                                }
                            }
                            ?></td>
                        <td class="nopadding">
                            <a href="#" class="btn white pull-right dropblock-left" onclick="Popup.load('/infos/edit/<?=getUUID($info, 'get');?>',800);">
                                <span class="fa fa-folder-open-o"></span>
                            </a>

                            <a href="#" class="btn white pull-right dropblock-left" onclick="Popup.load('/infos/add/<?=getUUID($info, 'get');?>',800);">
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