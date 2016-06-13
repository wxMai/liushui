<?php ?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/hmtl;charset=UTF-8">
    </head>
    <body>
        <form action="<?=  site_url(array('user','login'))?>" method="post">
            <input type="text" placeholder="用户名" name="count" id="count">
            <input type="password" placeholder="密码" name="password">
            <input type="submit" value="登录">
        </form>
    </body>
</html>