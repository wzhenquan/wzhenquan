<?php
header("Content-type: text/html; charset=utf-8");
/**配置数据区信息**/
$db_host=" 111.67.192.116";  //数据库服务器
$db_port="3306";  //数据库端口
$db_user="sfydb_6477304";  //帐号
$db_pwd="Wu139409001";   //密码
$db_dabse="sfydb_6477304";  //数据库名称
//连接数据库对象
$con = mysqli_connect($db_host,$db_user,$db_pwd);
if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }
 //设置使用的数据库
mysqli_select_db($con,$db_dabse);
mysqli_query($con,"set names utf8");
?>