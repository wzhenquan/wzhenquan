<?php
include_once "config.php";

$appver='V1.0';
$appUrl='';
$sql = "SELECT appver,appurl,up_size,up_sets,up_text FROM chzb_appdata";
$result = mysqli_query($GLOBALS['conn'],$sql);
if($row = mysqli_fetch_array($result)) {
	$appver=$row['appver'];	
	$appUrl=$row['appurl'];
	$up_size=$row["up_size"];
	$up_sets=$row["up_sets"];
	$up_text=$row["up_text"];
}
unset($row);
mysqli_free_result($result);
mysqli_close($GLOBALS['conn']);
$obj=(Object)null;
$obj->appver=$appver;
$obj->appurl=$appUrl;
$obj->appsets=$up_sets;
$obj->appsize=$up_size;
$obj->apptext=$up_text;
echo json_encode($obj,JSON_UNESCAPED_UNICODE);
unset($obj);
?>