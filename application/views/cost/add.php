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
<body style="background-color: #F7F7F7;">
<div class="form_div">
    <form action="<?= site_url(array('cost', 'add')) ?>" method="post" class="form-horizontal" id="add_form" role="form"
          data-toggle="validator">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="UserName">使用者</label>
            <div class="col-sm-10">
                <input type="text" name="UseName" id="UserName" class="form-control ul_input">
                <ul class="input_ul" id="UserName_ul">
                    <?php foreach ($UserName_list as $value): ?>
                        <li class="input_li" data-id="UserName"><?= $value['UserName'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="type">用途</label>
            <div class="col-sm-10">
                <input type="text" name="type" id="type" class="form-control ul_input">
                <ul class="input_ul" id="type_ul">
                    <li class="input_li" data-id="type">type1</li>
                    <li class="input_li" data-id="type">type2</li>
                    <li class="input_li" data-id="type">type3</li>
                    <li>
                        <input type="text" class="form-control" placeholder="添加">
                        <input type="button" class="btn-sm btn-primary" value="添加">
                    </li>
                </ul>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="Money">金额</label>
            <div class="col-sm-10">
                <input id="Money" name="Money" type="number" placeholder="元" class="form-control input_money"
                       data-error="Money不能为空" required>

                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2"></label>
            <div class="col-sm-10">
                <input name="note" type="text" placeholder="备注" class="form-control input_note">
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2"></label>
            <div class="col-sm-10">
                <input type="submit" value="提交" class="btn btn-info">
            </div>
        </div>
    </form>
</div>
</body>
<script>
    $(function () {
        $('.ul_input').click(function () {
            var id = $(this).attr('id') + '_ul'
            $('ul#' + id).addClass('input_ul_z-index').addClass('input_ul_hover')
        })
        $('.input_li').click(function () {
            var id = '#' + $(this).attr('data-id')
            var value = $(this).html()
            console.log(id)
            $('ul#' + id).removeClass('input_ul_z-index')
            $(id).val(value)
        })
        $('.input_ul').blur(function () {
            var id = $(this).attr('id') + '_ul'
            setTimeout(function () {
                $('ul#' + id).removeClass('input_ul_hover')
                setTimeout(function () {
                    $('ul#' + id).removeClass('input_ul_z-index')
                }, 500)
            }, 100);

        })
    })
</script>
</html>