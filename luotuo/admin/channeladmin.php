<script>
	var leftbgColor='#112233';
	var showindex=0;
	var maxindex=0;
</script>
<?php

include_once "nav.php";

if($_SESSION['channeladmin']==0){
	echo"<script>alert('你无权访问此页面！');history.go(-1);</script>";
	exit();
}

?>

<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
	(function($){$.session={_id:null,_cookieCache:undefined,_init:function()
	{if(!window.name){window.name=Math.random();}
	this._id=window.name;this._initCache();var matches=(new RegExp(this._generatePrefix()+"=([^;]+);")).exec(document.cookie);if(matches&&document.location.protocol!==matches[1]){this._clearSession();for(var key in this._cookieCache){try{window.sessionStorage.setItem(key,this._cookieCache[key]);}catch(e){};}}
	document.cookie=this._generatePrefix()+"="+ document.location.protocol+';path=/;expires='+(new Date((new Date).getTime()+ 120000)).toUTCString();},_generatePrefix:function()
	{return'__session:'+ this._id+':';},_initCache:function()
	{var cookies=document.cookie.split(';');this._cookieCache={};for(var i in cookies){var kv=cookies[i].split('=');if((new RegExp(this._generatePrefix()+'.+')).test(kv[0])&&kv[1]){this._cookieCache[kv[0].split(':',3)[2]]=kv[1];}}},_setFallback:function(key,value,onceOnly)
	{var cookie=this._generatePrefix()+ key+"="+ value+"; path=/";if(onceOnly){cookie+="; expires="+(new Date(Date.now()+ 120000)).toUTCString();}
	document.cookie=cookie;this._cookieCache[key]=value;return this;},_getFallback:function(key)
	{if(!this._cookieCache){this._initCache();}
	return this._cookieCache[key];},_clearFallback:function()
	{for(var i in this._cookieCache){document.cookie=this._generatePrefix()+ i+'=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';}
	this._cookieCache={};},_deleteFallback:function(key)
	{document.cookie=this._generatePrefix()+ key+'=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';delete this._cookieCache[key];},get:function(key)
	{return window.sessionStorage.getItem(key)||this._getFallback(key);},set:function(key,value,onceOnly)
	{try{window.sessionStorage.setItem(key,value);}catch(e){}
	this._setFallback(key,value,onceOnly||false);return this;},'delete':function(key){return this.remove(key);},remove:function(key)
	{try{window.sessionStorage.removeItem(key);}catch(e){};this._deleteFallback(key);return this;},_clearSession:function()
	{try{window.sessionStorage.clear();}catch(e){for(var i in window.sessionStorage){window.sessionStorage.removeItem(i);}}},clear:function()
	{this._clearSession();this._clearFallback();return this;}};$.session._init();})(jQuery);
</script>

<style type="text/css">
	a{
		text-decoration: none;
		font-size:16px;
		color:#0000ff;
	}
	#pdlist{padding-left: 0px;padding-top: 5px;}
	ul li{list-style: none}
	textarea{
		font-size:16px;
		font-family:Fixedsys;
		line-height: 1.5;
		width:100%;
		height: 76%;
		white-space:nowrap; 
		overflow:scroll;
	}
	input{
		margin:5px;
	}
	img{
		vertical-align: middle;
		padding-left: 5px;
	}
	pre{
		white-space:pre-wrap;
		white-space:-moz-pre-wrap;
		white-space:-pre-wrap;
		white-space:-o-pre-wrap;
		word-wrap:break-word;
	}
</style>

<?php
ini_set('display_errors',1);						
ini_set('display_startup_errors',1);	 
error_reporting(E_ERROR);

//对分类进行重新排序

$categorytype=$_GET['categorytype'];

function sort_id(){
global $categorytype;
if ($categorytype=='default'){$numCount=1;}else{$numCount=50;}
$result=mysqli_query($GLOBALS['conn'],"SELECT * from chzb_category where type='$categorytype' order by id");
while ($row=mysqli_fetch_array($result)) {
		$name=$row['name'];
		mysqli_query($GLOBALS['conn'],"UPDATE chzb_category set id=$numCount where name='$name'");
		unset($name);
		$numCount++;
	}
	unset($row);
	mysqli_free_result($result);
}
sort_id();

