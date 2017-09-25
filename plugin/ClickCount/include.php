<?php
/**
 * ClickCount Plugin For Z-Blog
 * 
 * @package     ClickCount.zba
 * @version     1.5.1
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

RegisterPlugin('ClickCount', 'ActivePlugin_ClickCount');

function ActivePlugin_ClickCount() {
    Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags', 'ClickCount_Zbp_MakeTemplatetags');
}

function ClickCount_Zbp_MakeTemplatetags() {
    global $zbp;
    $zbp->header .= '<script type="text/javascript" src="'. $zbp->host .'zb_users/plugin/ClickCount/script.js"></script>';
}

function InstallPlugin_ClickCount() {}
function UninstallPlugin_ClickCount() {}