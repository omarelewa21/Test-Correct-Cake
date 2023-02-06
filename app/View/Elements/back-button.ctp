<a href="#"
    class="btn white mr2"
    <? if (isset($return_route)) { ?>
        onclick="User.goToLaravel('<?= $return_route ?>')"
    <? } elseif(isset($onclick)) { ?>
        onclick= <?= $onclick ?>
    <? } else { ?>
        onclick="Navigation.back();"
    <? } ?>
>
    <span class="fa fa-backward mr5"></span>
    <?= __("Terug") ?>
</a>