//检测上下移的ID参数是否存在
function chk_sort_id(){
	global $categorytype,$minid,$maxid;
	$result=mysqli_query($GLOBALS['conn'],"SELECT min(id),max(id) from chzb_category where type='$categorytype'");
	if($row=mysqli_fetch_array($result)){
		$minid=$row['min(id)'];
		$maxid=$row['max(id)'];
	}
}
chk_sort_id();
	
//增加频道列表
function add_channel_list($pd,$listurl){
	$getlist=file_get_contents($listurl);
	if (!empty($getlist)){
		mysqli_query($GLOBALS['conn'],"delete from chzb_channels where category='$pd'");
		$rows=explode("\n",$getlist);
		$rows=preg_replace('# #','',$rows);
		$rows=preg_replace('/高清/', '', $rows);
		$rows=preg_replace('/FHD/', '', $rows);
		$rows=preg_replace('/HD/', '', $rows);
		$rows=preg_replace('/SD/', '', $rows);
		$rows=preg_replace('/\[.*?\]/', '', $rows);
		$rows=preg_replace('/\#genre\#/', '', $rows);
		$rows=preg_replace('/ver\..*?\.m3u8/', '', $rows);
		$rows=preg_replace('/t\.me.*?\.m3u8/', '', $rows);
		$rows=preg_replace("/https(.*)www.bbsok.cf[^>]*/","",$rows);
		foreach($rows as $row){
			if (strpos($row, ',') !== false){
				$ipos=strpos($row, ',');	
				$channelname=substr($row,0,$ipos);
				$source=substr($row,$ipos+1);
				if(strpos($source,'#')!==false){
					$sources=explode("#",$source);
					foreach ($sources as $src) {
						$src2=str_replace("\"", "", $src);
						$src2=str_replace("\'", "", $src2);
						$src2=str_replace("}", "", $src2);
						$src2=str_replace("{", "", $src2);
						$channelurl=mysqli_query($GLOBALS['conn'],"SELECT url from chzb_channels order by id");
						while ($url=mysqli_fetch_array($channelurl)) {
							if($src2 == $url['url']){$src2='';}
						}
						unset($url);
						mysqli_free_result($channelurl);
						if($channelname!=''&&$src2!=''){
							mysqli_query($GLOBALS['conn'],"INSERT INTO chzb_channels VALUES (null,'$channelname','$src2','$pd')");
						}
					}
				}else{
					$src2=str_replace("\"", "", $source);
					$src2=str_replace("\'", "", $src2);
					$src2=str_replace("}", "", $src2);
					$src2=str_replace("{", "", $src2);
					$channelurl=mysqli_query($GLOBALS['conn'],"SELECT url from chzb_channels order by id");
					while ($url=mysqli_fetch_array($channelurl)) {
						if($src2 == $url['url']){$src2='';}
					}
					unset($url);
					mysqli_free_result($channelurl);
					if($channelname!=''&&$src2!=''){
						mysqli_query($GLOBALS['conn'],"INSERT INTO chzb_channels VALUES (null,'$channelname','$src2','$pd')");
					}
				}
			}
		}
		unset($rows,$getlist);
		return 0;
	}
	return 1;
}

