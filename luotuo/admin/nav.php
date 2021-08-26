<meta charset="UTF-8"> <!-- for HTML5 -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="renderer" content="webkit" />
<title>IPTV管理平台</title>
<?php
include_once "../config.php";include_once "usercheck.php";
header("Expires: Wed, 1 Jan 1997 00:00:00 GMT");//内容过期时间 强制浏览器去服务器去获取数据 而不是从缓存中读取数据
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");//标记内容最后修改时间
header("Cache-Control: no-store, no-cache, must-revalidate");//强制不缓存
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");//禁止本页被缓存
header("Access-Control-Allow-Origin: *"); // Support CORS

?>

<style type="text/css">
body{ font-family: "Microsoft YaHei", "微软雅黑", "MicrosoftJhengHei", "华文细黑", "STHeiti", "MingLiu", "Helvetica" }
a:link, a:visited { text-decoration: none; color: #000 }
#topnav { width: 100%; background: #33a996; height: 50px; line-height: 50px; }
#topnav ul { width: 100%; margin: auto; text-align: center; }
#topnav a { display: inline-block; font-size: 18px; padding: 0 20px; }
#topnav a:hover { background: #345; color: #fff; border-top: 0px solid #f77825; }
#topnav a { color: #FFF }
#topnav_current { background: #345; border-top: 0px solid #f77825; color: #fff }/* 高亮选中颜色 */
a#topnav_current { color: #fff }
</style>

<center>
	<nav id="topnav">
		<ul>
			<?php if($user =='admin')echo '<a href="sysadmin.php">系统</a>';
			if($_SESSION['author']!=0)echo '<a href="author.php">授权</a>';
            echo '<a href="useradmin1.php">帐号</a>';
			if($_SESSION['useradmin']!=0)echo '<a href="useradmin.php">用户</a>';
			if($_SESSION['ipcheck']!=0)echo '<a href="ipcheck.php">异常</a>';
			if($_SESSION['epgadmin']!=0)echo '<a href="epgadmin.php">EPG列表</a>';
			if($_SESSION['channeladmin']!=0)echo '<a href="channeladmin.php?categorytype=default">频道列表</a>';
			if($_SESSION['channeladmin']!=0)echo '<a href="channeladmin.php?categorytype=vip">会员专区</a>'; ?>
		</ul>
		<script language="javascript">
		var obj=null;
		var As=document.getElementById('topnav').getElementsByTagName('a');
		obj = As[0];
		for(i=1;i<As.length;i++){
			var navhref=As[i].href;
			var href='';
			if(navhref.indexOf('&')>=0){
				href=navhref.substring(0,navhref.indexOf('&'));
			}else{
				href=navhref;
			}
			if(window.location.href.indexOf(href)>=0)
			obj=As[i];
		}
		obj.id='topnav_current';
		</script> 
	</nav>
</center>
<div style="float: left;width:100%;background: #fff;margin:auto;">
	<center>
		<?php
		date_default_timezone_set('Etc/GMT-8');
		echo "<p style='padding-left:20px;color:#000'>管理员：【".$user . "】&nbsp;&nbsp;".date("Y-m-d H:i",time());
		?>
		&nbsp;&nbsp;&nbsp;&nbsp;<a href="logout.php"><font color=red>注销登陆</font></a>
	</center>
</div>