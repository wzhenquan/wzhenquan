<?php
include_once "../config.php";

$sql = "SELECT value FROM chzb_config where name='epg_api_chk'";
$result = mysqli_query($GLOBALS['conn'],$sql);
if($row = mysqli_fetch_array($result)) {
	$value=$row['value'];
	if($value != 0){
		function msg($code,$msg){
				date_default_timezone_set("Asia/Shanghai");
				header('content-type:application/json;charset=utf-8');
				$arr=[];
				$datas=[];
				$datas["name"]=$msg;
				$datas["starttime"]=date("H:i",time());
				$arr["code"]=$code;
				$arr["msg"]=$msg;
				$arr["name"]="Access deniend";
				$arr["tvid"]="1";
				$arr["date"]=date("Y-m-d",time());
				$arr["data"]=[$datas];
				$str=json_encode($arr,JSON_UNESCAPED_UNICODE);
				unset($arr,$datas);
				echo $str;
				exit;
		}
		
		$utoken=!empty($_SERVER["HTTP_USER_TOKEN"])?$_SERVER["HTTP_USER_TOKEN"]:msg(200,"1000_EPG接口验证失败!如有疑问请联系公众号客服：luo2888的工作室");
		$uid=!empty($_SERVER["HTTP_USER_ID"])?$_SERVER["HTTP_USER_ID"]:msg(200,"1001_EPG接口验证失败系!如有疑问请联系公众号客服：luo2888的工作室");
		$uip=!empty($_SERVER["HTTP_USER_IP"])?$_SERVER["HTTP_USER_IP"]:msg(200,"1002_EPG接口验证失败!如有疑问请联系公众号客服：luo2888的工作室");
		
		$sql = "SELECT randkey FROM chzb_appdata";
		$result = mysqli_query($GLOBALS['conn'],$sql);
		if($row = mysqli_fetch_array($result)) {
			$randkey=$row['randkey'];
			if($utoken != $randkey){
				msg(200,"1003_EPG接口验证失败!如有疑问请联系公众号客服：luo2888的工作室");exit();
			}	
		}
		unset($row);
		mysqli_free_result($result);
		
		$sql = "SELECT ip FROM chzb_users where name='$uid'";
		$result = mysqli_query($GLOBALS['conn'],$sql);
		if($row = mysqli_fetch_array($result)){
			$ip=$row['ip'];
			if($uip != $ip){
				msg(200,"1004_EPG接口验证失败!如有疑问请联系公众号客服：luo2888的工作室");exit();
			}
		}else{
			msg(200,"1005_EPG接口验证失败!如有疑问请联系公众号客服：luo2888的工作室");exit();
		}
		unset($row);
		mysqli_free_result($result);
	}
}
?>