<?php
/**
 * StickyLayout Plugin For Z-Blog
 *
 * @package     StickyLayout.zba
 * @note        粘滞布局
 * @version     1.0
 * @date        2021/02/09
 * @author      Wildlife <admin@lanseyujie.com>
 * @source      https://github.com/lanseyujie/zblog_apps
 * @link        https://lanseyujie.com
 */

RegisterPlugin("StickyLayout","ActivePlugin_StickyLayout");

function ActivePlugin_StickyLayout() {
    Add_Filter_Plugin('Filter_Plugin_Admin_Header', 'StickyLayout_Admin_Header');
    Add_Filter_Plugin('Filter_Plugin_Admin_TopMenu', 'StickyLayout_Admin_TopMenu');
}

function InstallPlugin_StickyLayout() {}

function UninstallPlugin_StickyLayout() {}

function StickyLayout_Admin_Header() {
    global $zbp;

    $style = '<style>';

    // 粘滞布局
    $style .= 'body {position: absolute !important; width: 100% !important;} .admin .header {z-index: 2021; top: 0 !important; position: sticky !important;} .admin .left {z-index: 2021; top: 90px !important; position: sticky !important;}';

    // 圆形头像
    $style .= '.user #avatar {border-radius: 100%;}';

    // 屏蔽信息
    $style .= 'li#topmenu8, table#tbUpdateInfo, table#thankslist {display:none;}';

    $style .= '</style>';

    echo $style;
}

function StickyLayout_Admin_TopMenu(&$topmenus) {
    global $zbp;

    // 受应用中心插件 CSP（Content Security Policy）影响，此页面下按钮失效
    $nav = MakeTopMenu('root', '清缓重编', 'javascript:statistic(\'' . BuildSafeCmdURL('act=misc&type=statistic&forced=1') . '\');', '', 'statistic', 'icon-arrow-repeat');
    array_unshift($topmenus, $nav);
}
