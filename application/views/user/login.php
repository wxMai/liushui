<?php ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/user/login.css">
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
<div class="back_div" style="background-image: url('<?=base_url().'public/img/banner1.jpg'?>')"></div>
<div class="form_div">
    <form action="<?= site_url(array('user', 'login')) ?>" method="post" class="form-inline" role="form"
          data-toggle="validator" id="login_form">
        <div class="form-group has-feedback">
            <input type="text" placeholder="用户名" name="UserName" id="UserName" class="form-control"
                   data-error="用户名不能为空。"
                   data-remote="<?php echo site_url(array('user', 'check_login_name')); ?>"
                   data-remote-error="用户名不存在"
                   required>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback">
            <input type="password" placeholder="密码" name="password" class="form-control" data-error="请输入密码"
                   data-minlength="6" data-minlength-error="密码不能小于6位"
                   required>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback">
            <input type="submit" value="登录" class="btn btn-info">
        </div>
    </form>
</div>
</body>
</html>
<script>
    function login() {
        var form_data = $('#login_form').serializeArray();
        $.ajax({
            type: 'POST',
            url: "<?php echo site_url(array('user','do_login'))?>",
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
                alert('ajax失败,错误号：'+XMLHttpRequest.readyState +'___'+ XMLHttpRequest.status );
            }
        });
    }

    $('#login_form').on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            alert('无效登录信息')
        } else {
            login();
        }
        return false;
    })
</script>