if(isset($_GET['pd'])){
	$pd=$_GET['pd'];
}else{
	$result=mysqli_query($GLOBALS['conn'],"SELECT name from chzb_category order by id");
	if($row=mysqli_fetch_array($result)){
		$pd=$row['name'];
		unset($row);
		mysqli_free_result($result);
	}else{
		mysqli_free_result($result);
		$pd='';
	}
}

	mysqli_query($GLOBALS['conn'],"set names utf8");

	if(isset($_POST['submit'])&&isset($_POST['pd'])&&isset($_POST['srclist'])){
		$pd=$_POST['pd'];
		$srclist=$_POST['srclist'];
		$showindex=$_POST['showindex'];
		
		mysqli_query($GLOBALS['conn'],"delete from chzb_channels where category='$pd'");
		$rows=explode("\r\n",$srclist);
		foreach($rows as $row){
			if (strpos($row, ',') !== false){
				$ipos=strpos($row, ',');	
				$channelname=substr($row,0,$ipos);
				$source=substr($row,$ipos+1);
				if(strpos($source,'#')!==false){
					$sources=explode("#",$source);
					foreach ($sources as $src) {
						$src2=str_replace("\"", "", $src);
						$src2=str_replace("\'", "", $src2);
						$src2=str_replace("}", "", $src2);
						$src2=str_replace("{", "", $src2);
						if($channelname!=''&&$src2!=''){
							mysqli_query($GLOBALS['conn'],"INSERT INTO chzb_channels VALUES (null,'$channelname','$src2','$pd')");
						}
					}
				}else{
					$src2=str_replace("\"", "", $source);
					$src2=str_replace("\'", "", $src2);
					$src2=str_replace("}", "", $src2);
					$src2=str_replace("{", "", $src2);
					if($channelname!=''&&$src2!=''){
						mysqli_query($GLOBALS['conn'],"INSERT INTO chzb_channels VALUES (null,'$channelname','$src2','$pd')");
					}
				}
			}
		}
		unset($rows,$srclist);
		echo"<script>showindex=$showindex;alert('保存成功');</script>。";
	}

	if(isset($_POST['submit'])&&isset($_POST['category'])){
		$category=$_POST['category'];
		$cpass=$_POST['cpass'];
		if($category==""){
			echo "<script>alert('类别名称不能为空');</script>";
		}else{
			$result=mysqli_query($GLOBALS['conn'],"SELECT max(id) from chzb_category where type='$categorytype'");
			if($row=mysqli_fetch_array($result)){			
				if($row[0]>0){
					$numCount=$row[0]+1;
				}
			}
			unset($row);
			mysqli_free_result($result);
			$sql = "SELECT name FROM chzb_category where name='$category'";
			$result = mysqli_query($GLOBALS['conn'],$sql);
			if(mysqli_fetch_array($result)){
				mysqli_free_result($result);
				echo "<script>showindex=$showindex;alert('该栏目已经存在');</script>";
			}else{
				mysqli_query($GLOBALS['conn'],"INSERT INTO chzb_category (id,name,psw,type) VALUES ($numCount,'$category','$cpass','$categorytype')");
				$result=mysqli_query($GLOBALS['conn'],"SELECT * from chzb_category");
				$showindex=mysqli_num_rows($result)-1;
				echo "<script>showindex=$showindex;alert('增加类别$category 成功');</script>";
				$pd=$category;
				mysqli_free_result($result);
			}
		}
	}
	
	//增加外部列表
	if(isset($_POST['addthirdlist'])){
		$category=$_POST['thirdlistcategory'];
		$listurl=$_POST['thirdlisturl'];
		if($category==""){
			echo "<script>alert('类别名称不能为空');</script>";
		}else{
			$result=mysqli_query($GLOBALS['conn'],"SELECT max(id) from chzb_category where type='$categorytype'");
			if($row=mysqli_fetch_array($result)){			
				if($row[0]>0){
					$numCount=$row[0]+1;
				}
			}
			unset($row);
			mysqli_free_result($result);
			$sql = "SELECT name FROM chzb_category where name='$category'";
			$result = mysqli_query($GLOBALS['conn'],$sql);
			if(mysqli_fetch_array($result)){
				mysqli_free_result($result);
				echo "<script>showindex=$showindex;alert('该栏目已经存在');</script>";
			}else{
				mysqli_query($GLOBALS['conn'],"INSERT INTO chzb_category (id,name,psw,type,url) VALUES ($numCount,'$category','$cpass','$categorytype','$listurl')");
				$result=mysqli_query($GLOBALS['conn'],"SELECT * from chzb_category where $categorytype");
				$showindex=mysqli_num_rows($result)-1;
				mysqli_free_result($result);
				if (add_channel_list($category,$listurl)==0){
					echo "<script>showindex=$showindex;alert('增加列表$category 成功');</script>";
				}else{
					echo "<script>showindex=$showindex;alert('增加列表$category 失败');</script>";
					mysqli_query($GLOBALS['conn'],"delete from chzb_category where name='$category'");
				}
			}
		}
	}

	//更新外部列表
	if(isset($_POST['updatelist'])){
		$category=$_POST['thirdlist'];
		if($category==""){
			echo "<script>alert('列表名不能为空');</script>";
		}else{
			$result=mysqli_query($GLOBALS['conn'],"SELECT * from chzb_category where $categorytype");
			$showindex=mysqli_num_rows($result)-1;
			mysqli_free_result($result);
			$listurl=mysqli_query($GLOBALS['conn'],"SELECT url from chzb_category where name='$category'");
			if($row=mysqli_fetch_array($listurl)){$listurl=$row['url'];}
			if (add_channel_list($category,$listurl)==0){
				echo "<script>showindex=$showindex;alert('更新列表$category 成功');</script>";
			}else{
				echo "<script>showindex=$showindex;alert('更新列表$category 失败');</script>";
			}
		}
	}
	
	if(isset($_POST['submit_deltype'])&&isset($_POST['category'])){
		$category=$_POST['category'];
			$showindex=$_POST['showindex'];
		if($category==""){
				echo "<script>alert('类别名称不能为空');</script>";
		}else{
			$result=mysqli_query($GLOBALS['conn'],"SELECT id from chzb_category where name='$category'");
			if($row=mysqli_fetch_array($result)){
				$categoryid=$row[0];
				mysqli_query($GLOBALS['conn'],"UPDATE chzb_category set id=id-1 where id>$categoryid");
			}
			$sql = "delete from chzb_category where name='$category'";
			mysqli_query($GLOBALS['conn'],$sql);	
			mysqli_query($GLOBALS['conn'],"delete from chzb_channels where category='$category'");
			sort_id();
			echo "<script>showindex=$showindex-1;alert('$category 删除成功');</script>";
		}
	}

	if(isset($_POST['submit_modifytype'])&&isset($_POST['category'])){
		$category=$_POST['category'];	
		$cpass=$_POST['cpass'];
		$showindex=$_POST['showindex'];
		$category0=$_POST['typename0'];
		if($category==""){
			echo "<script>alert('类别名称不能为空');</script>";
		}else{
			mysqli_query($GLOBALS['conn'],"update chzb_category set name='$category',psw='$cpass' where name='$category0'");
			mysqli_query($GLOBALS['conn'],"UPDATE chzb_channels set category='$category' where category='$category0'");
			echo "<script>showindex=$showindex;alert('$category 修改成功');</script>";
			$pd=$category;
		}
	}

	if(isset($_POST['submit_moveup'])&&isset($_POST['category'])){
		$category=$_POST['category'];
		$showindex=$_POST['showindex'];
		$result=mysqli_query($GLOBALS['conn'],"SELECT id from chzb_category where name='$category'");
		if($row=mysqli_fetch_array($result)){
			$id=$row['id'];
			$preid=$id-1;
			if($preid >= $minid){
				mysqli_query($GLOBALS['conn'],"update chzb_category set id=id+1	where id=$preid");	
				mysqli_query($GLOBALS['conn'],"update chzb_category set id=id-1	where name='$category'");
				unset($row);
				mysqli_free_result($result);
				echo "<script>showindex=$showindex-1;</script>";
			}else {
				echo "<script>showindex=$showindex-1;alert('已经上移到最顶了！！')</script>";
			}
		}
	}
	
	if(isset($_POST['submit_movedown'])&&isset($_POST['category'])){
		$category=$_POST['category'];
		$showindex=$_POST['showindex'];
		$result=mysqli_query($GLOBALS['conn'],"SELECT id from chzb_category where name='$category'");
		if($row=mysqli_fetch_array($result)){
			$id=$row['id'];
			$nextid=$id+1;
			if($nextid <= $maxid){
				mysqli_query($GLOBALS['conn'],"update chzb_category set id=id-1	where id=$nextid'");
				mysqli_query($GLOBALS['conn'],"update chzb_category set id=id+1	where name='$category'");
				unset($row);
				mysqli_free_result($result);
				echo "<script>showindex=$showindex+1;</script>";
			}else{
				unset($row);
				mysqli_free_result($result);
				echo "<script>showindex=$showindex;alert('已经下移到最底了！！')</script>";
			}
		}
	}
	
	if(isset($_POST['submit_movetop'])&&isset($_POST['category'])){
		$category=$_POST['category'];
		$result=mysqli_query($GLOBALS['conn'],"SELECT Min(id) from chzb_category where type='$categorytype'");
		if($row=mysqli_fetch_array($result)){
			$id=$row[0]-1;				
			mysqli_query($GLOBALS['conn'],"update chzb_category set id=$id	where name='$category'");
			sort_id();
			echo "<script>showindex=0;</script>";
		}
		mysqli_free_result($result);
	}

	if(isset($_POST['submit'])&&isset($_POST['ver'])){
		$updateinterval=$_POST['updateinterval'];
		if(isset($_POST['autoupdate'])){			
			mysqli_query($GLOBALS['conn'],"update chzb_appdata set autoupdate=1,updateinterval=$updateinterval");
		}else{
			$ver=$_POST['ver'];
			$sql = "update chzb_appdata set dataver=$ver,autoupdate=0";
			mysqli_query($GLOBALS['conn'],$sql);	
		}
		echo "<script>alert('保存成功');</script>";
	}

	if(isset($_POST['checkpdname'])){ 
		mysqli_query($GLOBALS['conn'],"UPDATE chzb_category set enable=0");
		foreach ($_POST['enable'] as $pdenable) {				
			mysqli_query($GLOBALS['conn'],"UPDATE chzb_category set enable=1 where name='$pdenable'");		 	 
		}
	}

	$sql = "SELECT dataver,appver,autoupdate,updateinterval FROM chzb_appdata";
	$result = mysqli_query($GLOBALS['conn'],$sql);
	if($row = mysqli_fetch_array($result)) {
		$ver=$row['dataver'];
		$versionname=$row['appver'];
		$autoupdate=$row['autoupdate'];
		$updateinterval=$row['updateinterval'];
	}else{
		$ver="0";
		$autoupdate=0;
		$updateinterval=0;
	}
	unset($row);
	mysqli_free_result($result);

	if($autoupdate==1){
		$checktext="checked='true'";
	}else{
		$checktext='';
	}
