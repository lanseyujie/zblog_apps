<?php
/**
 * AprilFoolsDay Plugin For Z-Blog
 * 
 * @package     AprilFoolsDay.zba
 * @version     1.0.1
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

RegisterPlugin('AprilFoolsDay', 'ActivePlugin_AprilFoolsDay');

function ActivePlugin_AprilFoolsDay() {
    Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags', 'AprilFoolsDay_Zbp_MakeTemplatetags');
}

function AprilFoolsDay_Zbp_MakeTemplatetags(&$template) {
    global $zbp;
    
    $t = time();
    if ('04' === date('m', $t) && '01' === date('d', $t)) {
        $zbp->header .= '<style>body{-moz-transform:skew(0deg,180deg) scale(-1,1);-o-transform:skew(0deg,180deg) scale(-1,1);transform:rotateY(180deg);filter:fliph
    -webkit-transform: rotateY(180deg);}
</style>';
    }
}

function InstallPlugin_AprilFoolsDay() {}

function UninstallPlugin_AprilFoolsDay() {}