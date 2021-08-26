<?php
include_once"aes.php";
include_once "config.php";
mysqli_query($GLOBALS['conn'],"SET NAMES 'UTF8'");
$channelNumber=1;

function echoJSON($category,$alisname,$psw){
	global $channelNumber;
	$sql = "SELECT name,url FROM chzb_channels where category='$category' order by id";
	$result = mysqli_query($GLOBALS['conn'],$sql);
	$nameArray = array();
	while($row = mysqli_fetch_array($result)) {
		if(!in_array($row['name'],$nameArray)){
			$nameArray[]=$row['name'];
		}
		$sourceArray[$row['name']][]=$row['url'];
	}
	mysqli_free_result($result);
	$objCategory=(Object)null;
	$objChannel=(Object)null;
	$channelArray=array();
	for($i=0;$i<count($nameArray);$i++) {
		$objChannel=(Object)null;
		$objChannel->num=$channelNumber;
		$objChannel->name=$nameArray[$i];
		$objChannel->source=$sourceArray[$nameArray[$i]];
		$channelArray[]=$objChannel;
		$channelNumber++;
	}
	$objCategory->name=$alisname;
	$objCategory->psw=$psw;
	$objCategory->data=$channelArray;
	unset($nameArray,$sourceArray,$objChannel);
	return $objCategory;
}

if(isset($_POST['data'])){
	$obj=json_decode($_POST['data']);
	$region=$obj->region;
	$mac=$obj->mac;
	$androidid=$obj->androidid;
	$model=$obj->model;
	$nettype=$obj->nettype;
	$appname=$obj->appname;
	$randkey=$obj->rand;
	
    $sql = "SELECT isvip FROM chzb_users where deviceid='$androidid'";
    $result = mysqli_query($GLOBALS['conn'], $sql);
    if ($row = mysqli_fetch_array($result)) {
        $isvip = $row['isvip'];
    }else{
    	$isvip = '0';
	}
	
	$contents[]= echoJSON('',"我的收藏",''); 

	//添加默认频道
	$sql = "SELECT name,id,psw FROM chzb_category where enable=1 and type='default' order by id";
	$result = mysqli_query($GLOBALS['conn'],$sql);
	while($row = mysqli_fetch_array($result)) {
		$pdname=$row['name'];
		$psw=$row['psw'];
		$contents[]= echoJSON($pdname,$pdname,$psw); 
	}
	unset($row);
	mysqli_free_result($result);
	
	//添加会员频道
	if ($isvip == '1') {
		$sql = "SELECT name,id,psw FROM chzb_category where enable=1 and type='vip' order by id";
		$result = mysqli_query($GLOBALS['conn'],$sql);
		while($row = mysqli_fetch_array($result)) {
			$pdname=$row['name'];
			$psw=$row['psw'];
			$contents[]= echoJSON($pdname,$pdname,$psw); 
		}
		unset($row);
		mysqli_free_result($result);
	}
	
	$str=json_encode($contents,JSON_UNESCAPED_UNICODE);
	$str=stripslashes($str);
	$str=base64_encode(gzcompress($str));

	$result=mysqli_query($GLOBALS['conn'],"select dataver from chzb_appdata");
	$ver=3;
	if($row=mysqli_fetch_array($result)){
		$ver=$row[0];
	}
	$key=md5($key.$randkey);
	$key=substr($key,7,16);
	$aes = new Aes($key);
	$encrypted =$aes->encrypt($str);
	$encrypted=str_replace("f", "&", $encrypted);
	$encrypted=str_replace("b", "f", $encrypted);
	$encrypted=str_replace("&", "b", $encrypted);
	$encrypted=str_replace("t", "#", $encrypted);
	$encrypted=str_replace("y", "t", $encrypted);
	$encrypted=str_replace("#", "y", $encrypted);
	$coded=substr($encrypted,44,128);
	$coded=strrev($coded);
	$str=$coded.$encrypted;
	echo $str;
	unset($row);
	mysqli_free_result($result);
	mysqli_close($GLOBALS['conn']);
}else{
	mysqli_close($GLOBALS['conn']);
	exit();
}
?>