<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
if (!$zbp->CheckRights('AvatarEdt')) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('CHAvatar')) {$zbp->ShowError(48);die();}

require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';


function GetEditUserID() {
    global $zbp;

    if ($zbp->CheckRights('root') && $_GET['id']) {
        $id = (int)$_GET['id'];
    } else {
        $id = $zbp->user->ID;
    }

    return $id;
}

function GetEditUserAvatar() {
    global $zbp;

    $id = GetEditUserID();
    $user = $zbp->members[$id];
    $avatar = $user->Avatar;
    
    return $avatar;
}

function SaveUpload() {
    global $zbp;

    $img = GetVars('useravatar', 'POST');
    if ($img) {
        $fullpath = $zbp->usersdir .'avatar';
        if (!is_dir($fullpath)) {
            mkdir($fullpath, 0777, true);
        }
        $types = array('jpg', 'gif', 'png', 'jpeg');
        // SafeBase64 转换，未用
        $img = str_replace(array('_', '-'), array('/', '+'), $img);

        $head = substr($img, 0, 100);
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $head, $matches)) {
            $type = $matches[2];
            if (!in_array($type, $types)) {
                echo '<script>alert("图片格式不正确！")</script>';
            }
            // 去掉 data:image/png;base64,
            $base64img = str_replace($matches[1], '', $img);
            
            $id = GetEditUserID();
            
            $file = $fullpath . '/' . $id . '.' . $type;
            // 解码
            $content = base64_decode($base64img);

            file_put_contents($file, $content);
            // echo '<script>alert("保存图片成功'.$id. $file.'！")</script>';
            Redirect($zbp->host . 'zb_system/admin/member_edit.php?act=MemberEdt&id=' . $id);
        }
    }
}
SaveUpload();
?>
<div id="divMain">
    <div class="divHeader">修改头像</div>
    <div class="SubMenu"></div>
    <div id="divMain2">
        <style>.container{width:400px;position:relative;font-size:12px}
    .imageBox{position:relative;height:400px;width:400px;border:1px solid #aaa;background:#fff;overflow:hidden;background-repeat:no-repeat;cursor:move;box-shadow:4px 4px 12px #B0B0B0}
    .imageBox .thumbBox{position:absolute;top:50%;left:50%;width:200px;height:200px;margin-top:-100px;margin-left:-100px;box-sizing:border-box;border:1px solid #666;box-shadow:0 0 0 1000px rgba(0,0,0,.5);background:none repeat scroll 0 0 transparent}
    .imageBox .spinner{position:absolute;top:0;left:0;bottom:0;right:0;text-align:center;line-height:400px;background:rgba(0,0,0,.7)}
    .action{width:410px;height:30px;margin:10px 0}
    .cropped{position:absolute;right:-230px;top:0;width:200px;border:1px #ddd solid;height:488px;padding:4px;box-shadow:0 0 12px #ddd;text-align:center}
        </style>
        <script type="text/javascript" src="js/cropbox.js"></script>
        <form id="avatarform" action="main.php?id=<?php echo GetEditUserID(); ?>" method="post" enctype="multipart/form-data" /> 
            <div class="container">
                <div class="imageBox">
                    <div class="thumbBox"></div>
                    <div class="spinner" style="display: none">Loading...</div>
                </div>
                <div class="action"> 
                    <input type="file" id="upload-file" name="upload-file" />
                    <input type="button" id="btnZoomIn" value="放大" />
                    <input type="button" id="btnZoomOut" style="float: right;" value="缩小" />
                    <br /><br />
                    <input type="button" id="btnCrop" value="裁切" />
                    <input type="button" id="btnSubmit" style="float: right;" value="提交" />
                </div>
                <div class="cropped"></div>
            </div>
        </form>
        <script type="text/javascript">$(window).load(function(){var avatarurl = '<?php echo GetEditUserAvatar(); ?>';var options={thumbBox:'.thumbBox',spinner:'.spinner',imgSrc:avatarurl};var cropper=$('.imageBox').cropbox(options);$('#upload-file').on('change',function(){var reader=new FileReader();reader.onload=function(e){options.imgSrc=e.target.result;cropper=$('.imageBox').cropbox(options)};reader.readAsDataURL(this.files[0]);this.files[0]=''});$('#btnCrop').on('click',function(){var img=cropper.getDataURL();$('.cropped').html('');$('.cropped').append('<img src="'+img+'" align="absmiddle" style="width:64px;margin-top:4px;border-radius:64px;box-shadow:0px 0px 12px #7E7E7E;" ><p>64px*64px</p>');$('.cropped').append('<img src="'+img+'" align="absmiddle" style="width:128px;margin-top:4px;border-radius:128px;box-shadow:0px 0px 12px #7E7E7E;"><p>128px*128px</p>');$('.cropped').append('<img src="'+img+'" align="absmiddle" style="width:180px;margin-top:4px;border-radius:180px;box-shadow:0px 0px 12px #7E7E7E;"><p>180px*180px</p>');$('.cropped').append('<input id="useravatar" type="hidden" value="'+img+'" name="useravatar">')});$('#btnZoomIn').on('click',function(){cropper.zoomIn()});$('#btnZoomOut').on('click',function(){cropper.zoomOut()});$('#btnSubmit').on('click',function(){if(document.getElementById("useravatar")){document.getElementById("avatarform").submit();}else{alert('请先裁切再提交！');}});});AddHeaderIcon("<?php echo $bloghost .'zb_users/plugin/CHAvatar/logo.png';?>");</script>
    </div>
</div>
<?php
    require $blogpath . 'zb_system/admin/admin_footer.php';
    RunTime();
?>