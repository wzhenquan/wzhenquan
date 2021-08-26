<?php
session_start();
session_unset();//free all session variable
session_destroy();//销毁一个会话中的全部数据
setcookie("psw",NULL,time()-1);
setcookie("user",NULL,time()-1);
setcookie("secret_key",NULL,time()-1,"/");
setcookie("rememberpass","1",time()-1);
setcookie("remembersecret_key","1",time()-1,"/");
header("location:userlogin.php");
?>