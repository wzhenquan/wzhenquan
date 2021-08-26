<script type="text/javascript">
//是否显示管理员设定，1为显示，0为不显示。
var showadmin=1;
//是否修改密码设定，1为显示，0为不显示。
var showsrcset=1;
//第一次打开显示第几个设置页面，0为第1个，1为第二个...
var showindex=0;
</script>
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>

<?php
include_once "nav.php";

if($user!='admin'){
	echo"<script>alert('你无权访问此页面！');history.go(-1);</script>";
	exit();
}

//修改密码操作
if(isset($_POST['submit'])&&isset($_POST['newpassword'])){
	if (empty($_POST['oldpassword']) || empty($_POST['newpassword'])) {
	    echo"<script>showindex=5;alert('密码不能为空！');</script>";
	}else{
		$username=$_POST['username'];
		$oldpassword=md5(PANEL_MD5_KEY.$_POST['oldpassword']);
		$newpassword=md5(PANEL_MD5_KEY.$_POST['newpassword']);
		$result=mysqli_query($GLOBALS['conn'],"select * from chzb_admin where name='$username' and psw='$oldpassword'");
		if(mysqli_fetch_array($result)){
			$sql="update chzb_admin set psw='$newpassword' where name='$username'";
			mysqli_query($GLOBALS['conn'],$sql);
			echo"<script>showindex=5;alert('密码修改成功！');</script>";
			mysqli_free_result($result);
		}else{
			echo"<script>showindex=5;alert('原始密码不匹配！');</script>";
			mysqli_free_result($result);
		}
	}
}


//修改安全码操作
if(isset($_POST['submit'])&&isset($_POST['newsecret_key'])){
	if (empty($_POST['newsecret_key']) || empty($_POST['newsecret_key_confirm'])) {
	    echo"<script>showindex=5;alert('安全码不能为空！');</script>";
	}else{
		$newsecret_key_input=$_POST['newsecret_key'];
		$newsecret_key_confirm=$_POST['newsecret_key_confirm'];
		if($newsecret_key_input==$newsecret_key_confirm){
			$newsecret_key=md5($_POST['newsecret_key']);
			$sql="update chzb_config set value='$newsecret_key' where name='secret_key'";
			mysqli_query($GLOBALS['conn'],$sql);
			echo"<script>showindex=5;alert('安全码修改成功！');</script>";
		}else{
			echo"<script>showindex=5;alert('两次输入不匹配！');</script>";
		}
	}
}

if(isset($_POST['closesecret_key'])){
	$needsecret_key=$_POST['closesecret_key'];
	$sql="update chzb_config set value=NULL where name='secret_key'";
	mysqli_query($GLOBALS['conn'],$sql);
	echo"<script>showindex=5;alert('安全码验证已关闭！');</script>";
}

//添加管理员操作
if(isset($_POST['adminadd'])){
	if (empty($_POST['addadminname']) || empty($_POST['addadminpsw'])) {
	    echo"<script>showindex=6;alert('管理员的账号或是密码不能为空！');</script>";
	}else{
		$adminname=$_POST['addadminname'];
		$adminpsw=md5(PANEL_MD5_KEY.$_POST['addadminpsw']);
		$result=mysqli_query($GLOBALS['conn'],"SELECT count(*) from chzb_admin");
		if($row=mysqli_fetch_array($result)){
			if($row[0]>5){
				unset($row);
				mysqli_free_result($result);
				echo"<script>showindex=6;alert('管理员数量已达上限！');</script>"; 
			}else{
			$result=mysqli_query($GLOBALS['conn'],"select * from chzb_admin where name='$adminname'");
				if(mysqli_fetch_array($result)){
					unset($row);
					mysqli_free_result($result);
					echo"<script>showindex=6;alert('用户名已存在！');</script>"; 
				}else{
					mysqli_query($GLOBALS['conn'],"INSERT into chzb_admin (name,psw) values ('$adminname','$adminpsw')");
					echo"<script>showindex=6;alert('管理员添加成功！');</script>"; 
				}
			}
		}
	}
}

//删除账号操作
if(isset($_POST['deleteadmin'])){	
	if (empty($_POST['adminname'])) {
	    echo"<script>showindex=6;alert('请选择要删除的帐号！');</script>";
	}else {
	    foreach ($_POST['adminname'] as $name) {
			if($name<>'admin'){
				mysqli_query($GLOBALS['conn'],"delete from chzb_admin where name='$name'");
				echo"<script>showindex=6;alert('管理员[$name]已删除！');</script>";
			}else{
				if ($name=="admin") {
				    echo"<script>showindex=6;alert('超级管理员[$name]不允许删除！');</script>";
				}else {
				    echo"<script>showindex=6;alert('删除失败！');</script>";
				}
			}
		}
	}
}

