<?php
include_once "../config.php";

session_start();
if($_SESSION['user']!='admin')exit();
    
mysqli_query($GLOBALS['conn'],"alter table chzb_appdata add column randkey varchar(100) DEFAULT '827ccb0eea8a706c4c34a16891f84e7b'");
$r=rand(1,9999999);
$k=md5($r);
mysqli_query($GLOBALS['conn'],"UPDATE chzb_appdata set randkey='$k'");

mysqli_close($GLOBALS['conn']);
echo '更新成功！';
?>