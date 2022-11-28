<script>
    <?php if($page_action){?>
    Navigation.callback = function(){
      <?= $page_action?>;
    };
    <?php } ?>
    Navigation.load('<?= $internal_page?>');
</script>
