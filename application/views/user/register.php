<?php ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/user/register.css">
    <link rel="stylesheet/less" type="text/css"
          href="<?php echo base_url(); ?>public/bootstrap_less/custom/bootstrap.less">
    <script type="text/javascript">
        less = {
            env: "production" // 或者"production"/"development"
        };
    </script>
<!--    <script src="http://cdn.bootcss.com/less.js/1.7.0/less.min.js"></script>less-1.7.0.js-->
<!--    <script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>jquery-3.0.0.min.js-->
<!--    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
    <script src="<?php echo base_url(); ?>public/bootstrap_less/custom/less-1.7.0.js"></script>
    <script src="<?php echo base_url(); ?>public/js/jquery-3.0.0.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/validator.js"></script>
</head>
<body>
<?php $this->load->view('header');?>
<div class="form_div">
    <form action="" method="post" class="form-inline" role="form"
          data-toggle="validator" id="register_form" onsubmit="">
        <div class="form-group has-feedback">
            <input type="text" placeholder="用户名" name="UserName" id="UserName" class="form-control" data-error="请填写名称"
                   pattern="^(?!_)(?!.*?_$)[a-zA-Z0-9_\u4e00-\u9fa5]+$" data-pattern-error="包含汉字、数字、字母、下划线，下划线不开始结尾"
                   data-remote="<?php echo site_url(array('user', 'check_register_name')); ?>"
                   data-remote-error="用户名已存在"
                   required>
            <!--            pattern="/^[^0-9.,]+$/" title="名称不能包含数字与标点符号" data-error="请填写名称"-->
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback">
            <input type="password" placeholder="密码" name="password" class="form-control" id="password"
                   data-minlength="6" data-minlength-error="密码不能小于6位"
                   data-error="请输入密码" required>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback">
            <input type="password" placeholder="确认密码" name="sure_password" class="form-control" data-match="#password"
                   data-error="请确认密码"
                   data-match-error="两次密码不一致"
                   required>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <input type="submit" value="注册" class="btn btn-info">
        </div>
    </form>
</div>

</body>
</html>
<script>
    function register() {
        var form_data = $('#register_form').serializeArray();
        $.ajax({
            type: 'POST',
            url: "<?php echo site_url(array('user','do_register'))?>",
            data: form_data,
            dataType: 'JSON',
            success: function (data) {
                if(data.status){
                    alert(data.msg)
                    window.location.href = "<?php echo base_url()?>"
                }else{
                    alert(data.msg)
                }
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert('ajax失败')
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status );
            }
        });
    }
    //自定义HTML5 验证不通过提示
    //    document.addEventListener("DOMContentLoaded", function() {
    //        var elements = document.getElementsByTagName("INPUT");
    //        for (var i = 0; i < elements.length; i++) {
    //            elements[i].oninvalid = function(e) {
    //                e.target.setCustomValidity("");
    //                if (!e.target.validity.valid) {
    //                    e.target.setCustomValidity("This field cannot be left blank");
    //                }
    //            };
    //            elements[i].oninput = function(e) {
    //                e.target.setCustomValidity("");
    //            };
    //        }
    //    })

    $('#register_form').on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            // handle the invalid form...
            alert('11')
        } else {
            // everything looks good!
            alert('22')
            register();
        }
        return false;
    })
</script>