?>

<div id="tip"></div>
<div style="float:left;width:99%;text-align: left;
border-top:1px solid #a0c6e5;
border-left:1px solid #a0c6e5;
border-right:1px solid #a0c6e5;
border-bottom: :0px solid #a0c6e5;">
	<table>
		<tr>
			<form method="post" id='autoupdate_form'>
				<input type="hidden" name="ver" value="<?php echo ($ver+1); ?>">
				间隔时间<input type="text" name='updateinterval' value="<?php echo $updateinterval ?>" size="5">分
				<?php echo"<input type=\"checkbox\" name=\"autoupdate\" value=\"$autoupdate\" $checktext>自动更新"?>
				<input type="submit" name="submit" value="&nbsp;&nbsp;保存设定&nbsp;&nbsp;"/>
			</form>
		</tr>
		<br>
		<tr>
			<form method="post">
				外部列表
				<select name="thirdlist">
					<option selected="selected" />
						<?php $result=mysqli_query($GLOBALS['conn'],"SELECT name from chzb_category where type='$categorytype' and url is not null");
						while ($row=mysqli_fetch_array($result)) {
							$listname=$row['name'];
							echo "<option>$listname</option>";
						}
						unset($row);
						mysqli_free_result($result); ?>
				</select>
				<input type="submit" name="updatelist" value="更新列表"/>
			</form>
				<input type="button" name="button" value="导入列表" onclick="document.getElementById('addthirdlist').style.display='block'" />
		</tr>
		<tr>
			<div style="display: none;" id="addthirdlist">
				<form method="post">
					新增分类<input type="text" name="thirdlistcategory" value="" size="10" />&nbsp;&nbsp;
					列表链接<input type="text" name="thirdlisturl" value="" size="64" />
					<input type="submit" name="addthirdlist" value="确定" onclick="{document.getElementById('addthirdlist').style.display='none';}" >
				</form>
			</div>
		</tr>
		<br>
		<tr>
			<form method="post">
				<input type="hidden" id="showindextype" name="showindex" value=""/>
				<input type="hidden" id="typename0" name="typename0" value=""/>
				分类名称<input id="typename" type="text" size="10" name="category" value="<?PHP echo $pd?>" />
				分类密码<input id="typepass" type="text" size="10" name="cpass" value="<?PHP echo $cpass?>" />
				<input type="submit" name="submit" value="增加分类">
				<input type="submit" name="submit_deltype" value="删除分类">
				<input type="submit" name="submit_modifytype" value="修改分类">
				<input type="submit" name="submit_moveup" value="上移分类">
				<input type="submit" name="submit_movedown" value="下移分类">
				<input type="submit" name="submit_movetop" value="移至最上">
			</form>
		</tr>
	</table>
