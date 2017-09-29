<?php
/**
 * OneQRCPay Plugin For Z-Blog
 * 
 * @package     OneQRCPay.zba
 * @version     1.0.3
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();

if (!$zbp->CheckRights('root')) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('OneQRCPay')) {$zbp->ShowError(48);die();}

if (isset($_POST['alipaylink'])) {
    $zbp->Config('OneQRCPay')->AliPayLink = $_POST['alipaylink'];
    $zbp->Config('OneQRCPay')->DelConfig = $_POST['delconfig'];
    $zbp->SaveConfig('OneQRCPay');
    $zbp->SetHint('good', '参数已保存');
}

if (isset($_GET['act']) && ('qrc' == $_GET['act'])) {
    foreach ($_FILES as $key => $value) {
        if (!strpos($key, '_php')) {
            if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
                $tmp_name = $_FILES[$key]['tmp_name'];
                $name = $_FILES[$key]['name'];
                @move_uploaded_file($_FILES[$key]['tmp_name'], $zbp->usersdir .'plugin/OneQRCPay/src/qrc.png');
            }
        }
    }

    $zbp->SetHint('good', '微信支付码修改成功！');
    Redirect('./main.php');
}

require $blogpath .'zb_system/admin/admin_header.php';
require $blogpath .'zb_system/admin/admin_top.php';

if ('REWRITE' != $zbp->option['ZC_STATIC_MODE']) {
    $gateway = $zbp->host .'?pay=oneqrc';
}
else {
    $gateway = $zbp->host .'pay/oneqrc';
}

?>

<div id="divMain">
    <div class="divHeader">OneQRCPay Plugin For Z-Blog</div>
    <div class="SubMenu">
        <a href="https://github.com/lanseyujie/ZBlogPHPApps/blob/master/plugin/OneQRCPay" target="_blank" title="GitHub"><span class="m-right">项目源码</span></a>
        <a href="https://lanseyujie.com?from=<?php echo $zbp->host; ?>&plugin=OneQRCPay" target="_blank" title="蓝色域界"><span class="m-right">作者博客</span></a>
    </div>
    <div id="divMain2">
        <form id="form1" name="form1" method="post">
            <table width="100%" style="padding:0;margin:0;" cellspacing="0" cellpadding="0" class="tableBorder">
                <tr>
                    <td width="20%" height="50px" align="center">支付链接</td>
                    <td width="40%" align="center">
                        <input name="alipaylink" id="alipaylink" type="url" value="<?php echo $zbp->Config('OneQRCPay')->AliPayLink; ?>" style="width:90%;height:30px;letter-spacing:1px; " required="required" />
                    </td>
                    <td width="40%" align="center">
                        <span style="color:#888;margin-left:20px;font-size:12px;">支付宝收款码的解析链接</span>
                    </td>
                </tr>
                <tr>
                    <td width="20%" height="50px" align="center">完全卸载</td>
                    <td width="40%" align="center">
                        <input name="delconfig" id="delconfig" class="checkbox" value="<?php echo $zbp->Config('OneQRCPay')->DelConfig; ?>" style="display:none;" />
                    </td>
                    <td width="40%" align="center">    
                        <span style="color:#888;margin-left:20px;font-size:12px;">卸载时清空本插件的配置</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" width="100%" height="50px" align="center">
                        <input name="" type="Submit" class="button" value="保存" />
                    </td>
                </tr>
            </table>
        </form>

        <br />

        <form enctype="multipart/form-data" method="post" action="main.php?act=qrc">  
            <table width="100%" style="padding:0;margin:0;" cellspacing="0" cellpadding="0" class="tableBorder">
                <tr>
                    <td width="20%" height="50px" align="center">微信收款码</td>
                    <td width="40%" align="center">
                        <input name="qrc.png" type="file" />
                    </td>
                    <td width="40%" align="center">
                        <span style="color:#888;margin-left:20px;font-size:12px;">上传微信的收款图片</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" width="100%" height="50px" align="center">
                        <input name="" type="Submit" class="button" value="上传" />
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div id="divMain3">
        <h3>您的一码付二维码</h3>
        <div id="oneqrcpay"></div>
    </div>
</div>

<script type="text/javascript" src="jquery.qrcode.min.js"></script>
<script type="text/javascript">
    $("#oneqrcpay").qrcode({
        render: "canvas",
        minVersion: 1,
        ecLevel: "L",
        size: 130,
        text: "<?php echo $gateway; ?>",
    });
    AddHeaderIcon("<?php echo $bloghost .'zb_users/plugin/OneQRCPay/logo.png'; ?>");
</script>

<?php
    require $blogpath .'zb_system/admin/admin_footer.php';
    RunTime();
?>