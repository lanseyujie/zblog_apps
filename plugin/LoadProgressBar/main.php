<?php
/**
 * LoadProgressBar Plugin For Z-Blog
 * 
 * @package     LoadProgressBar.zba
 * @version     1.1.0
 * @author      森林生灵 <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();

if (!$zbp->CheckRights('root')) {
    $zbp->ShowError(6);
    die();
}
if (!$zbp->CheckPlugin('LoadProgressBar')) {
    $zbp->ShowError(48);
    die();
}

if (isset($_POST['Color'])) {
    $zbp->Config('LoadProgressBar')->Color = $_POST['Color'];
    $zbp->Config('LoadProgressBar')->Style = $_POST['Style'];
    $zbp->SaveConfig('LoadProgressBar');
    $zbp->SetHint('good', '参数已保存，刷新首页可以查看是否生效');
}

require $blogpath .'zb_system/admin/admin_header.php';
require $blogpath .'zb_system/admin/admin_top.php';
?>
<div id="divMain">
    <div class="divHeader">页面加载进度条</div>
    <div class="SubMenu">
        <a href="https://lanseyujie.com" target="_blank" title="蓝色域界"><span class="m-right">作者博客</span></a>
    </div>
    <div id="divMain2">
        <form id="form1" name="form1" method="post">
            <table width="100%" style="padding:0;margin:0;" cellspacing="0" cellpadding="0" class="tableBorder">
                <tr>
                    <td width="15%" height="30" align="center">选择颜色</td>
                    <td width="35%">
                        <div align="center">
                            <select name="Color" id="Color">
                                <option value="black" <?php echo $zbp->Config('LoadProgressBar')->Color=='black' ? 'selected':''; ?>>黑色</option>
                                <option value="blue" <?php echo $zbp->Config('LoadProgressBar')->Color=='blue' ? 'selected':''; ?>>蓝色</option>
                                <option value="green" <?php echo $zbp->Config('LoadProgressBar')->Color=='green' ? 'selected':''; ?>>绿色</option>
                                <option value="orange" <?php echo $zbp->Config('LoadProgressBar')->Color=='orange' ? 'selected':''; ?>>橙色</option>
                                <option value="pink" <?php echo $zbp->Config('LoadProgressBar')->Color=='pink' ? 'selected':''; ?>>粉色</option>
                                <option value="purple" <?php echo $zbp->Config('LoadProgressBar')->Color=='purple' ? 'selected':''; ?>>紫色</option>
                                <option value="red" <?php echo $zbp->Config('LoadProgressBar')->Color=='red' ? 'selected':''; ?>>红色</option>
                                <option value="silver" <?php echo $zbp->Config('LoadProgressBar')->Color=='silver' ? 'selected':''; ?>>银色</option>
                                <option value="white" <?php echo $zbp->Config('LoadProgressBar')->Color=='white' ? 'selected':''; ?>>白色</option>
                                <option value="yellow" <?php echo $zbp->Config('LoadProgressBar')->Color=='yellow' ? 'selected':''; ?>>黄色</option>
                            </select>
                        </div>                  
                    </td>
                    <td width="15%" height="30" align="center">选择样式</td>
                    <td width="35%">
                        <div align="center">
                            <select name="Style" id="Style">
                                <option value="pace-theme-barber-shop" <?php echo $zbp->Config('LoadProgressBar')->Style='pace-theme-barber-shop' ? 'selected':''; ?>>旋转彩柱</option>
                                <option value="pace-theme-big-counter" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-big-counter' ? 'selected':''; ?>>大百分比</option>
                                <option value="pace-theme-bounce" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-bounce' ? 'selected':''; ?>>弹跳淡出</option>
                                <option value="pace-theme-center-atom" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-center-atom' ? 'selected':''; ?>>中心原子</option>
                                <option value="pace-theme-center-circle" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-center-circle' ? 'selected':''; ?>>中心旋圈</option>
                                <option value="pace-theme-center-radar" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-center-radar' ? 'selected':''; ?>>雷达扫描</option>
                                <option value="pace-theme-center-simple" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-center-simple' ? 'selected':''; ?>>细进度条</option>
                                <option value="pace-theme-corner-indicator" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-corner-indicator' ? 'selected':''; ?>>封角指示</option>
                                <option value="pace-theme-fill-left" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-fill-left' ? 'selected':''; ?>>全屏加载</option>
                                <option value="pace-theme-flash" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-flash' ? 'selected':''; ?>>顶部细条</option>
                                <option value="pace-theme-flat-top" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-flat-top' ? 'selected':''; ?>>顶部粗条</option>
                                <option value="pace-theme-loading-bar" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-loading-bar' ? 'selected':''; ?>>粗进度条</option>
                                <option value="pace-theme-mac-osx" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-mac-osx' ? 'selected':''; ?>>OSX样式</option>
                                <option value="pace-theme-material" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-material' ? 'selected':''; ?>>中心空圈</option>
                                <option value="pace-theme-minimal" <?php echo $zbp->Config('LoadProgressBar')->Style=='pace-theme-minimal' ? 'selected':''; ?>>极简样式</option>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>
            <br />
            <input name="" type="Submit" class="button" value="保存" />
        </form>
        <i>插件用到的开源项目 https://github.com/HubSpot/PACE</i>
    </div>
</div>
<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost; ?>zb_users/plugin/LoadProgressBar/logo.png");</script>
<?php
    require $blogpath .'zb_system/admin/admin_footer.php';
    RunTime();
?>