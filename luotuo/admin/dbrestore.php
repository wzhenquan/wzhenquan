<?php
include_once "../config.php";
include_once"dbmanager.php";

session_start();
if(isset($_SESSION['user']))$user=$_SESSION['user'];
$result=mysqli_query($GLOBALS['conn'],"select * from chzb_admin where name='$user'");
if($row=mysqli_fetch_array($result)){
	$psw=$row['psw'];
}else{
	$psw='';
}
if(!isset($_SESSION['psw'])||$_SESSION['psw']!=$psw){
	echo"<script>alert('你无权还原数据库！');history.go(-1);</script>";
	exit();
}

$db = new DbManage();
$db->restore("./backup/chzb_admin_v1.sql");
$db->restore("./backup/chzb_adminrec_v1.sql");
$db->restore("./backup/chzb_appdata_v1.sql");
$db->restore("./backup/chzb_category_v1.sql");
$db->restore("./backup/chzb_channels_v1.sql");
$db->restore("./backup/chzb_config_v1.sql");
$db->restore("./backup/chzb_epg_v1.sql");
$db->restore("./backup/chzb_loginrec_v1.sql");
$db->restore("./backup/chzb_users_v1.sql");
echo "数据库恢复成功！";

mysqli_close($GLOBALS['conn']);
?>