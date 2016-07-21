<table class="table table-bordered">
    <thead>
    <tr>
        <th colspan="<?= count($UserName_list) + 4 ?>"><?=$month?>月</th>
    </tr>
    </thead>
    <tr>
        <th></th>
        <?php foreach ($UserName_list as $value): ?>
            <th><?= $value['UserName'] ?></th>
        <?php endforeach; ?>
        <th>公共</th>
        <th>总</th>
        <th></th>
    </tr>
    <?php foreach ($cost['list'] as $key => $value): ?>
        <tr>
            <td><?= $key . '日' ?></td>
            <?php foreach ($UserName_list as $userName_value): ?>
                <td><?= get_array_value($userName_value['UserName'], $value, ' - ') ?></td>
            <?php endforeach; ?>
            <td><?= get_array_value('公共', $value) ?></td>
            <td><?= $value['total'] ?></td>
            <td><a class="get_day_cost" data-day="<?= $key ?>">详细</a></td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    $(function(){
        $('.get_day_cost').click(function(){
            var day = $(this).attr('data-day')
            $('#day_div').load("<?=site_url(array('cost', 'ajax_day_list'))?>/<?=$month?>/" + day)
        })
    })
</script>