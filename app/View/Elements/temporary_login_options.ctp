<?php
$notificationsToShow = [];

if (CakeSession::read('temporaryLoginOptions') !== null) {
    $options = json_decode(CakeSession::consume('temporaryLoginOptions'), true);
    if (array_key_exists('redirect_reason', $options)) {
        ?>
        <script>
            <?php foreach ($options['redirect_reason'] as $text => $type) {?>
            Notify.notify('<?php echo $text ?>', '<?php echo $type ?>', 7500)
            <?php } ?>
        </script>
    <?php }

    if (array_key_exists('page', $options)) {
        ?>
        <script>
            Navigation.load('<?= $options['page']['url'] ?>')
        </script>
    <?php }

}
?>
