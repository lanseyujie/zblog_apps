<?php
/**
 * CHAvatar Plugin For Z-Blog
 * 
 * @package     CHAvatar.zba
 * @version     1.0.3
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

RegisterPlugin('CHAvatar', 'ActivePlugin_CHAvatar');

function ActivePlugin_CHAvatar() {
	Add_Filter_Plugin('Filter_Plugin_Zbp_Load_Pre', 'CHAvatar_Zbp_Load_Pre');
	Add_Filter_Plugin('Filter_Plugin_Member_Edit_Response', 'CHAvatar_Member_Edit_Response');
}

function CHAvatar_Zbp_Load_Pre() {
    global $zbp;
    $zbp->actions['AvatarEdt'] = 5;
    $zbp->lang['actions']['AvatarEdt'] = '头像编辑';
}

function CHAvatar_Member_Edit_Response() {
	global $action,$zbp;
	if ($zbp->CheckRights('AvatarEdt') && $zbp->user->ID == GetVars('id', 'GET')) {
		echo '<script type="text/javascript">
	$("div.SubMenu").append("<a href=\"../../zb_users/plugin/CHAvatar/main.php\"><span class=\"m-left\">修改头像</span></a>");
	</script>';
	}
	
}

function InstallPlugin_CHAvatar() {}
function UninstallPlugin_CHAvatar() {}