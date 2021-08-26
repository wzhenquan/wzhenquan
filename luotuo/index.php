<?php
header("Content-type: text/html; charset=utf-8");

session_start();
include_once "config.php";
if(isset($_POST['secret_key_enter'])){
	$secret_key=$_POST['secret_key'];
	$secret_key=mysqli_real_escape_string($GLOBALS['conn'],$_POST['secret_key']);
	$secret_key=md5($secret_key);
	$result=mysqli_query($GLOBALS['conn'],"select value from chzb_config where name='secret_key'");
	if($row=mysqli_fetch_array($result)){
		if (empty($row['value'])){
			$_SESSION['secret_key_status']='1';
			unset($row);
			mysqli_free_result($result);
			mysqli_close($GLOBALS['conn']);
			header("location:admin/userlogin.php");
		}else{
			if($secret_key==$row['value']){
				$_SESSION['secret_key_status']='1';
				if(isset($_POST['remembersecret_key'])){
					setcookie("secret_key",$row['value'],time()+3600*24*7);
					setcookie("remembersecret_key","1",time()+3600*24*7);
				}else{
					setcookie("remembersecret_key","1",time()-3600);
				}
				unset($row);
				mysqli_free_result($result);
				mysqli_close($GLOBALS['conn']);
				header("location:admin/userlogin.php");
			}else{
				echo "<script>alert('安全码错误！');</script>";
			}
		}
	}else{
		echo "<script>alert('数据库没找到安全码字段！');</script>";
	}
	unset($row);
	mysqli_free_result($result);
	mysqli_close($GLOBALS['conn']);
}

if(isset($_COOKIE['remembersecret_key'])){
	$secret_key=mysqli_real_escape_string($GLOBALS['conn'],$_COOKIE['secret_key']);
	$result=mysqli_query($GLOBALS['conn'],"select value from chzb_config where name='secret_key'");
	$row=mysqli_fetch_array($result);
	if($secret_key==$row['value']){
			$_SESSION['secret_key_status']='1';
			unset($row);
			mysqli_free_result($result);
			mysqli_close($GLOBALS['conn']);
			header("location:admin/userlogin.php");
	}
	unset($row);
	mysqli_free_result($result);
	mysqli_close($GLOBALS['conn']);
}
?>

<html>
	<head>
		<title>欢迎使用肥米TV</title>
		<script language="JavaScript">
			function startTime(){   
				var today=new Date();//定义日期对象   
				var yyyy = today.getFullYear();//通过日期对象的getFullYear()方法返回年    
				var MM = today.getMonth()+1;//通过日期对象的getMonth()方法返回年    
				var dd = today.getDate();//通过日期对象的getDate()方法返回年     
				var hh=today.getHours();//通过日期对象的getHours方法返回小时   
				var mm=today.getMinutes();//通过日期对象的getMinutes方法返回分钟   
				var ss=today.getSeconds();//通过日期对象的getSeconds方法返回秒   
				// 如果分钟或小时的值小于10，则在其值前加0，比如如果时间是下午3点20分9秒的话，则显示15：20：09   
				MM=checkTime(MM);
				dd=checkTime(dd);
				mm=checkTime(mm);   
				ss=checkTime(ss);    
				var day; //用于保存星期（getDay()方法得到星期编号）
				if(today.getDay()==0)   day   =   "星期日 " 
				if(today.getDay()==1)   day   =   "星期一 " 
				if(today.getDay()==2)   day   =   "星期二 " 
				if(today.getDay()==3)   day   =   "星期三 " 
				if(today.getDay()==4)   day   =   "星期四 " 
				if(today.getDay()==5)   day   =   "星期五 " 
				if(today.getDay()==6)   day   =   "星期六 " 
				document.getElementById('nowDateTimeSpan').innerHTML=yyyy+"-"+MM +"-"+ dd +" " + hh+":"+mm+":"+ss+"   " + day;   
				setTimeout('startTime()',1000);//每一秒中重新加载startTime()方法 
			}

            function checkTime(i){   
				if (i<10){
					i="0" + i;
				}
				return i;
            }
		</script>
	</head>
	<body onload="startTime()">
		当前时间：<font color="#0D0D0D"><span id="nowDateTimeSpan"></span></font>
		<br><br>
		<form method="post">
			请输入安全码：<input type="password" name="secret_key"/>
			<input class="check" type="checkbox" value="1" name="remembersecret_key">记住7天
			<input type="submit" name="secret_key_enter" value="&nbsp;&nbsp;&nbsp;进入后台&nbsp;&nbsp;&nbsp;"/>
		</form>
	</body>
</html>