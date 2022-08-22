<script>
    <?php if($page_action){?>
    Navigation.callback = function(){
      <?= $page_action?>;
    };
    <?php } ?>
    Navigation.load('<?= $internal_page?>');
    <? if(isset($notification)){ ?>
      Notify.notify('<?= $notification ?>', "info");
    <? } ?>
</script>
