<?php
/**
 * ScrollBar Plugin For Z-Blog
 * 
 * @package     ScrollBar.zba
 * @version     1.0.1
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

RegisterPlugin('ScrollBar', 'ActivePlugin_ScrollBar');

function ActivePlugin_ScrollBar() {
	Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags', 'ScrollBar_Action');
}
function InstallPlugin_ScrollBar() {
	global $zbp;
	$zbp->SetHint('good', '插件滚动条启用成功！感谢您的使用！');
}
function ScrollBar_Action(&$template) {
	global $zbp;
	$zbp->header .= '<link rel="stylesheet" href="'. $zbp->host .'zb_users/plugin/ScrollBar/src/css/style.css" type="text/css" /><script type="text/javascript" src="'. $zbp->host .'zb_users/plugin/ScrollBar/src/js/script.js"></script>';
	$zbp->footer .= '<div id="scroll" style="top: 0px; display: none;">(0%)</div><div class="go-up" style="display: none;"><span><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA/hJREFUeNrtl0tsE1cUhq+oxKa7LtmwZNdWQqoUHpadsY3HJAbEI2IRNaqyABYpTSh5jMdjxw8gCimorWhANI1TQpoHTuLE4VFA4lkSmoQAQkAQBHDsPFQU1EXZ9HD+UYyoscMQSrth8ctX557z/Zo59965Fl6vV/wfEu+NXycNv5pH+JVyXRhr79oYBtWqIqrdVaJ0X4MFwhgx7V0Ya7PyKxXC61E/KGzuD8tnnxGEMWKYS+X9K8YpWKByh1ACuxdtjIxecZz5i1zHp3VhvCEy2q8E9ixCjlFzQ/0MVpSKstofcly9yYR86k/K75tkTc1qkhDDHOcsQ66Rvs9p6vO4RZCfYtv3rUXyiZm/5RNPKD82QXmxyX8IMcw5OAe5wcoyvVZ7U2N9Ebkr9Z4WNZyusfETOfumKa93Yk4hB7moQS0YmhHjF/1UdgqP1/dhwdGhqPUkmzJ0dU/SkJCLGtSCAVamvqf1U9NfbfmuuiWuyP1b1uMz5IwmyNkDJQ0qodegFgywwARbSzfWAx5VhLg3Jd/86FwVTT61xf4guXucZIbMS1wLBlhggg2PlDkPfMKn8iKq+loUH+gqlWJPyN4zSY7uOGs8sxhs653WhXHWPGaABSbY8IAXPIXfrR8KCzb/dP6wuW+G7N0JWtUVzygHyx6doNyuBK1tuPQbhDFijiw1EJhgwwNefl50/KQ7xKbw5TZTjE2R2PmY7BmEuBSdIvOxx1Rc17QViwYqrgtvRQxzc9WCDQ/2asdrF58f7FOsndyPLEX2zkf6r5nBUsvt6S93fWsJ8SvDIQFhzDEz5pDzck264CHxG/riQFeJyGu5eccaeUS2bOJkU/cUyY1Xhyp8ocWhl7ZHSoiV85zcODCIXNRk40msNc3DA8J1ZPi69dhDyiQpEqcVnRO0vv7XVk11Lwyola/syRd7H3Ocs77+1C+oQW1GJmtd08BFkf/z0IjUMUbpskTGaXlHnAr3t/kC/N2t9sz96dNPO85B3wv3t3pRC0Y6N5e1Ltx/QeQdGR7JbX9AKUkdD8gUSZCpZfTZlppDBSGFt4DBj71+vqPvXLOl5mABGGCBmeJbWGsbr1wQq/mJLbMBTCyPJEkKXxsr89d+it6lnzjGbiia3ncwwAIzZWpmrdGNmwZHLG33OTBGOR3j5Dx07qyi+T4Kucvf6MOe6cwHAywwwYaHmb1cMHY2Dd4wtT+kz9ritOG7aD2uMn61at6mr9xamAUm2PBYyV75jf2XhK3h6r2c5rtUVBsuCeqLSH0rw8yLThVgwwNe8uHLv4vtwX1LSwN7Pw7i6JznjdHozRQe8PoqWPeJCGALsLxv+WoNmWO/s5ef9f6fxH+m55Sc0sKe/hprAAAAAElFTkSuQmCC" /></span></div>';
}
function UninstallPlugin_ScrollBar() {
	global $zbp;
	$zbp->SetHint('good', '您已成功卸载滚动条插件！后会有期！');
}