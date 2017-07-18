

<?php 
	session_start();
	if(!$_SESSION["userName"]){
		echo "<meta http-equiv='content-type' content='text/html; charset=UTF-8'>";
		echo "<SCRIPT Language=javascript type=\"text/javascript\">"; 
		echo "window.alert('未登錄!!!')";  
		echo "</SCRIPT>";
		echo "<script language=\"javascript\">"; 
		echo "top.location.href='http://".$_SERVER['HTTP_HOST']."/SwipeCard/Login.php'";
		echo "</script>"; 
	}
	$userName = $_SESSION['userName'];
	// echo $_SESSION["ssn_usr"];
	// echo "username ".$userName;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1">
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title></title> <!-- Icons -->
	<link href="assets/css/icons.css" rel="stylesheet" />
	<!-- Bootstrap stylesheets (included template modifications) -->
	<link href="assets/css/bootstrap.css" rel="stylesheet" />
	<!-- Plugins stylesheets (all plugin custom css) -->
	<link href="assets/css/plugins.css" rel="stylesheet" />
	<!-- Main stylesheets (template main css file) -->
	<link href="assets/css/main.css" rel="stylesheet" />

	<link href="Css/default.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css"
		href="js/themes/default/easyui.css" />
	<link rel="stylesheet" type="text/css" href="js/themes/icon.css" />
	<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="js/jquery.easyui.pack.js"></script>

	<script type="text/javascript" src='js/outlook2.js'>
	</script>
	<style>
		#loginOut {
			color: #ffffff;
		}
	</style>
	<script type="text/javascript">
		var id = 2;
		if(id==2){
			var _menus = {
					"menus" : [ {
						"menuid" : "1",
						"icon" : "icon-sys",
						"menuname" : "加班單管理",
						"menus" : [ {
							"menuname" : "加班單審核",
							"icon" : "icon-nav",
							"url" : "show_overtime_lineleader.php"
						},{
							"menuname" : "修改密碼",
							"icon" : "icon-nav",
							"url" : "xgmm.php"
						}]
					}]
				};
		}
		
		

		$(function() {

			openPwd();
			//
			$('#editpass').click(function() {
				$('#w').window('open');
			});

			$('#btnEp').click(function() {
				serverLogin();
			})

			$('#btnCancel').click(function() {
				closePwd();
			})

			$('#loginOut').click(function() {
				$.messager.confirm('系统提示', '您确定要退出本次登录吗?', function(r) {
					if (r) {
						location.href = '/DemoLogin/loginOut.php';
					}
				});

			})

		});
	</script>
</head>
<body class="easyui-layout" style="overflow-y: hidden" scroll="no">
	<noscript>
		<div
			style="position: absolute; z-index: 100000; height: 2046px; top: 0px; left: 0px; width: 100%; background: white; text-align: center;">
			<img src="images/noscript.gif" alt='抱歉，请开启脚本支持！' />
		</div>
	</noscript>
	<div region="north" split="true" border="false"
		style="overflow: hidden; height: 30px; background: #75b9e6;">

		<i class="im-windows8 text-logo-element animated bounceIn"></i><span
			class="text-logo">FOX</span><span class="text-slogan">LINK</span> <span
			style="float: right; padding-right: 20px;" class="head">欢迎 <?php echo $userName;?>
			<a href="/SwipeCard/loginout.php" id="loginOut">安全退出</a>
		</span>
	</div>
	<div region="south" split="true"
		style="height: 30px; background: #D2E0F2;">
		<div class="footer">By Paul_Qin Tel:#32910</div>
	</div>
	<div region="west" split="true" title="导航菜单" style="width: 180px;"
		id="west">
		<div class="easyui-accordion" fit="true" border="false">
			<!--  导航内容 -->

		</div>

	</div>
	<div id="mainPanle" region="center"
		style="background: #eee; overflow-y: hidden">
		<div id="tabs" class="easyui-tabs" fit="true" border="false">
			<div title="欢迎使用" style="padding: 20px; overflow: hidden;" id="home">
				<h1>歡迎來到實時刷卡系統</h1>
			</div>
		</div>
	</div>


	

	<div id="mm" class="easyui-menu" style="width: 150px;">
		<div id="mm-tabclose">关闭</div>
		<div id="mm-tabcloseall">全部关闭</div>
		<div id="mm-tabcloseother">除此之外全部关闭</div>
		<div class="menu-sep"></div>
		<div id="mm-tabcloseright">当前页右侧全部关闭</div>
		<div id="mm-tabcloseleft">当前页左侧全部关闭</div>
		<div class="menu-sep"></div>
		<div id="mm-exit">退出</div>
	</div>


</body>
</html>