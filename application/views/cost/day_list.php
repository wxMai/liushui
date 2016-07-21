<table class="table table-bordered">
    <thead>
    <tr>
        <th colspan="6"><?=$month?>月<?=$day?>日</th>
    </tr>
    </thead>
    <tr>
        <th>时间</th>
        <th>使用者</th>
        <th>金额</th>
        <th>用途</th>
        <th>提交者</th>
        <th>备注</th>
    </tr>
    <?php foreach ($cost as $value): ?>
        <tr>
            <td><?=date('H:i',strtotime($value['time']))?></td>
            <td><?=$value['UseName']?></td>
            <td><?=$value['num']?></td>
            <td><?=$value['cost_type']?></td>
            <td><?=$value['UserName']?></td>
            <td><?=$value['note']?></td>
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