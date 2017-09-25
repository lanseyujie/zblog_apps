<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('LoginMSG')) {$zbp->ShowError(48);die();}

if ('MailerTest' == GetVars('act', 'GET')) {
    $Client_Mail = GetVars('testmail', 'GET');
    if ('' != $Client_Mail) {
        require 'mailer.php';
        $TestResult = LoginMSG_Mailer($Client_Mail, 'Mailer Test', '<i style="color:red;">This Mailer was Sent From LANSEYUJIE WebMailer v1.0</i>', 1);
    }
    else {
        $TestResult = 'E-mail Address Not Found!';
    }
    die($TestResult);
}

$blogtitle='异地登录提醒';
require '../../../zb_system/admin/admin_header.php';
require '../../../zb_system/admin/admin_top.php';
?>

<script type="text/javascript">
$(function() {
    $("#testsend").click(function() {
        var email = document.getElementById("testmailto").value;
        var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
        if ("" != email && pattern.test(email)) {
            $("#testresult").html("邮件发送中...");
            url = "<?php echo $zbp->host ?>zb_users/plugin/LoginMSG/main.php?act=MailerTest&testmail="+email;
            $.get(url, {sid: Math.random()}, function(result) {
                if ('' != $.trim(result)) {
                    $("#testresult").html(result);
                }
                else {
                    $("#testresult").html("发送失败！");
                }
            });
        }
        else {
            alert("请输入正确格式的邮箱地址！");
            document.getElementById("testmailto").value = "";
            return false;
        }
    });
});
</script>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle;?></div>
  <div class="SubMenu">
        <a href="<?php echo $zbp->host ?>zb_users/plugin/LoginMSG/main.php"><span class="m-left">配置首页</span></a>
        <a href="https://lanseyujie.com" target="_blank" title="蓝色域界"><span class="m-right">作者博客</span></a>
  </div>
  <div id="divMain2">
    <!--代码-->
    <?php
    if(isset($_POST['Smtpserver'])){
        $zbp->Config('LoginMSG')->Smtpserver = $_POST['Smtpserver'];
        $zbp->Config('LoginMSG')->Smtpport = $_POST['Smtpport'];
        $zbp->Config('LoginMSG')->Servicemailadd = $_POST['Servicemailadd'];
        $zbp->Config('LoginMSG')->Servicemailpwd = $_POST['Servicemailpwd'];
        $zbp->Config('LoginMSG')->Openssl = $_POST['Openssl'];
        $zbp->Config('LoginMSG')->Succeedmsg = $_POST['Succeedmsg'];
        $zbp->SaveConfig('LoginMSG');
        $zbp->SetHint('good', '配置已保存');
        Redirect('./main.php');
    }
    ?>
    <form id="form1" name="form1" method="post"> 
        <table width="100%" style='padding:0;margin:0;' cellspacing='0' cellpadding='0' class="tableBorder">
            <tr>
                <th width="20%"><p align="center">配置名称</p></th>
                <th width="40%"><p align="center">配置内容</p></th>
                <th width="40%"><p align="center">测试配置</p></th>
            </tr>
            <tr>
                <td align="center">SMTP地址</td>
                <td align="center"><input name="Smtpserver" type="text" id="Smtpserver" placeholder="例如smtp.lanseyujie.com" value="<?php echo $zbp->Config('LoginMSG')->Smtpserver;?>" required="required" /></td>
                <td align="center"><input name="testmailto" type="email" id="testmailto" placeholder="请填写测试收件邮箱" value="" /></td>
            </tr>
            <tr>
                <td align="center">SMTP端口</td>
                <td align="center"><input name="Smtpport" type="number" id="Smtpport" placeholder="默认无加密端口为25" value="<?php echo $zbp->Config('LoginMSG')->Smtpport;?>" required="required" /></td>
                <td align="center">测试结果</td>
            </tr>
            <tr>
                <td align="center">发信邮箱</td>
                <td align="center"><input name="Servicemailadd" type="email" id="Servicemailadd" placeholder="请填写发信邮箱地址" value="<?php echo $zbp->Config('LoginMSG')->Servicemailadd;?>" required="required" /></td>
                <td align="center" rowspan="4"><div id="testresult" style="height:108px; padding:10px; border:1px dashed #ccc; overflow:auto;/*background-color:#bbd9e2;*/"></div></td>
            </tr>
            <tr>
                <td align="center">发信密码</td>
                <td align="center"><input name="Servicemailpwd" type="password" id="Servicemailpwd" laceholder="请填写发信密码" value="<?php echo $zbp->Config('LoginMSG')->Servicemailpwd; ?>" required="required" /></td>
            </tr>
            <tr>
                <td align="center">登录提醒</td>
                <td align="center"><input name="Succeedmsg" type="text" id="Succeedmsg" class="checkbox" value="<?php echo $zbp->Config('LoginMSG')->Succeedmsg; ?>" required="required" /></td>
            </tr>
            <tr>
                <td align="center">SSL加密</td>
                <td align="center"><input name="Openssl" type="text" id="Openssl" class="checkbox" value="<?php echo $zbp->Config('LoginMSG')->Openssl; ?>" required="required" /></td>
            </tr>
            <tr>
                <td align="center"><div>配置保存</div></td>
                <td align="center"><input name="" type="Submit" class="button" value="保　存" /></td>
                <td align="center"><input id="testsend" type="button" value="测　试" /></td>
            </tr>
        </table>
    </form>
  </div>
</div>
<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost .'zb_users/plugin/LoginMSG/logo.png';?>");</script>
<?php
    require $blogpath .'zb_system/admin/admin_footer.php';
    RunTime();
?>