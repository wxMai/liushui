<?php ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/hmtl;charset=UTF-8">
</head>
<body>
<form action="<?=  site_url(array('cost','add'))?>" method="post">
    <input name="money" type="number" placeholder="How much?">
<!--    <input name="type" type="text" placeholder="TYPE">-->
    <input name="note" type="text" placeholder="备注">
    <input type="submit" value="提交">
</form>
</body>
</html>