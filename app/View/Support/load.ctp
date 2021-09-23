<?
foreach($logs as $log) {
    $created_at = new DateTime($log['created_at']);
    $created_at->setTimezone(new DateTimeZone(Configure::read('Config.timezone')));
    ?>
    <tr>
        <td><?= $log['support_user']['fullname'] ?></td>
        <td><?= $log['user']['fullname'] ?></td>
        <td><?= $created_at->format('d-m-Y H:i:s') ?></td>
        <td><?= $log['ip'] ?></td>

        <td class="nopadding">

        </td>
    </tr>
    <?
}
?>