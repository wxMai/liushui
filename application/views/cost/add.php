<?php ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/cost/cost.css">
    <link rel="stylesheet/less" type="text/css"
          href="<?php echo base_url(); ?>public/bootstrap_less/custom/bootstrap.less">
    <script type="text/javascript">
        less = {
            env: "development" // 或者"production"/"development"
        };
    </script>
    <script src="<?php echo base_url(); ?>public/bootstrap_less/custom/less-1.7.0.js"></script>
    <script src="<?php echo base_url(); ?>public/js/jquery-3.0.0.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/validator.js"></script>
    <!--    <link rel="stylesheet" type="text/css" href="-->
    <?php //echo base_url(); ?><!--public/extra/jquery-editable-select.min.css">-->
    <!--    <script src="--><?php //echo base_url(); ?><!--public/extra/jquery-editable-select.min.js"></script>-->
</head>
<body>
<div class="form_div">
    <form action="<?= site_url(array('cost', 'add')) ?>" method="post" class="form-inline" id="add_form" role="form"
          data-toggle="validator">
        <div class="form-group">
            <select class="form-control" id="UserName" name="UserName" required>
                <option selected>Name1</option>
                <option>Name2</option>
                <option>Name3</option>
            </select>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <select class="form-control" id="type" name="type" required>
                <option selected>type1</option>
                <option>type2</option>
                <option>type3</option>
            </select>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <input name="Money" type="number" placeholder="How much?" class="form-control input_money" data-error="Money不能为空" required>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <input name="note" type="text" placeholder="备注" class="form-control input_note">
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group pull-right">
            <input type="submit" value="提交" class="btn btn-info">
            <div class="help-block with-errors"></div>
        </div>
    </form>
</div>
</body>
<script>
    $(function () {
//        $('#UserName').editableSelect();
    })
</script>
</html>