</div>

<div style="float:left; width:19%;height:80%;" >
	<div id="cate" style="padding:5px;overflow:scroll;height: 98%;overflow-x:visible;">
		<script type="text/javascript">
			var pdname=[];
			var psw=[];
		</script>

		<center>
			<ul id="pdlist">
				<?php
					if ($categorytype=='vip'){
						$sql = "SELECT name,psw,enable FROM chzb_category where type='$categorytype' order by id";
					}else{
						$sql = "SELECT name,psw,enable FROM chzb_category where type='$categorytype' or type='thirdlist' order by id";
					}
					$result = mysqli_query($GLOBALS['conn'],$sql);
					$index=0;
					while($row = mysqli_fetch_array($result)) {
						$pdname=$row['name'];
						$enable=$row['enable'];
						$cpass=$row['psw'];
						if($enable==1){
							$check='checked=checked';
						}else{
							$check='';
						}
						if($cpass==''){
							$lockimg='';
						}else{
							$lockimg='*';
						}
						echo "<script>pdname[$index]='$pdname';psw[$index]='$cpass';</script>";
						echo "<li>
							<a href='#' onclick=\"showlist($index)\">
								<div class='pdlist' style='text-align:left;padding-left:25px;padding-top:5px;padding-bottom:5px;'>
									<input width='20px' type='checkbox' $check onclick='togglepdcheck(\"$pdname\",\"chzb_category\")'/>					
									$pdname $lockimg 
								</div>
							</a>
						</li>";
						$index++;
					}
					unset($row);
					mysqli_free_result($result);
					mysqli_close($GLOBALS['conn']);
				?>
			</ul>
		</center>
	</div>
