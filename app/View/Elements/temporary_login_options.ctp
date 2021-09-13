<?php
if (CakeSession::read('temporaryLoginOptions') !== null) {
    $options = json_decode(CakeSession::consume('temporaryLoginOptions'), true);
    if (array_key_exists('notification', $options) || array_key_exists('notifications', $options)) {
        $notifications = $options['notification'] != null ? $options['notification'] : $options['notifications'];
        ?>
        <script>
            <?php foreach ($notifications as $text => $type) {?>
            Notify.notify('<?php echo $text ?>', '<?php echo $type ?>', 7500)
            <?php } ?>
        </script>
    <?php }

    if (array_key_exists('page', $options)) {
        ?>
        <script>
            Navigation.load('<?= $options['page'] ?>')
        </script>
    <?php }

}
?>