//设置管理员权限
if(isset($_POST['saveauthorinfo'])){
	if ( !empty($_POST['adminname'])) {
		mysqli_query($GLOBALS['conn'],"UPDATE chzb_admin set author=0,useradmin=0,ipcheck=0,epgadmin=0,channeladmin=0 where name<>'admin'");
		if ( !empty($_POST['author'])) {
			foreach ($_POST['author'] as $adminname){
				mysqli_query($GLOBALS['conn'],"UPDATE chzb_admin set author=1 where name='$adminname'");
			}
		}
		if ( !empty($_POST['useradmin'])) {
			foreach ($_POST['useradmin'] as $adminname){
				mysqli_query($GLOBALS['conn'],"UPDATE chzb_admin set useradmin=1 where name='$adminname'");
			}
		}
		if ( !empty($_POST['ipcheck'])) {
			foreach ($_POST['ipcheck'] as $adminname){
				mysqli_query($GLOBALS['conn'],"UPDATE chzb_admin set ipcheck=1 where name='$adminname'");
			}
		}
		if ( !empty($_POST['epgadmin'])) {
			foreach ($_POST['epgadmin'] as $adminname){
				mysqli_query($GLOBALS['conn'],"UPDATE chzb_admin set epgadmin=1 where name='$adminname'");
			}
		}
		if ( !empty($_POST['channeladmin'])) {
			foreach ($_POST['channeladmin'] as $adminname){
				mysqli_query($GLOBALS['conn'],"UPDATE chzb_admin set channeladmin=1 where name='$adminname'");
			}
		}
		echo"<script>showindex=6;alert('管理员权限设定已保存！');</script>";
	}else{
		echo"<script>showindex=6;alert('请选择管理员！');</script>";
	}
}

//设置APP升级信息
if(isset($_POST['submit'])&&isset($_POST['appver'])){
	$versionname=$_POST['appver'];
	$appurl=$_POST['appurl'];
	$up_size=$_POST["up_size"];
	$up_sets=$_POST["up_sets"];
	$up_text=$_POST["up_text"];
	$sql = "update chzb_appdata set appver='$versionname',appurl='$appurl',up_size='$up_size',up_sets=$up_sets,up_text='$up_text' ";
	mysqli_query($GLOBALS['conn'],$sql);
	echo"<script>showindex=2;alert('APP升级设置成功！');</script>";
}

if(isset($_POST['decodersel'])&&isset($_POST['buffTimeOut'])){
	$decoder=$_POST['decodersel'];
	$buffTimeOut=$_POST['buffTimeOut'];
	$trialdays=$_POST['trialdays'];
	$sql = "update chzb_appdata set decoder=$decoder,buffTimeOut=$buffTimeOut,trialdays=$trialdays";
	mysqli_query($GLOBALS['conn'],$sql);
	if($trialdays==0){
		$sql = "update chzb_users set exp=0 where status=-1";
		mysqli_query($GLOBALS['conn'],$sql);
	}
	echo"<script>showindex=2;alert('设置成功！');</script>";
}

if(isset($_POST['submitsetver'])){
	$sql = "update chzb_appdata set setver=setver+1";
	mysqli_query($GLOBALS['conn'],$sql);
	echo"<script>showindex=2;alert('推送成功，用户下次启动将恢复出厂设置！');</script>";
}

if(isset($_POST['submittipset'])){
	$tiploading=$_POST['tiploading'];
	$tipusernoreg=$_POST['tipusernoreg'];
	$tipuserexpired=$_POST['tipuserexpired'];
	$tipuserforbidden=$_POST['tipuserforbidden'];
	mysqli_query($GLOBALS['conn'],"update chzb_appdata set tiploading='$tiploading',tipusernoreg='$tipusernoreg',tipuserexpired='$tipuserexpired',tipuserforbidden='$tipuserforbidden'");
	echo"<script>showindex=2;alert('提示信息已修改！');</script>";
}

if(isset($_POST['weaapi_id'])&&isset($_POST['weaapi_key'])){
	$weaapi_id=$_POST['weaapi_id'];$weaapi_key=$_POST['weaapi_key'];
	if (empty($weaapi_id)) {echo("<script>showindex=0;alert('请填写天气APP_ID！');</script>");}
	else if (empty($weaapi_key)) {echo("<script>showindex=0;alert('请填写天气APP_KEY！');</script>");}
	if (isset($_POST['showwea'])){$showwea=1;}else{$showwea=0;}
	set_config('showwea',"$showwea");
	set_config('weaapi_id',"$weaapi_id");
	set_config('weaapi_key',"$weaapi_key");
}

