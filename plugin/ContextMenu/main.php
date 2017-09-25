<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('ContextMenu')) {$zbp->ShowError(48);die();}

if (isset($_POST['usebgpic'])) {
	$zbp->Config('ContextMenu')->usebgpic = $_POST['usebgpic'];
	$zbp->Config('ContextMenu')->usefontawesome = $_POST['usefontawesome'];
	$zbp->Config('ContextMenu')->usercml = $_POST['usercml'];
	$zbp->SaveConfig('ContextMenu');
	$zbp->SetHint('good', '参数已保存，刷新首页可以查看是否生效');
}

if (isset($_GET['act'])) {
	if ('bgp' == $_GET['act']) {
		global $zbp;
		foreach ($_FILES as $key => $value) {
			if (!strpos($key, '_php')) {
				if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
					$tmp_name = $_FILES[$key]['tmp_name'];
					$name = $_FILES[$key]['name'];
					@move_uploaded_file($_FILES[$key]['tmp_name'], $zbp->usersdir .'plugin/ContextMenu/src/images/bgp.png');
				}
			}
		}
		$zbp->SetHint('good','背景图片修改成功！');
		Redirect('./main.php');
	}
	else {
		echo 'ContextMenu配置参数未定义！';
		die();
	}
}

require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
	<div class="divHeader">ContextMenu</div>
	<div class="SubMenu">
		<a href="https://github.com/lanseyujie/ZBA" target="_blank" title="GitHub"><span class="m-right">项目源码</span></a>
		<a href="https://lanseyujie.com?plugin=ContextMenu" target="_blank" title="蓝色域界"><span class="m-right">作者博客</span></a>
	</div>
	<div id="divMain2">
		<form id="form1" name="form1" method="post">
			<table width="100%" style="padding:0;margin:0;" cellspacing="0" cellpadding="0" class="tableBorder">
				<tr>
					<td width="15%" height="50px" align="center">启用自定义背景图</td>
					<td width="35%"><span class="sel"><input name="usebgpic" id="usebgpic" class="checkbox" type="text" value="<?php echo $zbp->Config('ContextMenu')->usebgpic;?>" /></span><span style="color:#888;margin-left:20px;font-size:12px;">自定义右键菜单背景图</span></td>
					<td width="15%" height="50px" align="center">启用Font-awesome</td>
					<td width="35%"><span class="sel"><input name="usefontawesome" id="usefontawesome" class="checkbox" type="text" value="<?php echo $zbp->Config('ContextMenu')->usefontawesome;?>" /></span><span style="color:#888;margin-left:20px;font-size:12px;">使用Font-awesome图标</span></td>	
				</tr>
				<tr>
					<td width="15%" height="240px" align="center">菜单配置<b style="color:red">（请勿换行！）<b></td>
					<td width="85%" colspan="3"><p align="left"><textarea name="usercml" type="text" id="usercml" style="width:98%;height:200px"><?php echo $zbp->Config('ContextMenu')->usercml;?></textarea></p></td>
				</tr>
			</table>
			<br />
			<input name="" type="Submit" class="button" value="保存" />
		</form>
		<form enctype="multipart/form-data" method="post" action="main.php?act=bgp">  
			<table width="100%" style="padding:0;margin:0;" cellspacing="0" cellpadding="0" class="tableBorder">
				<tr>
					<td width="15%" height="50px"><label for="bgp.png"><p align="center">上传背景图片</p></label></td>
					<td width="50%" height="50px"><p align="center"><input name="bgp.png" type="file" /></p></td>
					<td width="25%" height="50px"><p align="center"><input name="" type="Submit" class="button" value="上传" /></p></td>
				</tr>
			</table>
		</form>
	</div>
</div>
<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost .'zb_users/plugin/ContextMenu/logo.png'; ?>");</script>
<?php
require $blogpath .'zb_system/admin/admin_footer.php';
RunTime();
?>