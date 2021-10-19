<div class="block" style="width:500px; margin: 0px auto; margin-top:100px;">
    <div class="block-head"><?= __("Bespreking is afgerond")?></div>
    <div class="block-content">
        <div class="alert alert-info" style="text-align: center;">
        <?= __("De bespreking is afgerond.")?>
        </div>
        <?php if($guest) {?>
        <div style="display: flex;width: 100%;justify-content: center;margin: 1rem 0 0 0;">
            <a href="<?= $loginUrl ?>" class="button primary-button button-md" style="display: flex; align-items: center; text-decoration: none">
                <span>Sluiten</span>
            </a>
        </div>
        <?php } ?>
    </div>
</div>