if(isset($_POST['submit'])&&isset($_POST['adtext'])){
	$adtext=$_POST['adtext'];
	$showtime=$_POST['showtime'];
	$showinterval=$_POST['showinterval'];
	$qqinfo=$_POST['qqinfo'];
	$sql="update chzb_appdata set adtext='$adtext',showtime=$showtime,showinterval=$showinterval,qqinfo='$qqinfo'";
	mysqli_query($GLOBALS['conn'],$sql);
	echo"<script>showindex=0;alert('公告修改成功！');</script>";
}

if(isset($_POST['submitappinfo'])){
	$app_sign=$_POST['app_sign'];$app_appname=$_POST['app_appname'];$app_packagename=$_POST['app_packagename'];
	set_config('app_sign',"$app_sign");
	set_config('app_appname',"$app_appname");
	set_config('app_packagename',"$app_packagename");
	echo"<script>showindex=2;alert('保存成功！');</script>";
}

$userdata="";
if(isset($_POST['submitexport'])){
	$result=mysqli_query($GLOBALS['conn'],"select name,deviceid,mac,model,author,exp,marks,status from chzb_users where status>-1");
	while($row=mysqli_fetch_array($result)){
		$userdata=$userdata.$row[0].",".$row[1].",".$row[2].",".$row[3].",".$row[4].",".$row[5].",".$row[6].",".$row[7]."\r\n";
	}
	unset($row);
	mysqli_free_result($result);
	echo"<script>showindex=1;alert('数据已导出。请全选复制后保存！');</script>";
}

if(isset($_POST['submitimport'])){
	$userdata=$_POST['userdata'];
	$lines=explode("\r\n",$userdata);
	$sucessCount=0;
	$failedCount=0;
	foreach($lines as $line){	
		if (strpos($line, ',') !== false){
			$arr=explode(",",$line);
			$nowtime=time();
			$name=$arr[0];
			$deviceid=$arr[1];
			$mac= $arr[2];
			$model=$arr[3];
			$author=$arr[4];
			$exp=$arr[5];
			$marks=$arr[6];
			$status=$arr[7];
			$result=mysqli_query($GLOBALS['conn'],"SELECT * from chzb_users where name=$name");
			if(mysqli_fetch_array($result)){
				$failedCount++;
				echo "<p align='center'>$line 因ID已存在导入失败</p>";
			}else{
				if(mysqli_query($GLOBALS['conn'],"INSERT into chzb_users (name,mac,deviceid,model,author,exp,status,marks) values($name,'$mac','$deviceid','$model','$author',$exp,$status,'$marks')")){
					$sucessCount++;
				}else{
					$failedCount++;
				}				
			}
			unset($arr);
			mysqli_free_result($result);
		}else{
			echo "<p align='center'>$line 因格式错误导入失败</p>";
			$failedCount++;
		}
	}
	unset($userdata,$lines);
	echo "<script>alert('导入成功 $sucessCount 条,失败 $failedCount 条。')</script>";
	echo"<script>showindex=1;</script>";
}

if(isset($_POST['submitimportid'])){
	$userdata=$_POST['userdata'];
	$days=$_POST['exp'];
	$marks=$_POST['marks'];
	$lines=explode("\r\n",$userdata);
	$sucessCount=0;
	$failedCount=0;
	$failedname='';
	$nowtime=time();
	foreach($lines as $line){	
			if(strlen($line)>0){					
				$exp=$days;
				$name=$line;
				$result=mysqli_query($GLOBALS['conn'],"select sn from chzb_serialnum where sn=$name");
				if(mysqli_fetch_array($result)){
					$failedCount++;
					$failedname.="[$name]";
				}else{
					mysqli_query($GLOBALS['conn'],"INSERT into chzb_serialnum (sn,exp,author,createtime,marks) values ($name,$exp,'$user',$nowtime,'$marks')");
					$sucessCount++;
				}
				mysqli_free_result($result);
			}
	}
	unset($userdata,$lines);
	if(!empty($failedname)) {
		echo "<script>alert('导入成功 $sucessCount 条,失败 $failedCount 条。用户 $failedname 导入失败。')</script>";
	}else{
		echo "<script>alert('导入成功 $sucessCount 条,失败 $failedCount 条。')</script>";
	}
	echo"<script>showindex=1;</script>";
}

