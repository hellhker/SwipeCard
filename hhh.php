<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<%@ page language="java" import="java.util.*,java.sql.*"
	pageEncoding="utf-8"%>
<%@ page contentType="text/html;charset=utf-8"%>
<%
	request.setCharacterEncoding("UTF-8");
	response.setCharacterEncoding("UTF-8");
	response.setContentType("text/html; charset=utf-8");
%>
<%
	String userName = (String) session.getAttribute("userName");
	String permissionLv = (String) session.getAttribute("permissionLv");
	if (userName == null) {
		response.sendRedirect("Login.html");
	}
%>
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
		var id = <%=permissionLv%>;
		if(id==2){
			var _menus = {
					"menus" : [ {
						"menuid" : "1",
						"icon" : "icon-sys",
						"menuname" : "加班單管理",
						"menus" : [ {
							"menuname" : "加班單審核",
							"icon" : "icon-nav",
							"url" : "show_overtime_backup.jsp"
						}, {
							"menuname" : "員工加班詳情",
							"icon" : "icon-nav",
							"url" : "EmployeeInfor.jsp"
						}, {
							"menuname" : "歷史刷卡情況",
							"icon" : "icon-nav",
							"url" : "swipecardtime_bylineno.jsp"
						}, {
							"menuname" : "加班單審核test",
							"icon" : "icon-nav",
							"url" : "show_overtime_backup.jsp"
						} ]
					}, {
						"menuid" : "8",
						"icon" : "icon-sys",
						"menuname" : "工時管理",
						"menus" : [ {
							"menuname" : "工時統計",
							"icon" : "icon-nav",
							"url" : "Compute_Hours.jsp"
						}, {
							"menuname" : "工時預警",
							"icon" : "icon-nav",
							"url" : "Compute_Hours_Warning.jsp"
						} ]
					}, {
						"menuid" : "56",
						"icon" : "icon-sys",
						"menuname" : "權限管理",
						"menus" : [ {
							"menuname" : "設定權限",
							"icon" : "icon-nav",
							"url" : "demo1.html"
						}, {
							"menuname" : "修改密碼",
							"icon" : "icon-nav",
							"url" : "demo1.html"
						} ]
					} ]
				};
		}else if(id==1){
			var _menus = {
			"menus" : [ {
				"menuid" : "1",
				"icon" : "icon-sys",
				"menuname" : "加班單管理",
				"menus" : [ {
					"menuname" : "加班單審核",
					"icon" : "icon-nav",
					"url" : "show_overtime_backup.jsp"
				}, {
					"menuname" : "員工加班詳情",
					"icon" : "icon-nav",
					"url" : "EmployeeInfor.jsp"
				}, {
					"menuname" : "歷史刷卡情況",
					"icon" : "icon-nav",
					"url" : "swipecardtime_bylineno.jsp"
				}]
			}, {
				"menuid" : "8",
				"icon" : "icon-sys",
				"menuname" : "工時管理",
				"menus" : [ {
					"menuname" : "工時統計",
					"icon" : "icon-nav",
					"url" : "Compute_Hours.jsp"
				}, {
					"menuname" : "工時預警",
					"icon" : "icon-nav",
					"url" : "Compute_Hours_Warning.jsp"
				} ]
			}, {
				"menuid" : "56",
				"icon" : "icon-sys",
				"menuname" : "權限管理",
				"menus" : [ {
					"menuname" : "設定權限",
					"icon" : "icon-nav",
					"url" : "demo1.html"
				}, {
					"menuname" : "修改密碼",
					"icon" : "icon-nav",
					"url" : "demo1.html"
				} ]
			} ]
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
						location.href = '/DemoLogin/loginOut.jsp';
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
			style="float: right; padding-right: 20px;" class="head">欢迎 <%=userName%>
			<a href="#" id="loginOut">安全退出</a>
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


	<!--修改密码窗口-->
	<div id="w" class="easyui-window" title="修改密码" collapsible="false"
		minimizable="false" maximizable="false" icon="icon-save"
		style="width: 300px; height: 150px; padding: 5px; background: #fafafa;">
		<div class="easyui-layout" fit="true">
			<div region="center" border="false"
				style="padding: 10px; background: #fff; border: 1px solid #ccc;">
				<table cellpadding=3>
					<tr>
						<td>新密码：</td>
						<td><input id="txtNewPass" type="text" class="txt01" /></td>
					</tr>
					<tr>
						<td>确认密码：</td>
						<td><input id="txtRePass" type="text" class="txt01" /></td>
					</tr>
				</table>
			</div>
			<div region="south" border="false"
				style="text-align: right; height: 30px; line-height: 30px;">
				<a id="btnEp" class="easyui-linkbutton" icon="icon-ok"
					href="javascript:void(0)"> 确定</a> <a id="btnCancel"
					class="easyui-linkbutton" icon="icon-cancel"
					href="javascript:void(0)">取消</a>
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