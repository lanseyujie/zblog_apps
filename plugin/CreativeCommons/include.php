<?php
/**
 * CreativeCommons Plugin For Z-Blog
 * 
 * @package     CreativeCommons.zba
 * @version     1.1.3
 * @author      Wildlife <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @license     https://opensource.org/licenses/mit-license.php MIT
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

RegisterPlugin('CreativeCommons', 'ActivePlugin_CreativeCommons');

function ActivePlugin_CreativeCommons() {
    Add_Filter_Plugin('Filter_Plugin_Edit_Response3', 'CreativeCommons_Edit_Response3');
    Add_Filter_Plugin('Filter_Plugin_ViewPost_Template', 'CreativeCommons_ViewPost_Template');
}

function CreativeCommons_Edit_Response3() {
    global $zbp, $article;
    // CC 协议
    $s = null;
    $cc = array(
         0 => '无版权',
         1 => '署名-非商业使用-禁止演绎',
         2 => '署名-非商业性使用-相同方式共享',
         3 => '署名-非商业性使用',
         4 => '署名-禁止演绎',
         5 => '署名-相同方式共享',
         6 => '署名'
        );
    foreach ($cc as $id => $type) {
        $s .= '<option ' . ((int) $article->Metas->CreativeCommons_Type == $id ? 'selected="selected"' : '') . ' value="' . $id . '">' . $type . '</option>';
    }
    echo '<div id="creativecommons" class="editmod">
        <label for="cmbCreativeCommons" class="editinputname">版权</label>
        <select id="cmbCreativeCommons" name="meta_CreativeCommons_Type" style="width:180px;" onchange="edtCreativeCommons.value=this.options[this.selectedIndex].value">'. $s .'</select>
        </div>';
}

function CreativeCommons_ViewPost_Template(&$template) {
    global $zbp;

    $article = $template->GetTags('article');
    $title = $article->Title;
    $url = $article->Url;
    $author = $article->Author->StaticName;
    $authorurl = $article->Author->Url;
    $bloghost = $zbp->host;
    $blogname = $zbp->name;
    $cc = $article->Metas->CreativeCommons_Type;

    if ($cc == 1) {
        $cctip = '本文使用「署名-非商业使用-禁止演绎 4.0 国际」创作共享协议，转载或使用请遵守署名协议。';
    }
    elseif ($cc == 2) {
        $cctip = '本文使用「署名-非商业性使用-相同方式共享 4.0 国际」创作共享协议，转载或使用请遵守署名协议。';
    }
    elseif ($cc == 3) {
        $cctip = '本文使用「署名-非商业性使用 4.0 国际」创作共享协议，转载或使用请遵守署名协议。';
    }
    elseif ($cc == 4) {
        $cctip = '本文使用「署名-禁止演绎 4.0 国际」创作共享协议，转载或使用请遵守署名协议。';
    }
    elseif ($cc == 5) {
        $cctip = '本文使用「署名-相同方式共享 4.0 国际」创作共享协议，转载或使用请遵守署名协议。';
    }
    elseif ($cc == 6) {
        $cctip = '本文使用「署名 4.0 国际」创作共享协议，转载或使用请遵守署名协议。';
    }
    else {
        $cctip = '本文不使用任何协议授权，您可以任何形式自由转载或使用。';
    }

$cc_template = <<<EOF
<div class="creativecommons" style="width:100%;display:inline-block;margin:10px 0;padding:3px;font-size:14px;line-height:26px;border:1px dashed #24b999;box-sizing:border-box;">
    <div class="cc-title">
        <strong>本文标题：</strong>{$title}
    </div>
    <div class="cc-url">
        <strong>本文链接：</strong>{$url}
    </div>
    <div class="cc-authorize">
        <strong>作者授权：</strong>除特别说明外，本文由&nbsp;<a href="{$authorurl}" title="本文作者" style="color:#0609e5;text-decoration:none;">{$author}</a>&nbsp;原创编译并授权&nbsp;<a href="{$bloghost}" title="{$blogname}" style="color:#0609e5;text-decoration:none;">{$blogname}</a>&nbsp;刊载发布。
    </div>
    <div class="cc-copy">
        <strong>版权声明：</strong>{$cctip}
    </div>
</div>
EOF;

    $article->Content = $article->Content . $cc_template;
    $template->SetTags('article', $article);
}

function InstallPlugin_CreativeCommons() {}
function UninstallPlugin_CreativeCommons() {}