function genName(){
	$name=rand(10000000,99999999);
	$result = mysqli_query($GLOBALS['conn'],"SELECT * from chzb_users where name=$name");
	if($row=mysqli_fetch_array($result)){
		unset($row);
		mysqli_free_result($result);
		genName();
	}else{
		return $name;
	}
}

//上传APP背景图片
if(isset($_POST['submitsplash'])){
	if ($_FILES["splash"]["type"] == "image/png"){
		if ($_FILES["splash"]["error"] > 0){
			echo "Error: " . $_FILES["splash"]["error"];
		}else{
			$savefile="../images/".$_FILES["splash"]["name"];
			move_uploaded_file($_FILES["splash"]["tmp_name"],$savefile);
			$url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]; 
			$splashurl=dirname($url).'/'.$savefile;
			$sql="update chzb_appdata set splash='$splashurl'";
			mysqli_query($GLOBALS['conn'],$sql);
			echo "<script>alert('上传成功！')</script>";
		}
	}else{
		echo "<script>alert('图片仅支持PNG格式，大小不能超过800KB。')</script>";
	}
	echo"<script>showindex=3;</script>";
}

//删除背景图片
if(isset($_POST['submitdelbg'])){
  $file=$_POST['file'];
  unlink('../images/'.$file);
  echo"<script>showindex=3;alert('删除成功！');</script>";
}

if(isset($_POST['submitcloseauthor'])){
	$needauthor=$_POST['needauthor'];
	if($needauthor==1){
		$needauthor=0;
		echo"<script>showindex=2;alert('用户授权已关闭！');</script>";
	}else{
		$needauthor=1;
		echo"<script>showindex=2;alert('用户授权已开启!');</script>";
	}
	mysqli_query($GLOBALS['conn'],"UPDATE chzb_appdata set needauthor=$needauthor");
}

if(isset($_POST['clearlog'])){
	$result=mysqli_query($GLOBALS['conn'],"delete from chzb_adminrec");
	echo"<script>showindex=4;alert('后台记录已清空!');</script>";
}

//初始化
$result=mysqli_query($GLOBALS['conn'],"select dataver,appver,setver,dataurl,appurl,adtext,showtime,showinterval,splash,needauthor,decoder,buffTimeOut,tiploading,tipuserforbidden,tipuserexpired,tipusernoreg,trialdays,qqinfo,up_size,up_sets,up_text from chzb_appdata");
if($row=mysqli_fetch_array($result)){
	$adtext=$row['adtext'];
	$dataver=$row['dataver'];
	$appver=$row['appver'];
	$setver=$row['setver'];
	$dataurl=$row['dataurl'];
	$appurl=$row['appurl'];
	$showtime=$row['showtime'];
	$showinterval=$row['showinterval'];
	$splash=$row['splash'];
	$needauthor=$row['needauthor'];
	$decoder=$row['decoder'];
	$buffTimeOut=$row['buffTimeOut'];
	$tiploading=$row['tiploading'];
	$tipusernoreg=$row['tipusernoreg'];
	$tipuserexpired=$row['tipuserexpired'];
	$tipuserforbidden=$row['tipuserforbidden'];
	$trialdays=$row['trialdays'];
  	$qqinfo=$row['qqinfo'];
  	$up_size=$row["up_size"];
  	$up_sets=$row["up_sets"];
  	$up_text=$row["up_text"];

}
unset($row);
mysqli_free_result($result);

if($needauthor==1){
	$closeauthor="关闭授权";
}else{
	$closeauthor="开启授权";
}

if(get_config('showwea')==1){
	$showwea='checked="checked"';
}else{
	$showwea="";
}

// 创建目录
$imgdir="../images";
if (! is_dir ( $imgdir )) {
    @mkdir ( $imgdir, 0755, true ) or die ( '创建文件夹失败' );
}
$files = glob("../images/*.png");
?>

<style type="text/css">
	input{margin: 10px;}
	.adinfo{width:100%;height: 30%;}
	.adfont{padding-bottom:5px;}
	.bkinfo{width:100%;height: 70%;padding: 20px}
	ul li{list-style: none}
	hr{margin:10px;}
	.blogbox ul {display: flex;align-items: center;justify-content: center;}
	.blogbox li {
	background: #f0f0f0;
	border-radius: 5px;
	margin-top: 35px;
	margin-left: 92px;
	width: 850px;	
	height: auto;
	display: list-item;
	text-align: center;
	}
	.blogbox li .title{
		background: #345;padding: 5px;
		color:#fff;
	}
	.blogbox li td{
		padding: 5px;
		text-align: center;
	}
	.leftmenu{
		float: left;
		top: 150px;
		position: fixed;
		background-color: #112233;
		border: 1px solid #ccc;
	}
	.leftmenu ul{
		padding-left: 0px;
	}
	.leftmenu li{
		padding: 10px 20px 10px 20px;
		margin: 10px 0px 10px 0px;
		text-align: center;
		border:1px,solid #fff;
	}
	.leftmenu li:hover{
		background-color: #1122ee;
	}
	.leftmenu li a{
		color:#fff;
	}
	li p{padding-left: 25px;font-size: 17px;}
	form{margin-top: 0px;margin-bottom: 0px;margin-block-end: 0px;}
