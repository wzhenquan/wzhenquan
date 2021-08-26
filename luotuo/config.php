<?php
header("Content-type: text/html; charset=utf-8");

define("PANEL_MD5_KEY","tvkey_");  //面板密码MD5加密秘钥

$db_host="111.67.192.116/";  //数据库服务器
$db_user="sfydb_6477304";  //数据库帐号
$db_pwd="Wu139409001";   //数据库密码
$db_database="sfydb_6477304";  //数据库名称
$conn=mysqli_connect($db_host,$db_user,$db_pwd,$db_database) OR die ('无法登录MYSQL服务器！');  //连接数据库对象

global $conn;
mysqli_query($GLOBALS['conn'],"SET NAMES 'UTF8'");

function get_config($name){
	$result=mysqli_query($GLOBALS['conn'],"SELECT value from chzb_config where name='$name'");
	if($row=mysqli_fetch_array($result)){$return=$row['value'];}else{echo '<script>alert("数据库错误！");</script>';}
	unset($row);
	mysqli_free_result($result);
	return $return;
}

function set_config($name,$value){
	mysqli_query($GLOBALS['conn'],"UPDATE chzb_config set value='$value' where name='$name'");
}
function getuserip(){
	$real_ip = $_SERVER['REMOTE_ADDR'];
	if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
		$real_ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
		foreach ($matches[0] AS $xip) {
			if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
				$real_ip = $xip;
				break;
			}
		}
	}
	return $real_ip;
}
?>