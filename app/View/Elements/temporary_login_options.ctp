<?php
    $notificationsToShow = [];

    if (AuthComponent::user('temporaryLoginOptions') !== null) {
        $options = json_decode(AuthComponent::user('temporaryLoginOptions'), true);
        if (array_key_exists('redirect_reason', $options)) {
            $redirectReason = $options['redirect_reason'];
            ?>
            <script>
            <?php foreach ($redirectReason as $text => $type) {?>
                Notify.notify('<?php echo $text ?>', '<?php echo $type ?>')
            <?php } ?>
            </script>
        <?php }
    }
?>
