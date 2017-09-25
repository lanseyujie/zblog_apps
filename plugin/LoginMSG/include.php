<?php
/**
 * LoginMSG Plugin For Z-Blog
 * 
 * @package     LoginMSG.zba
 * @version     1.0.5
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

RegisterPlugin('LoginMSG', 'ActivePlugin_LoginMSG');

function ActivePlugin_LoginMSG() {
    Add_Filter_Plugin('Filter_Plugin_VerifyLogin_Succeed', 'LoginMSG_VerifyLogin_Succeed');
}

function LoginMSG_Iplocate($ip) {
    $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip='. $ip);  
    if ('' != $ip && '' != $res) {
        $jsonMatches = array();  
        preg_match('#\{.+?\}#', $res, $jsonMatches);
        if (isset($jsonMatches[0])) {
            $json = json_decode($jsonMatches[0], true);
            if (isset($json['city']) && '' != $json['city']) {
                return $json['city'];
            };
        }
    }
    return '未知'. rand(1000, 9999);
}

function LoginMSG_VerifyLogin_Succeed() {
    global $zbp;

    $name = $zbp->user->Name;
    $email = $zbp->user->Email;
    $ip = GetGuestIP();
    $addr = LoginMSG_Iplocate($ip);
    $lastip = $zbp->members[$zbp->user->ID]->Metas->Lastloginip;
    if ('' != $lastip) {
        $lastaddr = LoginMSG_Iplocate($lastip);
    }
    if ('' != $email) {
        require 'mailer.php';
        if (1 == $zbp->Config('LoginMSG')->Succeedmsg) {
            $subject = '账号登录提醒';
            $content = '您在'. $zbp->name .'的账号'. $name .'于'. date('Y-m-d H:i:s', time()) .'成功登录，登录IP为：'. $ip .'（'. $addr .'），如果不是您本人操作，请立即重置密码或联系系统管理员！';
            LoginMSG_Mailer($email, $subject, $content);
        }
        else if ('' != $lastip && $lastaddr != $addr) {
            //$zbp->SetHint('bad', '账号异地登录！');
            $subject = '账号异地登录提醒';
            $content = '您在'. $zbp->name .'的账号'. $name .'于'. date('Y-m-d H:i:s', time()) .'不在常用地区成功登录，登录IP为：'. $ip .'（'. $addr .'），如果不是您本人操作，请立即重置密码或联系系统管理员！';
            LoginMSG_Mailer($email, $subject, $content);
        }
    }
    $zbp->members[$zbp->user->ID]->Metas->Lastloginip = $ip;
    $zbp->members[$zbp->user->ID]->Save();
    $zbp->SetHint('good', '欢迎'. $name .'登录！');
}

function InstallPlugin_LoginMSG() {
	global $zbp;

    //Init Config
    if(!$zbp->Config('LoginMSG')->HasKey('Version')) {
        $zbp->Config('LoginMSG')->Version = '1.0';
        $zbp->Config('LoginMSG')->Openssl = 1;
        $zbp->Config('LoginMSG')->Smtpserver = 'smtp.lanseyujie.com';
        $zbp->Config('LoginMSG')->Smtpport = 465;
        $zbp->Config('LoginMSG')->Servicemailadd = 'service@lanseyujie.com';
        $zbp->Config('LoginMSG')->Servicemailpwd = '1234567890abcdefghij';
        $zbp->Config('LoginMSG')->Succeedmsg = 0;

        $zbp->SaveConfig('LoginMSG');
    }
	$zbp->SetHint('good', '插件LoginMSG已启用！感谢您的使用！');
}

function UninstallPlugin_LoginMSG() {
	global $zbp;

    $zbp->DelConfig('LoginMSG');
	$zbp->SetHint('good', '您已成功卸载插件LoginMSG！一路走来，感谢有你，后会有期！');
}