<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>基于Bootstrup 3可预览的HTML5文件上传插件|DEMO_jQuery之家-自由分享jQuery、html5、css3的插件库</title>
    <link rel="stylesheet/less" type="text/css"
          href="<?php echo base_url(); ?>public/bootstrap_less/custom/bootstrap.less">
    <script type="text/javascript">
        less = {
            env: "production" // 或者"production"/"development"
        };
    </script>
    <script src="<?php echo base_url(); ?>public/bootstrap_less/custom/less-1.7.0.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/test/default.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/test/fileinput.css">
</head>
<body>
<div class="htmleaf-container">
    <header class="htmleaf-header">
        <h1>基于Bootstrup 3可预览的HTML5文件上传插件 <span>An enhanced HTML 5 file input for Bootstrap 3.x with file preview, multiple selection, and more features</span></h1>
        <div class="htmleaf-links">
            <a class="htmleaf-icon icon-htmleaf-home-outline" href="http://www.htmleaf.com/" title="jQuery之家" target="_blank"><span> jQuery之家</span></a>
            <a class="htmleaf-icon icon-htmleaf-arrow-forward-outline" href="http://www.htmleaf.com/html5/html5muban/201505091801.html" title="返回下载页" target="_blank"><span> 返回下载页</span></a>
        </div>
    </header>
    <!--<div class="htmleaf-content bgcolor-8">
        
    </div>-->
    <div class="container kv-main">
        <div class="page-header">
            <h2>Bootstrap File Input Example <small></h2>
        </div>
        <form enctype="multipart/form-data">
            <div class="form-group">
<!--                <input id="file-1" type="file" multiple class="file" data-overwrite-initial="false" data-min-file-count="2">-->
                <input id="file-1" type="file" multiple class="file" data-overwrite-initial="false" name="img_input[]">
            </div>
            <hr>
        </form>
        <button class="btn btn-info" id="submit_btn">tijiao</button>
    </div>
</div>
<script src="<?php echo base_url(); ?>public/bootstrap_less/custom/less-1.7.0.js"></script>
<script src="<?php echo base_url(); ?>public/js/jquery-3.0.0.min.js"></script>
<script src="<?php echo base_url(); ?>public/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>public/js/test/fileinput.js"></script>
<script src="<?php echo base_url(); ?>public/js/test/fileinput_locale_zh.js"></script>
<script>
    $("#file-1").fileinput({
        uploadUrl: "<?=site_url(array('test','do_test'))?>", // you must set a valid URL here else you will get an error
        allowedFileExtensions : ['jpg', 'png','gif'],
        overwriteInitial: false,
        maxFileSize: 1000,
        maxFilesNum: 10,
        uploadAsync:false,
        slugCallback: function(filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    }).on('filebatchuploadsuccess',function(event, data, previewId, index){
        console.log("%o",data)
    })

</script>
</body>
</html>