</div>

<script>
	function togglepdcheck(pdname,catname){
		$.get("togglepd.php?pdname="+pdname+"&cat="+catname,function(data){$("#tip").html(data)});
	}
	function showlist(index){
		$("#pdlist li div").css("fontSize","22px");
		$("#cate").css("background","#3c444d");
		$("#pdlist li").css("background","none");		
		$(".pdlist").css("color","#d6d7d9");
		$("#pdlist li").css("border-left","3px solid #3c444d");
		$($("#pdlist li")[index]).css("background","#2c3138");
		$($("#pdlist li")[index]).css("border-left","3px solid #55ff77");
		$($(".pdlist")[index]).css("color","white");
		$("#srclist").val("正在加载中...");
		$("#srclist").load("getlist.php?pd="+pdname[index],function(data){
			$("#srclist").val(data);
		});
		$("#typename").val(pdname[index]);
		$("#typename0").val(pdname[index]);
		$("#typepass").val(psw[index]);
		$("#pd").val(pdname[index]);
		$("#showindex").val(index);
		$("#showindextype").val(index);
		showindex=index;
		$.session.set("<?php echo 'showindex';?>",showindex);
	}
	if(showindex==-1) showindex=$.session.get("<?php echo 'showindex';?>");
	$("#cate")[0].scrollTop=$.session.get("<?php echo 'scrollTop';?>");
	$("#cate").scroll(function(){
		$.session.set("<?php echo 'scrollTop';?>", $(this)[0].scrollTop);
	});
</script>

<div style="float:left;width:80%;text-align: center;">
	<form method="post">
		<input style="width:92%;" type="submit" name="submit" value="&nbsp;&nbsp;保&nbsp;&nbsp;&nbsp;&nbsp;存&nbsp;&nbsp;">
		<input type="hidden" id="pd" name="pd" value=""/>
		<input type="hidden" id="showindex" name="showindex" value=""/>
		<textarea id="srclist" name="srclist"></textarea>
	</form>
</div>
<script type="text/javascript">
	showlist(showindex);
</script>
