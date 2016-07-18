<?php ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
<body>
<form action="<?= site_url(array('user', 'test')) ?>" method="post" enctype="multipart/form-data">
    <div id="imglist">

    </div>
    <input type="file" name="file[]" id="file_img" value="" multiple>
<!--    <input type="text" name="file_text[]">-->
<!--    <input type="file" name="file[]">-->
<!--    <input type="text" name="file_text[]">-->
<!--    <input type="file" name="file[]">-->
    <input type="text" name="file_text[]">
<!--    <input type="password" placeholder="密码" name="password">-->
    <input type="submit" value="submit">
</form>
</body>
</html>
<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
<script>
    $("#file_img").change(function (e) {
        alert(e.target.files.length);
            for (var i = 0; i < e.target.files.length; i++) {
            var file = e.target.files.item(i);
            //允许文件MIME类型 也可以在input标签中指定accept属性
            //console.log(/^image\/.*$/i.test(file.type));
            if (!(/^image\/.*$/i.test(file.type))) {
                continue;            //不是图片 就跳出这一次循环
            }

            //实例化FileReader API
            var freader = new FileReader();
            freader.readAsDataURL(file);
            freader.onload = function (e) {
                var img = '<img src="' + e.target.result + '" width="50px" height="50px" border-radius="25px"/>';
                showimg(e.target.result);
            }
        }
    });
    function showimg(url) {
        var img = '<img src="' + url + '"/>';
        $('#imglist').append(img);
    }
</script>