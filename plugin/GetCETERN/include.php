<?php
/**
 * GetCETERN Plugin For Z-Blog
 * 
 * @package     GetCETERN.zba
 * @version     1.0.8
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

RegisterPlugin('GetCETERN', 'ActivePlugin_GetCETERN');

function ActivePlugin_GetCETERN() {
    Add_Filter_Plugin('Filter_Plugin_Index_Begin', 'GetCETERN_Index_Begin');
}

function GetCETERN_Curl_Request($url, $post) {
    $headers = array(
        "Content-type: application/x-www-form-urlencoded; charset='utf-8'",
        "Host: app.cet.edu.cn:7066",
        "Origin: http://app.cet.edu.cn:7066",
        "Referer: http://app.cet.edu.cn:7066/baas/app/setuser.do?method=UserVerify"
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    if ($post) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        return curl_error($curl);
    }
    curl_close($curl);
    
    return $data;
}

function GetCETERN_Form() {
        global $zbp;

        $name = GetVars('name', 'POST');
        $idn = GetVars('idn', 'POST');
        $band = GetVars('band', 'POST');
        $vlc = GetVars('vlc', 'POST');

        if ($name && $idn && $band && $vlc) {
            if ($zbp->CheckValidCode($vlc, 'cet')) {
                $arr = array('ks_xm' => $name, 'ks_sfz' => $idn, 'jb' => $band);
                //var_dump($arr);
                $json = json_encode($arr);
                $data = array('params'=>$json);
                $ret = GetCETERN_Curl_Request('http://app.cet.edu.cn:7066/baas/app/setuser.do?method=UserVerify', $data);
                //var_dump($ret);
                $cetern = json_decode($ret, true);
                //var_dump($cetern);
                if (('' != $cetern['ks_bh']) && ('' == $cetern['msg'])) {
                    $form = '你的准考证号为： '.$cetern['ks_bh'];
                }
                else if ('' != $cetern['msg']) {
                    $form = $cetern['msg'];
                }
                else {
                    $form = '服务器开小差了，请重试...<a href="'. $zbp->host .'query/cet">点此传送到上一页</a>';
                }
            }
            else {
                $form = '手滑输错验证码了？<a href="'. $zbp->host .'query/cet">点此传送到上一页</a>';
            }
        }
        else {
$form = <<<EOF
Hello, 小伙伴～
<form action="" method="post">
    <p class="comment-input"><input placeholder="姓名" name="name" type="text" style="padding: 5px 10px;" /></p>
    <p class="comment-input"><input placeholder="身份证" name="idn" type="text" style="padding: 5px 10px;" /></p>
    <p class="comment-input"><b style="color:#249dff">别忘记选择 CET Band 哦～</b>&nbsp;&nbsp;&nbsp;&nbsp;四级点这个-><input name="band" type="radio" value="1" style="width: 10% !important;" />六级点这个-><input name="band" type="radio" value="2" style="width: 10% !important;" /></p>
    <p class="comment-input"><input placeholder="验证码" id="cet" name="vlc" type="text" tabindex="4" value="" style="padding: 5px 10px;" />
    <img class="captcha-image" src="{$zbp->validcodeurl}?id=cet" onclick="javascript:this.src='{$zbp->validcodeurl}?id=cet&amp;tm='+Math.random();" alt="点击刷新验证码" title="点击刷新验证码" /></p>
    <p class="comment-input"><input id="submit" type="submit" value="提交查询" /></p>
</form>
EOF;
        }

    $article = new Post;
    $article->Type = ZC_POST_TYPE_PAGE;
    $article->Title = 'CET 准考证号找回';
    $article->Content = $form;
    $article->Template = 'single';
    $article->IsLock = true;
    $zbp->template->SetTags('title', $article->Title);
    $zbp->template->SetTags('article', $article);
    $zbp->template->SetTags('type', $article->Type);
    $zbp->template->SetTags('page', 1);
    $zbp->template->SetTags('pagebar', null);
    $zbp->template->SetTags('comments', null);
    $zbp->template->SetTemplate($article->Template);
    $zbp->template->Display();
    exit;
}


function GetCETERN_Index_Begin() {
    global $zbp;

    $router = 'query';
    $path = explode('?', GetVars('REQUEST_URI', 'SERVER'));

    if ('REWRITE' != $zbp->option['ZC_STATIC_MODE']) {
        if ($path[0] == '/' || $path[0] == '/index.php') {
            $param = GetVars($router, 'GET');
            if ('cet' == $param) {
                GetCETERN_Form();
            }
        }
    }
    else {
        if (false === stripos($path[0], '/'. $router .'/')) return false;
        $param = substr($path[0], strlen($router) + 2);
        if ('cet' == $param) {
            GetCETERN_Form();
        }
    }

    return false;
}

function InstallPlugin_GetCETERN() {}

function UninstallPlugin_GetCETERN() {}