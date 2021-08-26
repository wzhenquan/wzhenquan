<?php
header("Content-type: text/json; charset=utf-8");
header("Cache-Control:no-cache,must-revalidate");
header("Pragma: no-cache");
include_once "config.php";

if ($_GET['ip']=='myip'){$ip=getuserip();}else{$ip=$_GET['ip'];}
$myurl=dirname('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$ip_chk=get_config('ip_chk');

if($ip_chk=='1'){
	echo file_get_contents("$myurl/iploc/qqzeng.php?ip=$ip");
}else if($ip_chk=='2'){
	echo file_get_contents("$myurl/iploc/taobao.php?ip=$ip");
}

?>
