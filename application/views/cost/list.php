<?php ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/cost/list.css">
    <link rel="stylesheet/less" type="text/css"
          href="<?php echo base_url(); ?>public/bootstrap_less/custom/bootstrap.less">
    <script type="text/javascript">
        less = {
            env: "production" // 或者"production"/"development"
        };
    </script>
    <script src="<?php echo base_url(); ?>public/bootstrap_less/custom/less-1.7.0.js"></script>
    <script src="<?php echo base_url(); ?>public/js/jquery-3.0.0.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/validator.js"></script>
</head>
<body>
<?php $this->load->view('header');?>
<div class="container">
    <div class="add_cost_div">
        <a class="btn btn-info" href="<?=site_url(array('cost','add'))?>">添加花费记录</a>
    </div>
    <div class="table_div">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th colspan="<?= count($UserName_list) + 4 ?>">2016年</th>
            </tr>
            </thead>
            <tbody>
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
                    <td><?= $key . '月' ?></td>
                    <?php foreach ($UserName_list as $userName_value): ?>
                        <td><?= get_array_value($userName_value['UserName'], $value, ' - ') ?></td>
                    <?php endforeach; ?>
                    <td><?= get_array_value('公共', $value) ?></td>
                    <td><?= $value['total'] ?></td>
                    <td><a class="get_month_cost" data-month="<?= $key ?>">详细</a></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="<?= count($UserName_list) + 4 ?>">
                    <?php
                    $str = '总:' . get_array_value('total',$cost['total']);
                    foreach ($UserName_list as $value) {
                        if (isset($cost['total'][$value['UserName']])) {
                            $str = $str . '&nbsp;&nbsp;&nbsp;&nbsp;' . $value['UserName'] . ':' . $cost['total'][$value['UserName']];
                        }
                    }
                    echo $str;
                    ?>
                </th>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table_div" id="month_div">
    </div>
    <div class="table_div" id="day_div">
    </div>
</div>

</body>
<script>
    $(function () {
        $('.get_month_cost').click(function () {
            var month = $(this).attr('data-month')
            $('#day_div').load("<?=site_url(array('cost', 'ajax_empty'))?>")
            $('#month_div').load("<?=site_url(array('cost', 'ajax_month_list'))?>/" + month)
        })
    })
</script>
</html>