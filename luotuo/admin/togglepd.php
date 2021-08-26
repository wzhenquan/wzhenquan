<?php
include_once "../config.php";
session_start();
if(isset($_SESSION['user']))$user=$_SESSION['user'];
$result=mysqli_query($GLOBALS['conn'],"select * from chzb_admin where name='$user'");
if($row=mysqli_fetch_array($result)){
	$psw=$row['psw'];
}else{
	$psw='';
}
if(!isset($_SESSION['psw'])||$_SESSION['psw']!=$psw){
	exit;
}
?>

<?php
if(isset($_GET['pdname']) && isset($_GET['cat'])){
	$pdname=$_GET['pdname'];
	$categoryname=$_GET['cat'];
	$result=mysqli_query($GLOBALS['conn'],"select enable from $categoryname where name='$pdname'");
	if($row=mysqli_fetch_array($result)){
		if($row['enable']==1){
			mysqli_query($GLOBALS['conn'],"UPDATE $categoryname set enable=0 where name='$pdname'");
			echo "<script>alert('$pdname 已禁用');</script>";
		}else{
			mysqli_query($GLOBALS['conn'],"UPDATE $categoryname set enable=1 where name='$pdname'");
			echo "<script>alert('$pdname 已启用');</script>";
		}
	}else{
		echo "<script>alert('$pdname 操作失败！');</script>";
	}
}else{
	echo "<script>alert('参数错误');</script>";
}
?>