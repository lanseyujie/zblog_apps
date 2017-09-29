<?php
/**
 * OneQRCPay Plugin For Z-Blog
 * 
 * @package     OneQRCPay.zba
 * @version     1.0.5
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

RegisterPlugin('OneQRCPay', 'ActivePlugin_OneQRCPay');

function ActivePlugin_OneQRCPay() {
    Add_Filter_Plugin('Filter_Plugin_Index_Begin', 'OneQRCPay_Index_Begin');
}

function OneQRCPay_AliPay() {
    global $zbp;

    Redirect($zbp->Config('OneQRCPay')->AliPayLink);
}

function OneQRCPay_WechatPay() {
    global $zbp;

    $wcpqrc = $zbp->host .'zb_users/plugin/OneQRCPay/src/qrc.png?timestamp='. time();
    $wcpp = <<<EOF
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>微信支付</title>
    </head>
    <body>
        <div class="wcpqrc" style="position: absolute;margin: auto;top: 0;bottom: 0;left: 0;right: 0;width: 800px;">
            <h2>长按图片识别二维码</h2>
            <img src="{$wcpqrc}" alt="微信支付" title="微信支付" style="width: 800px;" />
        </div>
    </body>
</html>
EOF;

    die($wcpp);
}

function OneQRCPay_UADetector() {
    global $zbp;

    $ua = GetGuestAgent();

    if (strpos($ua, 'AlipayClient') == true) {
        OneQRCPay_AliPay();
    }
    else if (strpos($ua, 'MicroMessenger') == true) {
        OneQRCPay_WechatPay();
    }
    else {
        die('错误：未知的支付客户端！请使用微信、支付宝扫描访问。');
    }
}

function OneQRCPay_Index_Begin() {
    global $zbp;

    $router = 'pay';
    $path = explode('?', GetVars('REQUEST_URI', 'SERVER'));

    if ('REWRITE' != $zbp->option['ZC_STATIC_MODE']) {
        if ($path[0] == '/' || $path[0] == '/index.php') {
            $param = GetVars($router, 'GET');
            if ('oneqrc' == $param) {
                OneQRCPay_UADetector();
            }
        }
    }
    else {
        if (false === stripos($path[0], '/'. $router .'/')) return false;
        $param = substr($path[0], strlen($router) + 2);
        if ('oneqrc' == $param) {
            OneQRCPay_UADetector();
        }
    }
}

function InstallPlugin_OneQRCPay() {
    global $zbp;

    if (!$zbp->Config('OneQRCPay')->HasKey('Version')) {
        $zbp->Config('OneQRCPay')->Version = '1.0';
        $zbp->Config('OneQRCPay')->AliPayLink = 'https://qr.alipay.com/';
        $zbp->Config('OneQRCPay')->DelConfig = false;
        $zbp->SaveConfig('OneQRCPay');
    }

    $zbp->SetHint('good', 'OneQRCPay插件启用成功！感谢您的使用！');
}

function UninstallPlugin_OneQRCPay() {
    global $zbp;

    if ($zbp->Config('OneQRCPay')->DelConfig) {
        $zbp->DelConfig('OneQRCPay');
    }
    else {
        $zbp->SetHint('good', 'OneQRCPay插件配置已保留！');
    }

    $zbp->SetHint('good', '您已成功卸载插件OneQRCPay！一路走来，感谢有你，后会有期！');
}