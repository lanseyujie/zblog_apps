<?php
/**
 * LoginOnlyOne Plugin For Z-Blog
 * 
 * @package     LoginOnlyOne.zba
 * @version     1.3.2
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

RegisterPlugin('LoginOnlyOne', 'ActivePlugin_LoginOnlyOne');

function ActivePlugin_LoginOnlyOne() {
    Add_Filter_Plugin('Filter_Plugin_Zbp_Load', 'LoginOnlyOne_Zbp_Load');
    Add_Filter_Plugin('Filter_Plugin_VerifyLogin_Succeed', 'LoginOnlyOne_VerifyLogin_Succeed');
    Add_Filter_Plugin('Filter_Plugin_Logout_Succeed', 'LoginOnlyOne_Logout_Succeed');
}

function LoginOnlyOne_GetUserAgent($password){
    global $zbp;
    $s = $zbp->path . $password . session_id();
    $s = $s . GetVars('HTTP_USER_AGENT', 'SERVER');
    $s = $s . GetVars('REMOTE_ADDR', 'SERVER');
    return sha1($zbp->guid . sha1 ($s));
}

function LoginOnlyOne_VerifyLogin_Succeed() {
    global $zbp;
    $member = $zbp->GetMemberByID($zbp->user->ID);
    $zbp->StartSession();
    session_regenerate_id();
    $_SESSION['LoginOnlyOne'] = $zbp->user->ID;
    $member->Metas->LoginOnlyOne = LoginOnlyOne_GetUserAgent($zbp->user->Password); 
    $member->Save();
    $zbp->EndSession();
}

function LoginOnlyOne_Logout_Succeed() {
    global $zbp;
    $zbp->StartSession();
    unset($_SESSION['LoginOnlyOne']);
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-3600, $zbp->cookiespath);
    }
    session_destroy();
}

function LoginOnlyOne_Zbp_Load() {
    global $zbp;
    ini_set('session.cookie_path', $zbp->cookiespath);
    ini_set('session.cookie_lifetime', 7*24*60*60); //7天有效期
    ini_set('session.gc_maxlifetime', 7*24*60*60);  //7天有效期
    if ($zbp->user->ID > 0) {
        if ('' != $zbp->GetMemberByID($zbp->user->ID)->Metas->LoginOnlyOne) {
            $zbp->StartSession();
            $suid = GetVars('LoginOnlyOne', 'SESSION');
            if ($zbp->user->ID == $suid) {
                if (LoginOnlyOne_GetUserAgent($zbp->user->Password) == $zbp->GetMemberByID($zbp->user->ID)->Metas->LoginOnlyOne) {
                    return;
                }
                $zbp->EndSession();
                Logout();
                $zbp->ShowError('您的账号已在其它地方登录，您已被强制下线！', __FILE__, __LINE__);
            }
            else {
                $zbp->EndSession();
                Logout();
                $zbp->ShowError('您的登录信息已过期，请重新登录！', __FILE__, __LINE__);
            }
        }
    }
}

function InstallPlugin_LoginOnlyOne() {}

function UninstallPlugin_LoginOnlyOne() {
    global $zbp;
    foreach ($zbp->members as $key => $m) {
        if ($m->Metas->HasKey('LoginOnlyOne')) {
            $m->Metas->Del('LoginOnlyOne');
            $m->Save();
        }
    }
}