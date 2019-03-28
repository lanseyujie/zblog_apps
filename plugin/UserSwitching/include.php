<?php
/**
 * UserSwitching Plugin For Z-Blog
 *
 * @package     UserSwitching.zba
 * @version     1.0.0
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://github.com/lanseyujie/ZBlogPHPApps
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2015-2020, lanseyujie.com
 */

RegisterPlugin('UserSwitching', 'ActivePlugin_UserSwitching');

function ActivePlugin_UserSwitching() {
    Add_Filter_Plugin('Filter_Plugin_Zbp_Load_Pre', 'UserSwitching_Zbp_Load_Pre');
    Add_Filter_Plugin('Filter_Plugin_Cmd_Begin', 'UserSwitching_Cmd_Begin');
    Add_Filter_Plugin('Filter_Plugin_Admin_MemberMng_Table', 'UserSwitching_Admin_MemberMng_Table');
    Add_Filter_Plugin('Filter_Plugin_Admin_Js_Add', 'UserSwitching_Js_Add');
    Add_Filter_Plugin('Filter_Plugin_Html_Js_Add', 'UserSwitching_Js_Add');
}

// 权限注入
function UserSwitching_Zbp_Load_Pre() {
    global $zbp;

    $zbp->actions['UserSwitching'] = 1;
    $zbp->lang['actions']['UserSwitching'] = '用户切换';
}

function UserSwitching_Cmd_Begin() {
    global $zbp;

    $action = GetVars('act', 'GET');
    $uid = (int)GetVars('UserSwitchingID', 'GET');

    if ($action == 'login' && isset($uid) && $uid > 0) {
        UserSwitching_SetLoginCookie($uid);
        UserSwitching_VerifyCallback(urldecode(urldecode(GetVars('callback', 'GET'))));
    } elseif ($action == 'logout') {
        setcookie('us-username-su', '', time() - 3600, $zbp->cookiespath);
        setcookie('us-token-su', '', time() - 3600, $zbp->cookiespath);
        setcookie('us-username-last', '', time() - 3600, $zbp->cookiespath);
        setcookie('us-token-last', '', time() - 3600, $zbp->cookiespath);
    }
}

function UserSwitching_Admin_MemberMng_Table(&$member, &$tabletds, &$tableths) {
    global $zbp;

    if ($zbp->CheckRights('UserSwitching') && $member->ID != $zbp->user->ID) {
        $tabletds[9] = substr($tabletds[9], 0, -5);
        $tabletds[9] .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="../cmd.php?act=login&amp;UserSwitchingID=' . $member->ID . '&amp;callback=' . $zbp->host . 'zb_system/cmd.php?act=admin"><img src="../image/admin/ok.png" alt="切换到 ' . $member->Name . ' 的账户" title="切换到 ' . $member->Name . ' 的账户" width="16" /></a>';
        $tabletds[9] .= '</td>';
    }
}

function UserSwitching_Js_Add() {
    global $zbp;

    $zbp->Load();

    // 检测是否存在上次切换的有效用户
    if (isset($_COOKIE['us-token-last']) && isset($_COOKIE['us-username-last'])) {
        $user = $zbp->VerifyUserToken($_COOKIE['us-token-last'], $_COOKIE['us-username-last']);
        if (is_object($user) && $user->ID > 0) {
            echo '$(document).ready(function() {
                document.body.innerHTML += \'<a href="' . $zbp->host . 'zb_system/cmd.php?act=login&amp;UserSwitchingID=' . $user->ID . '&amp;callback=\' + encodeURIComponent(encodeURIComponent(document.location.href)) + \'" title="切换到 ' . $user->Name . ' 的账户" style="position: fixed;display: block;right: 0;bottom: 0;margin-right: 40px;margin-bottom: 40px;z-index: 900;height: 36px;line-height: 36px;min-width: 64px;padding: 0 16px;border-radius: 2px;font-size: 14px;font-weight: bold;text-decoration: none;color: white;background-color: rgb(255,171,64);">切换到 ' . $user->Name . ' 的账户</a>\';
            });';
        }
    }
}

function UserSwitching_SetLoginCookie($uid) {
    global $zbp;

    $user = $zbp->GetMemberByID($uid);
    if (is_object($user) && $user->ID > 0) {
        // 验证存在的切换权限的账户是否有效
        if (isset($_COOKIE['us-token-su']) && isset($_COOKIE['us-username-su'])) {
            $su = $zbp->VerifyUserToken($_COOKIE['us-token-su'], $_COOKIE['us-username-su']);
            if (is_object($su) && $zbp->CheckRights('UserSwitching', $su->Level) && $zbp->user->ID != $user->ID) {
                // 记录切换前的账户
                $cookieTime = 0;
                $secure = HTTP_SCHEME == 'https://';
                $token = $zbp->GenerateUserToken($zbp->user, $cookieTime);
                setcookie('us-username-last', $zbp->user->Name, $cookieTime, $zbp->cookiespath, '', $secure, false);
                setcookie('us-token-last', $token, $cookieTime, $zbp->cookiespath, '', $secure, true);

                return SetLoginCookie($user, 0);
            } else {
                return false;
            }
        } elseif ($zbp->CheckRights('UserSwitching') && $zbp->user->ID != $user->ID) {
            // 记录具有切换权限的账户
            $cookieTime = 0;
            $secure = HTTP_SCHEME == 'https://';
            $token = $zbp->GenerateUserToken($zbp->user, $cookieTime);
            setcookie('us-username-su', $zbp->user->Name, $cookieTime, $zbp->cookiespath, '', $secure, false);
            setcookie('us-token-su', $token, $cookieTime, $zbp->cookiespath, '', $secure, true);

            // 记录切换前的账户
            $cookieTime = 0;
            $secure = HTTP_SCHEME == 'https://';
            $token = $zbp->GenerateUserToken($zbp->user, $cookieTime);
            setcookie('us-username-last', $zbp->user->Name, $cookieTime, $zbp->cookiespath, '', $secure, false);
            setcookie('us-token-last', $token, $cookieTime, $zbp->cookiespath, '', $secure, true);

            return SetLoginCookie($user, 0);
        } else {
            return false;
        }
    }

    return $zbp->ShowError(6, __FILE__, __LINE__);
}

function UserSwitching_VerifyCallback($url = '/') {
    global $zbp;

    $ret = parse_url($url);

    // 验证是否站内链接
    if (isset($ret['host']) && $ret['host'] == parse_url($zbp->host)['host']) {
        Redirect($url);
    } else {
        Redirect('/');
    }
}

function InstallPlugin_UserSwitching() {}
function UninstallPlugin_UserSwitching() {}

