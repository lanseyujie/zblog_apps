<?php
/**
 * CHAvatar Plugin For Z-Blog
 *
 * @package     CHAvatar.zba
 * @version     1.1.11
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://github.com/lanseyujie/ZBlogPHPApps
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2015-2019, lanseyujie.com
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
	global $zbp;

    $id = (int) GetVars('id', 'GET');
	if (
        ($zbp->CheckRights('AvatarEdt') && ($zbp->user->ID == $id))
        || $zbp->CheckRights('root')
    ) {
		echo '<script type="text/javascript">
	        $(".SubMenu").append("<a href=\"../../zb_users/plugin/CHAvatar/main.php?id=' . $id . '\"><span class=\"m-left\">修改头像</span></a>");
	    </script>';
	}
}

function InstallPlugin_CHAvatar() {}
function UninstallPlugin_CHAvatar() {}
