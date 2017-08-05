<?php 
	session_start();
	$access = $_SESSION["permission"];
	$depid = $_SESSION['depid'];
	$assistant_id = $_SESSION['assistant_id'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- 

<script src="assets/js/jquery-1.8.3.min.js"></script>
<link href="assets/css/bootstrap.css" rel="stylesheet">
<!-- Bootstrap stylesheets (included template modifications) -->

<link rel="stylesheet" type="text/css" href="easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="easyui/demo/demo.css">
	
	<script type="text/javascript" src="easyui/jquery.min.js"></script>
	<script type="text/javascript" src="easyui/jquery.easyui.min.js"></script>
<script src="Button_Plugins.js"></script>

<script type="text/javascript">
	function allCheck(check) {
		var checkbox = document.getElementsByName("checkbox");
		if (check.checked) {
			for ( var i = 0; i < checkbox.length; i++) {
				checkbox[i].checked = "checked";
			}
		} else {
			for ( var i = 0; i < checkbox.length; i++) {
				checkbox[i].checked = "";
			}
		}
	}

	function getValue() {//此為取recordID
		var checkbox = document.getElementsByName("checkbox");
		var record = [];
		for ( var i = 0; i < checkbox.length; i++) {
			if (checkbox[i].checked == true) {
				//alert("box[i]: "+checkbox[i].checked);
				record[i] = checkbox[i].value;
				console.log(record[i]);
				//alert(msg); //TODO
			}
		}
		return record;
	}

	function getValueA() {//此為取id/工號
		var checkbox1 = document.getElementsByName("testid");
		var record = [];
		for ( var i = 0; i < checkbox1.length; i++) {
			//alert("box[i]: "+checkbox[i].checked);
			record[i] = checkbox1[i].value;
			console.log(record[i]);
			//alert(msg); //TODO
		}
		return record;
	}

	var calInterval, calHour;
	function getValueB() {//獲取上下班時間并處理
		var checkbox1 = document.getElementsByName("stime");
		var checkbox2 = document.getElementsByName("etime");
		var checkbox3 = document.getElementsByName("checkbox");
		var shift = $("#Shift").val();
		var t_set = $("#Interval_Setting").val();//TODO
		var sdate = [];
		var sdate1 = [];
		var edate = [];
		var edate1 = [];
		var t_s = [];
		var calInterval = [];
		var calHour = [];
		var spellC = "cal_";
		var spellT = "tck_";
		var sp = [];//小時數小計
		var spT = [];//時間段
		var record = [];
		//加班時間
		var minus = 0;
		var str=null;
		t_s = t_set.split("*");
		for ( var i = 0; i < checkbox1.length; i++) {
			//alert("box[i]: "+checkbox[i].checked);
			sdate[i] = checkbox1[i].value;
			edate[i] = checkbox2[i].value;
			sdate1[i] = getDate1(sdate[i]);
			edate1[i] = getDate1(edate[i]);

			str = checkbox3[i].value.split("_");
			//record[i] = checkbox3[i].value;
			record[i] = str[0];
			//console.log("sdate: "+sdate[i]);
			//console.log("edate: "+edate[i]);
			//alert(msg); 
			//拼接表格id
			sp[i] = spellC + record[i];
			spT[i] = spellT + record[i];
			//console.log("sp: "+sp[i]);
			min = (edate1[i].getTime() - sdate1[i].getTime()) / 1000 / 3600;
			//console.log("min: "+typeof(sdate[i]));
			//console.log("min: "+min);
			//console.log("sdate1: "+sdate1[i]);
		}
		//加班計算為普通或是全天
		var cal = $("#overtimeCal").val();
		//console.log("cal: "+cal);
		//拼接加班時間段
		var sub;
		var tempInterval = [];
		var tempHour1,tempHour2;//17:30 19:30
		// var tempTime1;
		var tempTime2;//17 30
		// var tempTime1 = new Date();
		// var tempTime2 = new Date();//17 30
		var $tempEnd,$tempStart;
		for ( var i = 0; i < checkbox1.length; i++) {
			//minus-根據選擇的加班時間類型得出不同時段
			if (cal == "1") {
				if(shift=="D"){
				//console.log("sdate1[0]:"+ sdate1[0]);{
					sdate1[i].setHours(17, 30, 0);
				}else{
					// var tempDay  = getNextDay.time.getPreDate(1,sdate1[i]);
					var tempDay = sdate1[i].getDate();
					sdate1[i].setDate(tempDay+1);
					// console.log(sdate1[i]);
					sdate1[i].setHours(05, 00, 0);
				}
				
				minus = (edate1[i] - sdate1[i]);
				//console.log("sdate1[0]"+typeof(sdate1[0]));
				//console.log("時長minus: "+ minus);
				//根據選擇的加班時間類型得出不同時段
				//console.log(getHour(sdate1[i]));
			} else if (cal == "2") {
				
				// minus = (edate1[i] - sdate1[i]) / 3600000 - 2;
				if(shift=="D"){
					for(var j = 0;j<t_s.length;j++){
					// for(var j = 0;j<1;j++){
						if(j<t_s.length-1){
							tempInterval = t_s[j].split("-");
							tempHour1 = tempInterval[0].split(":");
							// console.log(tempHour1);
							tempHour2 = tempInterval[1].split(":");
						}else{
							tempHour1 = t_s[j].split(":");
							tempHour2 = edate1[i];
						}
						
						// console.log(tempHour1);
						
						// console.log(tempHour2);
						// tempTime1 = sdate1[i];
						var	tempTime1 = new Date(sdate1[i]);
						tempTime1 = tempTime1.setHours(tempHour1[0],tempHour1[1],0);
						// console.log("T1 :" + tempTime1);
						var	tempTime2 = new Date(edate1[i]);
						if(j<t_s.length-1){
							tempTime2 = tempTime2.setHours(tempHour2[0],tempHour2[1],0);
						}
						$calStart = sdate1[i]-tempTime1;
						$calEnd   = edate1[i]-tempTime2;
						// console.log(edate1[i]);
						// console.log(tempTime2);
						if($calEnd>0){
							$tempEnd = tempTime2;//10:30
							if($calStart>0){
								$tempStart = sdate1[i];//08:30
								$tempStart.setSeconds(0);
							}else{
								$tempStart = tempTime1;//07:40
							}
						}else{
							if(edate1[i]-tempTime1>0){
								$tempStart = tempTime1;//09:40
								$tempEnd = edate1[i];//10:30
								minus += $tempEnd - $tempStart;
								var tempStart = new Date($tempStart);
						var tempEnd = new Date($tempEnd);
						console.log("$tempStart:"+ tempStart);
						console.log("$tempEnd:"+ tempEnd);
						minus += $tempEnd - $tempStart;
								break;
							}else{
								break;
							}
						}
						var tempStart = new Date($tempStart);
						var tempEnd = new Date($tempEnd);
						console.log("$tempStart:"+ tempStart);
						console.log("$tempEnd:"+ tempEnd);
						minus += $tempEnd - $tempStart;
						// console.log(minus);
					}
				}else{
					// minus = (edate1[i] - sdate1[i]);
					for(var j = 0;j<t_s.length;j++){
					// for(var j = 0;j<1;j++){
						var x = $("#1").find("[name='yd']").val();
						tempTime = getDate1(x);
						// console.log(typeof(x));
						tempInterval = t_s[j].split("-");
						tempHour1 = tempInterval[0].split(":");
						tempHour2 = tempInterval[1].split(":");
						// console.log(edate1[i]);
						// console.log(tempHour2[0]);
						
						var	tempTime1 = new Date(tempTime);
						if(tempHour1[0]>0&&tempHour1[0]<12){
							 tempTime1 = new Date(tempTime.getTime() + 24 * 60 * 60 * 1000);
							 tempTime1 = tempTime1.setHours(tempHour1[0],tempHour1[1],0);
						}else{
							tempTime1 = tempTime.setHours(tempHour1[0],tempHour1[1],0);
						}
						
						// console.log("T1 :" + tempTime1);
						var	tempTime2 = new Date(tempTime);
						// console.log("T2 :" + tempTime2);
						// console.log(tempTime);
						if(tempHour2[0] >= 0 && tempHour2[0]<12){
							tempTime2 = new Date(tempTime.getTime() + 24 * 60 * 60 * 1000);
							tempTime2 = tempTime2.setHours(tempHour2[0],tempHour2[1],0);
						}else{
							tempTime2 = tempTime.setHours(tempHour2[0],tempHour2[1],0);
							console.log("321");
						}
						
						tempTime1 = new Date(tempTime1);
						console.log("T1 :" + edate1[i]);
						tempTime2 = new Date(tempTime2);
						console.log("T2 :" + tempTime2);
						
						$calStart = sdate1[i]-tempTime1;
						$calEnd   = edate1[i]-tempTime2;
						// console.log($calEnd);
						if($calEnd>0){
							$tempEnd = tempTime2;//10:30
							if($calStart>0){
								$tempStart = sdate1[i];//08:30
								$tempStart.setSeconds(0);
							}else{
								$tempStart = tempTime1;//07:40
							}
						}else{
							if(edate1[i]-tempTime1>0){
								$tempStart = tempTime1;//09:40
								$tempEnd = edate1[i];//10:30
								minus += $tempEnd - $tempStart;
								break;
							}else{
								break;
							}
						}
						minus += $tempEnd - $tempStart;
					}
				}
				
				//根據選擇的加班時間類型得出不同時段
			}
			// console.log(minus);
			minus = minus / 3600000;			
			minus = getNum(minus);
			sub = getHour(sdate1[i]) + "-" + getHour1(edate1[i]);
			//console.log("時段sub: "+sub);
			calInterval[i] = sub;
			calHour[i] = minus;
			//console.log("時段sub: " + sub);
			//console.log("時長minus: " + minus);
			
			//document.getElementById(sp[i]).innerHTML = minus;
			document.getElementById(spT[i]).innerHTML = sub;
			// console.log(sub);
			//tr= $("tr" + jtest );
			if(minus<0){
				minus = 0;
			}
			$("#"+sp[i]).find(".textBoxtest").attr("value",minus);
			//$('#cal_15296').find(".textbox-value").attr("value","12");
			//$('#cal_15296').numberbox('setValue', 206.12);
			//$("#"+sp[i]).find("[switchbuttonName='stButton']").switchbutton('setValue',minus);
			//x = $("#"+sp[i]).find("[switchbuttonName='stButton']").switchbutton('getValue');
			//console.log("滑動開關： "+x);
			//$('#sp[i]').numberbox('setValue', sub);
			var x = $("#"+sp[i]).find("[switchbuttonName='stButton']");
			x.switchbutton('setValue',minus);
			minus = 0;
		}
	}

	function getNextDay() {
		var time={
			getPreDate:function(pre,mydate){
				var self=this;
				// var c = new Date();
				var c = mydate;
				// var c = "2017-06-15 08:00:00";
				// c = Date(c);
				console.log(c);
				c.setDate(c.getDate() + pre);
				return self.formatDate(c);
			},
			formatDate:function(d){
				var self=this;
				return d.getFullYear() + "-" + self.getMonth1(d.getMonth()) + "-" + d.getDate();
			},
			getMonth1:function(m){
				var self=this;
				m++;
				if(m<10)
					return "0" + m.toString();
				return m.toString();
			}
		}
		function getnextDate(pre,mydate){
			var c = mydate;
			c.setDate(c.getDate+next);
			return formatDate(c);
		}
		function formateDate(x){
			return 
		}
		
	}
	$(function(){
		
		var time={
			getPreDate:function(pre,mydate){
				var self=this;
				var c = mydate;
				c.setDate(c.getDate() + pre);
				return self.formatDate(c);
			},
			formatDate:function(d){
				var self=this;
				return d.getFullYear() + "-" + self.getMonth1(d.getMonth()) + "-" + d.getDate();
			},
			getMonth1:function(m){
				var self=this;
				m++;
				if(m<10)
					return "0" + m.toString();
				return m.toString();
			}
		}
	})
	
	
	function getHourValue(){
		
		//$('#cal_15296').find(".textBoxtest").attr("value",12);
		//v = $('#cal_15296').find(".textBoxtest").val();
		//console.log("v: "+v);
		//$('#cal_15296')
		//text_v = ($this).find(".textBoxtext").val();
		//box_v  =($this).find("")
		//	$("#cal_"+str).find(".textBoxtest").removeAttr("readOnly");
		var $check_boxes = $('input[type=checkbox][name=checkbox]:checked');
		console.log($check_boxes);
	}
	
	//function changeStatus(){
		$(function(){ 
			//var thisSwitchbuttonObj = $(".state").find("[switchbuttonName='unitState']");
			//var swiButton = $("#cal_14364").find("[switchbuttonName='stButton']");
			//status= $("#cal_14364").switchbutton("options").checked;
			//var swiButton1 = $(this).parent().find("[switchbuttonName='stButton']");
			//var swiButton = $(this).parent().find("[switchbuttonName='stButton']");
			//$b_val = swiButton.val("2"); 
			//console.log(swiButton);
			//console.log(swiButton1);
			var swiButton = $(".changeStatus").find("[switchbuttonName='stButton']");
			swiButton.switchbutton({
				
				
				onChange: function(checked){
					this_Id=$(this).parent().find("input").eq(1).attr("id");
					temp = this_Id.split("_");
					str = temp[1];
					//console.log($(this).val());
					
					if (checked == true){
						//console.log("狀態： "+$(this));	
						//find("[switchbuttonName='stButton']")
						x = $(this);
						console.log(x.val());
						console.log(x);
						//xxx = $(this).parent().eq(1);
						//xxx=$(this).parent().find("[switchbuttonName='stButton']");
						//$("#cal_14364").find(".textBoxtest").attr("value","123");
						//$("#cal_14364").find(".textBoxtest").removeAttr("readOnly");
						//$("#reason_14364").next().children().first().removeAttr("readOnly");
						//$("#reason_14364").textbox('readOnly',false);
						console.log("被選中");
						$("#cal_"+str).find(".textBoxtest").removeAttr("readOnly");
						//$("#reason_"+str).next().children().first().removeAttr("readOnly");
						$("#reason_"+str).textbox('readonly',false);
						
						//console.log(str);
						//console.log(xxx);
						//console.log(swiButton);
					}
					if (checked == false){
						$("#cal_"+str).find(".textBoxtest").attr("readOnly","true");
						//txt = $("#reason_14364").textbox('getText');
						//$("#reason_14364").textbox('readOnly');
						//textbox('readonly',false);
						//$("#reason_"+str).next().children().first().attr("readOnly","true");
						$("#reason_"+str).textbox('readonly',true);
						//console.log("未啟用");
					}
				}
			});
		});
	//}
	
	
	
	/**
	$(function(){ 
		$("#cal_14364").find("[switchbuttonName='stButton']").switchbutton({
			checked: false,
			
			onChange: function(checked){
				if (checked == true){
					console.log(val());
					console.log("被選中");
				}
				if (checked == false){
					console.log("未啟用");
				}
			}
		});
	});
	*/
	function setOverType() {//設置加班類型：1、2、3
		var type = $("#overtimeType").val();
		//var cal = $("#overtimeCal").val();
		//console.log("cal: "+cal);
		var itype = "type_";
		var mType = [];
		console.log("type:" + type);
		type1 = "延時加班";
		atype = [ "", "延時加班", "例假日加班", "節假日加班" ];
		var checkbox3 = document.getElementsByName("checkbox");
		
		for ( var i = 0; i < checkbox3.length; i++) {
			str = checkbox3[i].value.split("_");
			//record[i] = checkbox3[i].value;
			
			mType[i] = itype + str[0];;
			//console.log(mType[i]);
			/**
				
			*/
			document.getElementById(mType[i]).innerHTML = atype[type];
		}

	}
	
	

	//得到時間段
	function getHour(strDate) {
		var hours = strDate.getHours();
		var mins = strDate.getMinutes();
		
		if(hours<10){
			hours="0"+hours;
		}
		// if(hours!='08'&&hours=='07'){
			// hours='08';
			// mins='00';
		// }
		if(mins<10){
			mins="0"+mins;
		}
		var Hour = hours + ":" + mins;
		return Hour;
	}
	
	
	function getHour1(strDate) {
		var hours = strDate.getHours();
		var mins = strDate.getMinutes();
		
		if(hours<10){
			hours="0"+hours;
		}
		// if(hours!='08'&&hours=='07'){
			// hours='08';
			// mins='00';
		// }
		if(mins<10){
			mins="0"+mins;
		}
		var Hour = hours + ":" + mins;
		return Hour;
	}
	//字符串轉日期格式
	function getDate1(strDate) {
		var date = eval('new Date('
				+ strDate.replace(/\d+(?=-[^-]+$)/, function(a) {
					return parseInt(a, 10) - 1;
				}).match(/\d+/g) + ')');
		return date;
	}

	function getNum(Num) {
		var front = 0;
		var surplus = 0;
		front = Math.floor(Num);
		surplus = Num - front;
		// if (surplus <= 0.25) {
			// surplus = 0;
		// } else if (surplus > 0.25 && surplus <= 0.75) {
			// surplus = 0.5;
		// } else if (surplus > 0.75) {
			// surplus = 1;
		// }
		if (surplus < 0.25) {
			surplus = 0;
		} else if (surplus > 0.25 && surplus < 0.5) {
			surplus = 0.25;
		} else if (surplus>=0.5 && surplus < 0.75) {
			surplus = 0.5;
		}else if(surplus >=0.75 && surplus < 1 ){
			surplus = 0.75;
		}
		
		return surplus + front;
	}
	
	function firm() {
		getValue();
		if (confirm("你确定提交吗？")) {

			alert("点击了确定");
		} else {
			alert("点击了取消");
		}
	}

	function update() {
		
		if (confirm("你确定提交當前選擇人員名單吗？")) {
			//獲取選中人員recordid
			//var $check_boxes = $('input[type=checkbox][checked=checked][id!=check_all_box][id!=inlineCheckbox1][name=stButton]');
			//var $check_boxes = $('input[type=checkbox][name=stButton]');
			var $check_boxes = $('input[type=checkbox][name=checkbox][id!=check_all_box][id!=inlineCheckbox1]:checked');
			var timeCal = $("#overtimeCal").val();
			var timeType = $("#overtimeType").val();
			var dropIds = new Array();
			$check_boxes.each(function() {
				dropIds.push($(this).val());
			});
			var shift = $("#Shift").val();
			var workcontent=$("#workcontent").val();
			//console.log($check_boxes);
			//console.log(dropIds);
			var lineNo=$("#LineNo").val();
			var rC_NO=$("#RC_NO").val();
			var item_No=$("#Item_No").val();
			// console.log("item_No： "+item_No);
			var jtest;
			var ids = [];
			var names = [];
			var depids = [];
			var costids = [];
			var directs = [];
			var yds = [];
			var calInterval = [];
			var calHour = [];
			var dropId=[];
			var depname=[];
			var reason=[];
			var tr = null;
			//dp= $('#3').find('input')[2].val();
			//console.log("lineNo: " +lineNo+"rC_NO: " +rC_NO+"item_No: " +item_No);
			for ( var i = 0; i < dropIds.length; i++) {
				//console.log("dropIds[i]:" + dropIds[i]);
				var str = dropIds[i].split("_");
				jtest = str[1];
				tr= $("tr[id=" + jtest + "]");
				depname[i] = tr.find('input[ID$=depname]').val();
				
				//console.log(depname[i]);
				dropId[i] = str[0];
				ids[i] = document.getElementById("tbl").rows[jtest].cells[2].innerText;
				names[i] = document.getElementById("tbl").rows[jtest].cells[3].innerText;
				depids[i] = document.getElementById("tbl").rows[jtest].cells[4].innerText;
				
				costids[i]=document.getElementById("tbl").rows[jtest].cells[5].innerText;
				directs[i]=document.getElementById("tbl").rows[jtest].cells[6].innerText;
				
				yds[i] = document.getElementById("tbl").rows[jtest].cells[7].innerText;
				//console.log(typeof(yds[i]));
				calInterval[i] = document.getElementById("tbl").rows[jtest].cells[8].innerText;
				calHour[i] = $(tr).find(".textBoxtest").val();
				if(calHour[i]<=0){
					alert("工時小於等於0，有誤，請重新選擇加班人員！");
					return false;
				}
				reason[i]=$("#reason_"+str[0]).textbox('getText');
				//reason[i]=$("#reason_"+str[0]).find(".textbox-value").val();
				//console.log("jtest:"+jtest);
				//console.log(reason[i]);
				//console.log(costids[i]+"  "+directs[i]);
				console.log(dropId[i]+" "+ ids[i]+" "+names[i]+" "+depids[i]+" "+yds[i]+" "+calInterval[i]+" "+calHour[i]);
			}
			
			
			
			
			$.ajax({
				type : 'post',
				traditional : true,
				url : 'overtime_order_pending_Update.php',
				data : {
					'dropId[]' : dropId,
					'ids[]' : ids,
					'names[]' : names,
					'depids[]' : depids,
					'depname[]':depname,
					'costids[]':costids,
					'directs[]':directs,
					'yds' : yds,
					'shift':shift,
					'calInterval[]' : calInterval,
					'calHour[]' : calHour,
					'reason[]': reason,
					'workcontent':workcontent,
					'timeCal' : timeCal,
					'timeType' : timeType,
					'lineNo':lineNo,
					'rC_NO':rC_NO,
					'item_No':item_No
				},
				success : function(msg) {
					alert("提交成功,窗口即將關閉！");
					// $("#ttt").html(msg);
					// console.log(msg);
					
					window.close();
				}
			});
			
		}
	}

	function check() {
		
		// checkBox1();
		if(checkBox1()==false){
			return false;
		}else{
			update();
		}
		
	}
	
	function isInteger(obj) {
		 return (obj | 0) === obj;
	}
	
	function checkBox1(){
		var timeCal = $("#overtimeCal").val();
		var timeType = $("#overtimeType").val();
		if (timeCal == 0 || timeType == 0) {
			alert("請選擇時間及加班類型");
			return false;
		}
		
		var $check_boxes = $('input[type=checkbox][name=checkbox][id!=check_all_box][id!=inlineCheckbox1]:checked');
		if($check_boxes.length==0){
			alert("沒有選擇加班人員，請重新選擇");
			return false;
		}
		
		
		if($("#workcontent").length >0) {
			var checkContent = $("#workcontent").val();
			if(checkContent.length==0){
				alert("請輸入工作內容！");
				return false;
			}
		}
		
		//console.log($check_boxes);
		//var $check_boxes = document.getElementById("test123").checked;
		var dropIds = new Array();
		$check_boxes.each(function() {
			dropIds.push($(this).val());
		});
		
		var text_v;
		console.log(dropIds);
		for( var i = 0; i < dropIds.length; i++){
			//console.log("dropIds[i]:" + dropIds[i]);
			var str = dropIds[i].split("_");
			jtest = str[1];
			//str[0];
			tr= $("tr[id=" + jtest + "]");
			
			//$(tr).find(".textBoxtest").val();
		 	//text_v = parseFloat(calHour[i]);
		 	text_v = $(tr).find(".textBoxtest").val();
		 	text_v = parseFloat(text_v);
		 	check_v = $(tr).find("[switchbuttonName='stButton']").val();
		 	reason_v=$("#reason_"+str[0]).textbox('getText');
		 	//console.log(text_v);
		 	//console.log(check_v);
		 	//console.log(reason_v);
		 	
		 	
		 	console.log(text_v);
		 	isInt = text_v/0.25;
		 	console.log(isInt);
		 	isInt = isInteger(isInt);
		 	console.log(isInt);
		 	if(text_v<=12&&text_v>=0&&isInt==true){
		 		if(text_v==check_v){
		 			if(reason_v.length!=0){
		 				alert("修改工時和原工時一樣,請重新確認！！！");
		 				return false;
		 			}else{
		 				
		 			}
		 		}else if(text_v<=check_v){
		 			if(reason_v.length==0){
		 				alert("請輸入修改加班工時原因，不少於6個字!");
		 				return false;
		 			}else if(reason_v.length<6){
						console.log(reason_v.length)
		 				alert("請繼續補充，不少於6個字!");
				 		return false;
		 			}else{
		 				
		 			}
		 		}else if(text_v>check_v){
					alert("修改后工時不得大於原工時！");
					return false;
				}
		 	}else{
		 		alert("非法輸入，請輸入0-12的正數！");
		 		return false;
		 	}
		 	//console.log(reason_v.length);
		}
	}
</script>
<title>SELECT Operation</title>
</head>
<body class="pace-done">
	<?php 
		// $MYSQL_LOGIN = "root";
		// $MYSQL_PASSWORD = "foxlink";
		// $MYSQL_HOST = "192.168.65.230";

		// $mysqli = new mysqli($MYSQL_HOST,$MYSQL_LOGIN,$MYSQL_PASSWORD,"swipecard");
		// $mysqli->query("SET NAMES 'utf8'");	 
		// $mysqli->query('SET CHARACTER_SET_CLIENT=utf8');
		// $mysqli->query('SET CHARACTER_SET_RESULTS=utf8'); 
		include("mysql_config.php");
	
		$SDate = $_POST['SDate'];
		$WorkshopNo = $_POST['WorkshopNo'];
		$lineno = $_POST['LineNo'];
		$RC_NO = $_POST['rc_no'];
		$Item_No = $_POST['item_no'];
		$Shift = $_POST['Shift'];
		$w=date('w',strtotime($SDate));
		if($w==6||$w==0){
			$weekend = 1;
		}else{
			$weekend = 0;//TODO
		}
		
		if($Shift=="D"){
			$employee_overtime_sql = 
				"SELECT a.id, 
					   a.NAME, 
					   a.depid,
					   a.depname,
					   a.direct,
					   a.costid,
					   Date_format(b.swipecardtime2, '%Y-%m-%d')                       AS yd,
					   b.checkstate,
					   b.recordid,
					   b.overtimeCal,
					   b.overtimeType,
					   b.swipecardtime,
					   b.swipecardtime2
				FROM   testemployee AS a, 
					   testswipecardtime AS b 
				WHERE  a.cardid = b.cardid 
						and DATE_FORMAT(b.swipecardtime, '%Y-%m-%d') = '".$SDate."'
						and swipecardtime2 is not null
						and shift = 'D'
					   AND prod_line_code = '".$lineno."'
					   AND a.depid = '".$depid."'
						AND RC_NO = '".$RC_NO."'
					   and checkstate in('0','9') ";
		}else if($Shift=="N"){
			$employee_overtime_sql = 
				"SELECT a.id, 
					   a.NAME, 
					   a.depid,
					   a.depname,
					   a.direct,
					   a.costid,
					   date_format(date_sub(swipecardtime2,interval 12 hour),'%Y-%m-%d') as yd,
					   b.checkstate,
					   b.recordid,
					   b.overtimeCal,
					   b.overtimeType,
					   b.swipecardtime,
					   b.swipecardtime2
				FROM   testemployee AS a, 
					   testswipecardtime AS b 
				WHERE  a.cardid = b.cardid 
						and DATE_FORMAT(b.swipecardtime, '%Y-%m-%d') = '".$SDate."'
						and shift = 'N'
						and swipecardtime2 is not null
					   AND prod_line_code = '".$lineno."'
					   AND a.depid = '".$depid."'
						AND RC_NO = '".$RC_NO."'
					   and checkstate in('0','9') ";
		}
		// echo $employee_overtime_sql;
		if($Shift=="D"){
			$interval_sql = "select * from interval_setting where WorkshopNo='$WorkshopNo' and weekend = '$weekend' and Shift = '$Shift'";
		}else{
			$interval_sql = "select * from interval_setting where WorkshopNo='$WorkshopNo'  and Shift = '$Shift'";
		}
		
		$timeset_row = $mysqli->query($interval_sql);
		$temp = array();
		// echo $interval_sql.'<br>';
		while($row1 = $timeset_row->fetch_assoc()){
			$temp[] = $row1['d_interval1'];
			$temp[] = $row1['d_interval2'];
			$temp[] = $row1['d_interval3'];
			$temp[] = $row1['d_interval4'];
			$temp[] = $row1['d_interval5'];
		}
		foreach($temp as $key => $value){
			if($value==end($temp)){
				$cch_t_set.= $value;
			}else{
				$cch_t_set.= $value."*";
			}
		}	
			   
		$person_sql = "select * from assistant_data where application_id='$assistant_id'";
		// echo $person_sql."<Br>";
		$zhuli_rows = $mysqli->query($person_sql);
		while($row = $zhuli_rows->fetch_assoc()){
			$application_person = $row['application_person'];
			$application_id = $row['application_id'];
			$application_dep = $row['application_dep'];
			$application_tel = $row['application_tel'];
		}
		
		mysqli_free_result($zhuli_rows);	   
		if(strcmp($application_person,"")<=0){
			echo "當前對應助理信息缺失，不能提交，請嘗試更換電腦再重新嘗試。";
		}
	?>
	<div>
	
	</div>
	<div class="panel-body" style="border: 1px solid #e1e3e6;">
		時間： <select id="overtimeCal" onclick="getValueB()">
			<option value="0">待選</option>
			<option value="1">正常班</option>
			<option value="2">假日班</option>

		</select> 加班類型： <select id="overtimeType" onclick="setOverType()">
			<option value="0">待選</option>
			<option value="1">加班1</option>
			<option value="2">加班2</option>
			<option value="3">加班3</option>
		</select>
		當前對應助理為：<?php echo $application_person;?>
		<?php 
			if($RC_NO==NULL){
				echo"工作內容：<input type=\"text\" id=\"workcontent\" />" ."";
			}
		?>
		<table class="table table-striped" id="tbl">
			<tr>
				<th class="per5"><input name="checkbox1" type="checkbox"
					id="inlineCheckbox1" value="option1" onclick="allCheck(this)">
				</th>
				<th>序號</th>
				<th>工號</th>
				<th>名字</th>
				<th>部門代碼</th>
				
				<th>費用代碼</th>
				<th>直間接人員</th>
				
				<th>加班日期</th>
				<th>加班時段</th>
				<th>加班小時</th>
				<th>加班類型</th>
				<th>審核狀態</th>
			</tr>
			<?php 
			// echo $employee_overtime_sql;
				$over_rows = $mysqli->query($employee_overtime_sql);
				// echo mysqli_num_rows($over_rows);
				$cch = '';
				$j=1;
				while($row = $over_rows->fetch_row()){
					
					// echo $row['ids'];
					// echo $row[3];
					if($row[7]==0){
						$checkState="未審核";
					}else if($row[7]==9){
						$checkState="退回";
					}
					
					
					
					echo"<tr id =\"".$j."\">"
					  . "<input type=\"hidden\" id=\"depname\" value=\"".$row[3]."\"/>"
					  . "<input type=\"hidden\" name=\"stime\" value=\"".$row[11]."\" />"
					  . "<input type=\"hidden\" name=\"etime\" value=\"".$row[12]."\" />"
					  . "<td><input id=\"checkValue\" name=\"checkbox\" type=\"checkbox\" value=\"".$row[8]."_".$j."\"></td>"
					  . "<td>".$j."</td>"
					  . "<td><input type=\"hidden\" name=\"testid\" value=\"".$row[0]."\" />".$row[0]."</td>"
					  . "<td><input type=\"hidden\" name=\"name\" value=\"".$row[1]."\" />".$row[1]."</td>"
					  . "<td><input type=\"hidden\" name=\"depid\" value=\"".$row[2]."\" />".$row[2]."</td>"
					  . "<td><input type=\"hidden\" name=\"costid\" value=\"".$row[5]."\" />".$row[5]."</td>"
					  . "<td><input type=\"hidden\" name=\"direct\" value=\"".$row[4]."\" />".$row[4]."</td>"
					  . "<td><input type=\"hidden\" name=\"yd\" value=\"".$row[6]."\" />".$row[6]."</td>"
					  . "<td id=\"tck_".$row[8]."\"></td>"
					  
					  ."<td id=\"cal_".$row[8]."\" class=\"changeStatus\" >"
					  . "<input type=\"text\" class=\"textBoxtest\" style=\"width:50px;height:32px\" value=\"\" readonly />"
					  . "<input class=\"easyui-switchbutton\" name=\"stButton\" id=\"but_".$row[8]."\" value=\"\"  /> "
					  . "</td>"
					  . "<td id=\"type_".$row[8]."\"></td>"
					  . "<td>".$checkState."</td>"
					  . "<td><input class=\"easyui-textbox\" id=\"reason_".$row[8]."\" style=\"width:100%;height:32px\" readonly /></td>"
					  
					  ."</tr>";
					 $j++;
				}
				// echo $cch;
				
			?>
		</table>
		<?
			if(strcmp($application_person,"")>0){
					echo "<input class=\"btn btn-primary\" name=\"\" type=\"button\"\n";
					echo "		onclick=\"check()\" value=\"提交\" />\n";
			}
		?>
	</div>
	<div>
		<input type="hidden" id="LineNo" value="<?php echo $lineno?>"/>
		<input type="hidden" id="RC_NO" value="<?php echo $RC_NO?>" />
		<input type="hidden" id="Item_No" value="<?php echo $Item_No?>" />
		<input type="hidden" id="Shift" value="<?php echo $Shift?>" />
		<input type="hidden" id="WorkshopNo" value="<?php echo $WorkshopNo?>" />
		<input type="hidden" id="Interval_Setting" value="<?php echo $cch_t_set //TODO_List ?>" />
	</div>
	<!-- 
	<input name="" type="button" onclick="location.href = 'index_test.jsp'"		value="返回" />
	 -->
</body>
</html>


