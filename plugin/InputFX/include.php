<?php
/**
 * InputFX Plugin For Z-Blog
 * 
 * @package     InputFX.zba
 * @version     1.0.3
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

RegisterPlugin('InputFX', 'ActivePlugin_InputFX');

function ActivePlugin_InputFX() {
	Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags', 'InputFX_Zbp_MakeTemplatetags');
}

function InputFX_Zbp_MakeTemplatetags() {
	global $zbp;
	$zbp->footer .= '<script src="'. $zbp->host .'zb_users/plugin/InputFX/activate-power-mode.js" type="text/javascript"></script>'."\r\n";
    $zbp->footer .= '<script>
    	POWERMODE.colorful = true; // make power mode colorful
		POWERMODE.shake = true; // turn on shake
		document.body.addEventListener("input", POWERMODE);
    </script>'."\r\n";
}

function InstallPlugin_InputFX() {}
function UninstallPlugin_InputFX() {}