</style>

<script type="text/javascript">
function submitForm(){
	$("#appsetform").submit();
}
function weaForm(){
	$("#weaform").submit();
}

function showli(index){
	$(".blogbox li").hide();
	$($(".blogbox li")[index]).fadeIn();
	$(".leftmenu li").css("background","none");
	$(".leftmenu").css("background","#345");
	$($(".leftmenu li")[index]).css("background",$("#topnav").css("background-color"));
	showindex=index;
}
</script>

	<div class='leftmenu'>
		<ul>
			<li><a href="#" onclick="showli(0)">系统公告</a></li>
			<li><a href="#" onclick="showli(1)">系统备份</a></li>
			<li><a href="#" onclick="showli(2)">APP设置</a></li>
			<li><a href="#" onclick="showli(3)">背景图片</a></li>
			<li><a href="#" onclick="showli(4)">后台记录</a></li>		
			<li><a href="#" onclick="showli(5)">修改密码</a></li>
			<li id='adminset'><a href="#" onclick="showli(6)">管理员设置</a></li>
			<li><a href="#" onclick="showli(7)">免责声明</a></li>
		</ul>
	</div>

	<div class='blogbox'>
		<br>
		<ul>
			<li>
				<span align="left">
					<div class="title">系统公告</div>
				</span>
				<form id="weaform" method="post" align="center" style="padding: 20px">
					天气APP_ID&nbsp;&nbsp;<input type="text" name="weaapi_id" value="<?php echo get_config('weaapi_id');?>" size=15>
					天气APP_KEY<input type="text" name="weaapi_key" size=15 value="<?php echo get_config('weaapi_key');?>" >
					&nbsp;<input type="checkbox" name="showwea" <?php echo $showwea;?> onchange="weaForm()" />显示天气
				</form>
				<form method="post" align="left" style="padding: 20px">
			          	<div class="adfont">系统公告：</div>
						<TEXTAREA style="height: 180px;" class="adinfo"  name="adtext"><?php echo $adtext ?></TEXTAREA>
						<br><br>         
			          	<div class="adfont">预留文字：</div>
						<TEXTAREA style="height: 180px;" class="adinfo" name="qqinfo"><?php echo $qqinfo;?></TEXTAREA>
						<br>
						<div style="text-align:center;vertical-align:middel;padding-top: 10px;">
							显示时间（秒）&nbsp;&nbsp;<input type="text" name="showtime" value="<?php echo $showtime;?>" size=20>
							显示间隔（分）<input type="text" name="showinterval" size=20 value="<?php echo $showinterval;?>" >
							<input type="submit" name="submit" value="&nbsp;&nbsp;保&nbsp;&nbsp;存&nbsp;&nbsp;">
						</div>
				</form>
			</li>

			<li>
				<span align="left">
					<div class="title">系统备份</div>
				</span>
				<form method="post" align=center style="padding: 20px">
					<TEXTAREA style="height: 450px;" class="bkinfo" name="userdata"><?php echo $userdata;?></TEXTAREA>
					授权天数<input type="text" name="exp" value="365" size="5">&nbsp;&nbsp;
					备注<input type="text" name="marks" value="" size="10">
					<input type="submit" name="submitimportid" value="用户账号导入">
					<input type="submit" name="submitexport" value="导出用户数据">
					<input type="submit" name="submitimport" value="导入用户数据">		
					<br>
					<a target="_blank" href="dbbackup.php"><font color=blue>系统备份</font></a>
					<a target="_blank" href="dbrestore.php" onclick="return confirm('确认请全部数据恢复到上次备份的状态？恢复过程中不要进行任何管理操作。')"><font color=blue>系统恢复</font></a>
					<a target="_blank" href="randkey.php" onclick="return confirm('确认更新randkey吗？')"><font color=blue>更新秘钥</font></a>
				</form>
			</li>

			<li>
				<span align="left">
					<div class="title">APP设置</div>
				</span>
				<form id="weaform" method="post" align="center" style="padding: 10px">
					应用名&nbsp;&nbsp;<input type="text" name="app_appname" value="<?php echo get_config('app_appname');?>" size=5>
					应用包名<input type="text" name="app_packagename" size=15 value="<?php echo get_config('app_packagename');?>" >
					应用签名<input type="text" name="app_sign" size=5 value="<?php echo get_config('app_sign');?>" >
					<input type="submit" name="submitappinfo" value="保存">
				</form>

				<hr>

				<form method="post" id="appsetform" style="padding: 10px">			
					<div style="color: black;">
						默认解码模式：
						<select name="decodersel" onchange="submitForm()">
							<?php
							switch ($decoder) {
								case '0':
									echo "<option value='0' selected=\"selected\">智能解码</option>";
									echo "<option value='1'>硬件解码</option>";
									echo "<option value='2'>软件解码</option>";
									break;
								case '1':
									echo "<option value='0'>智能解码</option>";
									echo "<option value='1' selected=\"selected\">硬件解码</option>";
									echo "<option value='2'>软件解码</option>";
									break;
								case '2':
									echo "<option value='0'>智能解码</option>";
									echo "<option value='1'>硬件解码</option>";
									echo "<option value='2' selected=\"selected\">软件解码</option>";
									break;
								default:
									echo "<option value='0' selected=\"selected\">硬件解码</option>";
									echo "<option value='1'>软件解码</option>";
									break;
							}
							?>				
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;默认超时跳转：
						<select name="buffTimeOut" onchange="submitForm()">
							<?php
							$checkString5='';
							$checkString10='';
							$checkString15='';
							$checkString20='';
							$checkString25='';
							$checkString30='';
							switch ($buffTimeOut) {
								case 5:
									$checkString5="selected=\"selected\"";
									break;
								case 10:
									$checkString10="selected=\"selected\"";
									break;
								case 15:
									$checkString15="selected=\"selected\"";
									break;
								case 20:
									$checkString20="selected=\"selected\"";
									break;
								case 25:
									$checkString25="selected=\"selected\"";
									break;
								case 30:
									$checkString30="selected=\"selected\"";
									break;
								default:
									break;
							}
							echo "<option value='5' $checkString5 >5 秒</option>";
							echo "<option value='10' $checkString10 >10 秒</option>";
							echo "<option value='15' $checkString15 >15 秒</option>";
							echo "<option value='20' $checkString20 >20 秒</option>";
							echo "<option value='25' $checkString25 >25 秒</option>";
							echo "<option value='30' $checkString30 >30 秒</option>";
							?>
						</select>&nbsp;&nbsp;
						试用天数：
						<input type="text" name="trialdays" value="<?php echo $trialdays ?>" size="3">
						<input type="submit" name="submittrialdays" value="修改">	
						<font color=blue>提示：-999为永不到期。</font>
					</div>
				</form>

				<br>

				<form method="post" align=center>
						提示：关闭后，APP进入无需授权。<input type="submit" name="submitcloseauthor" value="<?php echo $closeauthor;?>">
						<input type="hidden" name="needauthor" value="<?php echo $needauthor;?>">
					</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<font color=red>
						推送清除数据：
						<?php echo $setver; ?>
						<input type="submit" name="submitsetver" value="确定">
					</font>
				</form>

				<hr>

				<form method="post">
					升级地址<input type="text" size="80" name="appurl" value="<?php echo $appurl; ?>"/><br>
					当前版本<input type="text" size="18" name="appver" value="<?php echo $appver; ?>"/>
					软件大小<input type="text" size="18" name="up_size" value="<?php echo $up_size; ?>"/>
					<?php
						if($up_sets==1){
							$seta="checked";
							$setb="";
						}else{
							$seta="";
							$setb="checked";
						}
					?>
					强制更新<input type="radio" size="15" name="up_sets" value="1" <?php echo $seta; ?> >是
						<input type="radio" size="15" name="up_sets" value="0" <?php echo $setb; ?> >否<br>
					更新内容<br>
					<textarea style="height: 135px;margin: 5px;" name="up_text" rows="10" cols="92"><?php echo $up_text; ?></textarea><br>
					<input type="submit" name="submit" value="&nbsp;推送升级&nbsp;">
				</form>

				<hr> 

				<form method="post">
					<p>节目加载提示：<input type="text" size="80" name="tiploading" value="<?php echo $tiploading;?>"></p>
					<p>授权到期提示：<input type="text" size="80" name="tipuserexpired" value="<?php echo $tipuserexpired;?>"></p>
					<p>账号停用提示：<input type="text" size="80" name="tipuserforbidden" value="<?php echo $tipuserforbidden;?>"></p>
					<p>未予授权提示：<input type="text" size="80" name="tipusernoreg" value="<?php echo $tipusernoreg;?>"></p>
					<p><input type="submit" name="submittipset" value="&nbsp;&nbsp;保&nbsp;存&nbsp;&nbsp;"></p>
				</form>
			</li>

			<li>
				<span align="left">
					<div class="title">背景图片</div>
				</span>		
				<table border="1" bordercolor="#00f" style="border-collapse:collapse;margin: 20px;width: 90%">
					<tr height="35px">
						<td>图片名称</td>
						<td>文件时间</td>
						<td>图片大小</td>
						<td>操作</td>
					</tr>
					<?php
					foreach ($files as $file) {
						$fctime=date("Y-m-d H:i:s",filectime($file));
						$fsize=filesize($file);
						$url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]; 
						$splashurl=dirname($url).'/'.$file;
						$file=basename($file);
						if($fsize>=1024){
							$fsize=round($fsize / 1024 * 100) / 100 . ' KB';
						}else{
							$fsize=$fsize ." B";
						}
						echo "<tr height='35px'>
								<td>$file</td>
								<td>$fctime</td>
								<td>$fsize</td>
								<td>
									<form method='post'>
										<button type='button' onclick=\"javascript:window.open('$splashurl')\">预览</button>
										<input type='hidden' name='file' value='$file'>
										<input type='submit' name='submitdelbg' onclick=\"return confirm('确认删除？')\" value='删除'>
									</form>
								</td>
							</tr>";
					}
					unset($files);
					?>
					</table>
					<font color="red">提示：图片仅支持PNG格式，不超过800KB，多张图片为随机显示。</font>
					<form method="post" enctype="multipart/form-data" style="padding: 20px">
						<input type="file" name="splash" accept="image/png" />
						<input type="submit" name="submitsplash" value="&nbsp;&nbsp;开始上传&nbsp;&nbsp;">
					</form>
			</li>

			<li style="text-align: left;">
				<span align="left">
					<div class="title">后台记录</div>
				</span>
				<div style="padding: 20px;padding-top:10px;padding-bottom:30px">
					<table border="1" bordercolor="#a0c6e5" style="border-collapse:collapse;">
						<td width="300px" colspan="5">
							<form method="POST">
								<input type='submit' name='clearlog' value='清空记录'>
							</form>
						</td>
						<tr>
							<td width="100px">账号</td>
							<td width="200px">登入IP</td>
							<td width="200px">登入位置</td>
							<td width="200px">登入时间</td>
							<td width="200px">操作</td>
						</tr>
			
						<?php
						$result=mysqli_query($GLOBALS['conn'],"SELECT name,ip,loc,time,func from chzb_adminrec");
						while ($row=mysqli_fetch_array($result)) {
							$loguser=$row['name'];
							$logip=$row['ip'];
							$logloc=$row['loc'];
							$logtime=$row['time'];
							$logfunc=$row['func'];
							echo "<tr>
								<td>$loguser</td>
								<td>$logip</td>
								<td>$logloc</td>
								<td>$logtime</td>
								<td>$logfunc</td>
							</tr>";
						}
						unset($row);
						mysqli_free_result($result);
						?>
					</table>
				</div>
			</li>

			<li>
				<span align="left">
					<div class="title">修改密码</div>
				</span>
				<form method="post" style="padding: 20px">
					新安全码:<input type="password" name="newsecret_key" value="" size="80"><br>
					确认新安全码:<input type="password" name="newsecret_key_confirm" value="" size="75"><br>
					<input type="submit" name="submit" value="修改安全码">
					<input type="submit" name="closesecret_key" value="关闭安全码认证">
				</form>
				<hr>
				<form method="post" style="padding: 20px">
					用户名:<input type="text" name="username" value="admin" size="80"><br>
					旧密码:<input type="password" name="oldpassword" value="" size="80"><br>
					新密码:<input type="password" name="newpassword" value="" size="80"><br>
					<input type="submit" name="submit" value="修改密码">
				</form>
			</li>

			<li>
				<span align="left">
					<div class="title">管理员设定</div>
				</span>
					<center>
						<form method="POST" style="padding: 20px">
							<table border="1" bordercolor="#00f" style="border-collapse:collapse;margin:20px">
								<tr>
									<td width="20px"></td>
									<td width="180px">用户名</td>
									<td width="100px">识别授权</td>
									<td width="100px">用户管理</td>
									<td width="100px">异常检测</td>
									<td width="100px">EPG管理</td>
									<td width="100px">频道管理</td>
								</tr>
								<?php
								$result=mysqli_query($GLOBALS['conn'],"select name,author,useradmin,ipcheck,epgadmin,channeladmin from chzb_admin");
								while ($row=mysqli_fetch_array($result)) {
									$adminname=$row['name'];
									$author=$row['author'];
									$useradmin=$row['useradmin'];
									$ipcheck=$row['ipcheck'];
									$epgadmin=$row['epgadmin'];
									$channeladmin=$row['channeladmin'];
									if($author==1){
										$authorchecked=" checked='true'";
									}else{
										$authorchecked="";
									}
									if($useradmin==1){
										$useradminchecked=" checked='true'";
									}else{
										$useradminchecked="";
									}
									if($ipcheck==1){
										$ipcheckchecked=" checked='true'";
									}else{
										$ipcheckchecked="";
									}
									if($epgadmin==1){
										$epgadminchecked=" checked='true'";
									}else{
										$epgadminchecked="";
									}
									if($channeladmin==1){
										$channeladminchecked=" checked='true'";
									}else{
										$channeladminchecked="";
									}
									if($adminname == 'admin'){
										echo "<tr>
											<td width=\"20px\">⊗</td>
											<td width=\"180px\">admin</td>
											<td width=\"180px\"><input value='$adminname' name='author[]' type='checkbox' checked='true' disabled='true'></td>
											<td width=\"180px\"><input value='$adminname' name='useradmin[]' type='checkbox' checked='true' disabled='true'></td>
											<td width=\"180px\"><input value='$adminname' name='ipcheck[]' type='checkbox' checked='true' disabled='true'></td>
											<td width=\"180px\"><input value='$adminname' name='epgadmin[]' type='checkbox' checked='true' disabled='true'></td>
											<td width=\"180px\"><input value='$adminname' name='channeladmin[]' type='checkbox' checked='true' disabled='true'></td>
										</tr>";
									}else{
										echo "<tr>
											<td width=\"20px\"><input value='$adminname' name='adminname[]' type='checkbox'></td>
											<td width=\"180px\">$adminname</td>
											<td width=\"180px\"><input value='$adminname' name='author[]' type='checkbox' $authorchecked ></td>
											<td width=\"180px\"><input value='$adminname' name='useradmin[]' type='checkbox' $useradminchecked ></td>
											<td width=\"180px\"><input value='$adminname' name='ipcheck[]' type='checkbox' $ipcheckchecked ></td>
											<td width=\"180px\"><input value='$adminname' name='epgadmin[]' type='checkbox' $epgadminchecked ></td>
											<td width=\"180px\"><input value='$adminname' name='channeladmin[]' type='checkbox' $channeladminchecked ></td>
										</tr>";
									}
								}
								unset($row);
								mysqli_free_result($result);
								mysqli_close($GLOBALS['conn']);
								?>
							</table>
							<input type="submit" name="deleteadmin" value="&nbsp;&nbsp;&nbsp;删除选中&nbsp;&nbsp;&nbsp;">
							<input type="submit" name="saveauthorinfo" value="&nbsp;&nbsp;&nbsp;保存权限设定&nbsp;&nbsp;&nbsp;">
							<br>
							用户名<input type="text" name="addadminname" size="10">
							密码<input type="password" name="addadminpsw" size="10">
							<input type="submit" name="adminadd" value="增加管理员">
					</form>
				</center>
			</li>

			<li style="text-align: left;">
				<span align="left">
					<div class="title">免责声明</div>
				</span>
				<div style="padding: 20px;padding-top:10px;padding-bottom:30px">
				<h3 align="center">免责声明</h3>
					<p>1、软件支持http rtsp rtmp m3u8 flv mp4 msc p2p tvbus vjms等等的主流格式。</p>
					<p>2、本软件仅用作个人娱乐，请勿用于从事违法犯罪活动，开发者不对使用此软件引起的问题承担任何责任。</p>
					<p>3、如果您喜欢本软件并准备长期使用，请购买正版，支持软件开发者继续改进和增强本软件的功能。</p>
					<p>4、本软件不保证能兼容和适用于所有 Android 平台和系统，有可能引起冲突和导致不可预测的问题出现。</p>
					<p>5、使用本软件与管理后台平台的视为同意以上条款，如有违反相关法律法规请自行承担相应法律责任。</p>
				</div>
			</li>
		</ul>
	</div>

<script type="text/javascript">
showli(showindex);
if(showadmin==0){
	$("#adminset").hide();
}
if(showsrcset==0){
    $("#srcset").hide();
}
</script>

