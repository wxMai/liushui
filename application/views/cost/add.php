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
            env: "production" // 或者"production"/"development"
        };
    </script>
    <script src="<?php echo base_url(); ?>public/bootstrap_less/custom/less-1.7.0.js"></script>
    <script src="<?php echo base_url(); ?>public/js/jquery-3.0.0.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/bootstrap.min.js"></script>
<!--    <script src="--><?php //echo base_url(); ?><!--public/js/validator.js"></script>-->
    <!--    <link rel="stylesheet" type="text/css" href="-->
    <?php //echo base_url(); ?><!--public/extra/jquery-editable-select.min.css">-->
    <!--    <script src="--><?php //echo base_url(); ?><!--public/extra/jquery-editable-select.min.js"></script>-->
</head>
<body style="background-color: #F7F7F7;">
<div class="form_div">
    <form action="<?= site_url(array('cost', 'add')) ?>" method="post" class="form-horizontal" id="add_form" role="form"
          data-toggle="validator" onsubmit="return false;">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="UserName">使用者</label>
            <div class="col-sm-10">
                <input type="text" name="UseName" id="UserName" class="form-control ul_input" value="<?=$userInfo->UserName?>">
                <ul class="input_ul" id="UserName_ul">
                    <li class="input_li" data-id="UserName">公共</li>
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
                <input type="text" name="type" id="type" class="form-control ul_input" value="默认">
                <ul class="input_ul" id="type_ul">
                    <li class="input_li" data-id="type" id="0">默认</li>
                    <?php foreach ($costType_list as $value): ?>
                        <li class="input_li" data-id="type" id="<?=$value['typeId']?>"><?=$value['typeName']?></li>
                    <?php endforeach; ?>
                    <li>
                        <input id="add_cost_type" type="text" class="form-control" placeholder="自定义 " data-id="type">
                        <input id="add_cost_type_btn" type="button" class="btn btn-info" value="添加">
                    </li>
                </ul>
            </div>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="Money">金额</label>
            <div class="col-sm-10">
                <input id="Money" name="Money" type="text" placeholder="元" class="form-control input_money"
                       data-error="Money不能为空"
                       data-remote="<?php echo site_url(array('cost', 'check_num')); ?>"
                       data-remote-error="只能输入数字"
                       required>
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
                <input type="submit" value="提交" class="btn btn-info" onclick="submit_form()">
            </div>
        </div>
    </form>
</div>
</body>
<script>
    function submit_form(){
        var form_data = $('#add_form').serializeArray();
        $.ajax({
            url:"<?= site_url(array('cost', 'add')) ?>",
            type:"POST",
            data: form_data,
            dataType: 'JSON',
            success: function (data) {
                console.log("%o",data.msg)
                if(data.status){
                    alert(data.msg)
                    window.location.href = "<?=site_url(array('cost','lists'))?>"
                }else{
                    alert(data.msg)
                }
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert('ajax失败,错误号：'+XMLHttpRequest.readyState +'___'+ XMLHttpRequest.status );
            }
        })
    }
    $(function () {

        if(sessionStorage.getItem('select_type')){
            var type_id = sessionStorage.getItem('select_type')
            var type_name = $('#'+ type_id).html()
            $('#type').val(type_name)
        }
        if(sessionStorage.getItem('select_UserName')){
            $('#UserName').val(sessionStorage.getItem('select_UserName'))
        }
        $('.ul_input').click(function () {
            var id = $(this).attr('id') + '_ul'
            $('ul#' + id).addClass('input_ul_z-index').addClass('input_ul_hover')
        })
        $('.input_li').click(function () {
            var id = $(this).attr('data-id')
            var value = $(this).html()
            console.log(id)
            set_sessionStorage(id,this)
            $('ul#' + id).removeClass('input_ul_z-index')
            $('#' + id).val(value)
        })
        $('#add_cost_type').blur(function () {
            console.log('add_cost_type blur')
            var id = $(this).attr('data-id') + '_ul'
            remove_hover_ul_class(id)
        })
        $('.ul_input').blur(function () {
            var id = $(this).attr('id') + '_ul'
            setTimeout(function () {
                if (!$('#add_cost_type').is(':focus')) {
                    $('ul#' + id).removeClass('input_ul_hover')
                    setTimeout(function () {
                        $('ul#' + id).removeClass('input_ul_z-index')
                    }, 500)
                }
            }, 10)

        })
        $('#add_cost_type_btn').click(function () {
            var typeName = $('#add_cost_type').val()
            $.ajax({
                url: "<?=site_url(array('Cost', 'add_cost_type'))?>",
                type: 'POST',
                data: {'typeName': typeName},
                dataType: 'JSON',
                success: function (data) {
                    console.log("%o", data)
                    if (data.status) {
                        console.log('add_cost_type success')
                        sessionStorage.setItem('select_type', data.msg)
                        location.reload()
                    } else {
                        console.log('add_cost_type failed')
                        alert(data.msg)
                    }
                }
            })
        })
        function remove_hover_ul_class(id) {
            setTimeout(function () {
                if (!$('#add_cost_type').is(':focus')) {
                    $('ul#' + id).removeClass('input_ul_hover')
                    setTimeout(function () {
                        $('ul#' + id).removeClass('input_ul_z-index')
                    }, 500)
                }
            }, 10)
        }
        function set_sessionStorage(id,obj){
            console.log("%o",obj)
            if(id === 'type'){
                var value = $(obj).attr('id')
            }else{
                var value = $(obj).html()
            }
            sessionStorage.setItem('select_'+id,value)
        }


    })
</script>
</html>