<?php
/**
 * LoadProgressBar Plugin For Z-Blog
 * 
 * @package     LoadProgressBar.zba
 * @version     1.1.0
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

RegisterPlugin('LoadProgressBar', 'ActivePlugin_LoadProgressBar');

function ActivePlugin_LoadProgressBar() {
    Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags', 'LoadProgressBar_Zbp_MakeTemplatetags');
}

function LoadProgressBar_Zbp_MakeTemplatetags(&$template) {
    global $zbp;

    $zbp->header .= '<link type="text/css" href="'. $zbp->host .'zb_users/plugin/LoadProgressBar/pace/themes/'. $zbp->Config('LoadProgressBar')->Color .'/'. $zbp->Config('LoadProgressBar')->Style .'.css" rel="stylesheet" /><script type="text/javascript" src="'. $zbp->host .'zb_users/plugin/LoadProgressBar/pace/pace.min.js?v=1.0.2"></script>';
}

function InstallPlugin_LoadProgressBar() {
    global $zbp;

    if (!$zbp->Config('LoadProgressBar')->HasKey('Version')) {
        $zbp->Config('LoadProgressBar')->Version = '1.1';
        $zbp->Config('LoadProgressBar')->Color = 'orange';
        $zbp->Config('LoadProgressBar')->Style = 'pace-theme-loading-bar';
        $zbp->SaveConfig('LoadProgressBar');
    }

    $zbp->SetHint('good', '页面加载进度条启用成功！感谢您的使用！');
}

function UninstallPlugin_LoadProgressBar() {
    global $zbp;

    $zbp->DelConfig('LoadProgressBar');
    $zbp->SetHint('good', '您已成功卸载页面加载进度条并删除配置信息！后会有期！');
}
