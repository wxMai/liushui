<?php ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/header.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/extra/bootstrap-datetimepicker.min.css">
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
</head>
<body>
    <nav>
        <div class="container">
            <ul class="nav_menu">
                <li>
                    <a href="<?=site_url(array('Cost','lists'))?>">记账</a>
                </li>
                <li>
                    <a>美丽说</a>
                </li>
                <li>
                    <a>管理</a>
                </li>
            </ul>
        </div>
    </nav>
</body>
</html>