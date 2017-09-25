<?php
/**
 * ContextMenu Plugin For Z-Blog
 * 
 * @package     ContextMenu.zba
 * @version     1.5.1
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

RegisterPlugin('ContextMenu', 'ActivePlugin_ContextMenu');

function ActivePlugin_ContextMenu() {
    Add_Filter_Plugin('Filter_Plugin_Html_Js_Add', 'ContextMenu_Html_Js_Add');
}

function ContextMenu_Html_Js_Add() {
    global $zbp;

    $ContextMenu = '';
    if ($zbp->Config('ContextMenu')->usefontawesome) {
        $ContextMenu .= '<link rel=\'stylesheet\' type=\'text/css\' href=\''. $zbp->host .'zb_users/plugin/ContextMenu/src/font-awesome/css/font-awesome.min.css\'/>';
    }
    if (!$zbp->Config('ContextMenu')->usebgpic) {
        $ContextMenu .= '<style type=\'text/css\'>div.usercm{background:#fff !important;}</style>';
    }
    $cm = '<div class="usercm"><ul>'. $zbp->Config('ContextMenu')->usercml .'</ul></div>';
    echo '$(document).ready(function() {$("body").append(\''. $cm .'\');});';
    echo "\r\n" . 'document.writeln("<script src=\''. $zbp->host. 'zb_users/plugin/ContextMenu/src/js/script.js?v=20170826\' type=\'text/javascript\'></script>'. $ContextMenu .'<link rel=\'stylesheet\' type=\'text/css\' href=\''. $zbp->host .'zb_users/plugin/ContextMenu/src/css/style.css?v=20170823\'/>");';
}

function InstallPlugin_ContextMenu() {
    global $zbp;

    if (!$zbp->Config('ContextMenu')->HasKey('Version')) {
        $zbp->Config('ContextMenu')->Version = '1.5';
        $zbp->Config('ContextMenu')->usebgpic = false;
        $zbp->Config('ContextMenu')->usefontawesome = true;
        $zbp->Config('ContextMenu')->usercml = '<li><a href="javascript:void(0);" onclick="getSelect();"><i class="fa fa-clipboard fa-fw"></i><span>复制文字</span></a></li><li><a href="javascript:window.location.reload();"><i class="fa fa-refresh fa-fw"></i><span>刷新页面</span></a></li><li><a href="javascript:history.go(1);"><i class="fa fa-arrow-right fa-fw"></i><span>前进一页</span></a></li><li><a href="javascript:history.go(-1);"><i class="fa fa-arrow-left fa-fw"></i><span>后退一页</span></a></li><li><a href="javascript:void(0);" onclick="checkElement();" class="disabled"><i class="fa fa-check fa-fw"></i><span>调试检查</span></a></li><li><a href="javascript:void(0);" onclick="localSearch();"><i class="fa fa-search fa-fw"></i><span>站内搜索</span></a></li><li><a href="javascript:void(0);" onclick="baiduSearch();"><i class="fa fa-paw fa-fw"></i><span>百度搜索</span></a></li><li><a href="javascript:void(0);" onclick="googleSearch();"><i class="fa fa-google fa-fw"></i><span>谷歌搜索</span></a></li>';
        $zbp->SaveConfig('ContextMenu');
    }

    $zbp->SetHint('good', '右键菜单启用成功！感谢您的使用！');
}

function UninstallPlugin_ContextMenu() {
    global $zbp;
    $zbp->DelConfig('ContextMenu');
    $zbp->SetHint('good', '您已成功卸载右键菜单并删除配置信息！后会有期！');
}
