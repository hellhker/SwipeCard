<?php 
	session_start();
	$access = $_SESSION["permission"];
	$costid = $_SESSION['costid'];
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
<link rel="stylesheet" href="assets/css/bootstrap.min.css">  
<script type="text/javascript" src="easyui/jquery.min.js"></script>
<script type="text/javascript" src="easyui/jquery.easyui.min.js"></script>
<script src="Button_Plugins.js"></script>

<script src="assets/js/bootstrap.min.js"></script>
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
		var sdate = [];
		var sdate1 = [];
		var edate = [];
		var edate1 = [];
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
				
				minus = (edate1[i] - sdate1[i]) / 3600000;
				//console.log("sdate1[0]"+typeof(sdate1[0]));
				//console.log("時長minus: "+ minus);
				//根據選擇的加班時間類型得出不同時段
				//console.log(getHour(sdate1[i]));
			} else if (cal == "2") {
				if(shift=="D"){
					minus = (edate1[i] - sdate1[i]) / 3600000 - 1.75;
				}else if(shift=="N"){
					minus = (edate1[i] - sdate1[i]) / 3600000 - 1;
				}
				// minus = (edate1[i] - sdate1[i]) / 3600000;
				//根據選擇的加班時間類型得出不同時段
				//console.log("minus:"+ minus);
			}
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
			$("#"+sp[i]).find(".textBoxtest").attr("value",minus);
			//$('#cal_15296').find(".textbox-value").attr("value","12");
			//$('#cal_15296').numberbox('setValue', 206.12);
			//$("#"+sp[i]).find("[switchbuttonName='stButton']").switchbutton('setValue',minus);
			//x = $("#"+sp[i]).find("[switchbuttonName='stButton']").switchbutton('getValue');
			//console.log("滑動開關： "+x);
			//$('#sp[i]').numberbox('setValue', sub);
			var x = $("#"+sp[i]).find("[switchbuttonName='stButton']");
			x.switchbutton('setValue',minus);
			
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
		if(hours!='08'&&hours=='07'){
			hours='08';
			mins='00';
		}
		if(mins=='0'){
			mins="00";
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
		if(mins=='0'){
			mins="00";
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
				url : 'overtime_order_pending_Update111.php',
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
					//$("#ttt").html(msg);
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
		 			}else if(reason_v.length<12){
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
		$SDate = $_POST['SDate'];
		$lineno = $_POST['LineNo'];
		$RC_NO = $_POST['rc_no'];
		$Item_No = $_POST['item_no'];
		$Shift = $_POST['Shift'];
		
		// $MYSQL_LOGIN = "root";
		// $MYSQL_PASSWORD = "foxlink";
		// $MYSQL_HOST = "192.168.65.230";
		// $mysqli = new mysqli($MYSQL_HOST,$MYSQL_LOGIN,$MYSQL_PASSWORD,"swipecard");
		// $mysqli->query("SET NAMES 'utf8'");	 
		// $mysqli->query('SET CHARACTER_SET_CLIENT=utf8');
		// $mysqli->query('SET CHARACTER_SET_RESULTS=utf8'); 
		include("mysql_config.php");
		$employee_overtime_sql = 
			"SELECT id, 
		       name, 
		       depid, 
		       rid,
			   direct,
			   shift,
		       overtimeDate, 
		       overtimeInterval, 
		       overtimeHours, 
		       overtimeType,
			   notesStates
		FROM   
		       notes_overtime_state 
		WHERE  
		        overtimedate  = '$SDate' 
		       AND LineNo = '$lineno' 
			   AND shift = '$Shift' ";
		if($RC_NO!=''){
			$employee_overtime_sql .= " AND rc_no = '$RC_NO'";
		}
		
		// echo 
		// echo $employee_overtime_sql;
		$over_rows = $mysqli->query($employee_overtime_sql);
		// echo $employee_overtime_sql;
		// while($row = $over_rows->fetch_assoc()){
					
			// echo $row['ids'];
			// echo $row[3];
			// if($row[7]==0){
				// $checkState="未審核";
			// }else if($row[7]==9){
				// $checkState="退回";
			// }	
			// $arr[] = $row['id'];
			// $arr[] = $row['overtimeDate'];
		// }
		// var_dump($arr);
		// exit;
	?>

	<div class="panel-body" style="border: 1px solid #e1e3e6;">
		<table class="table" id="tbl">
			<tr>
				<th>序號</th>
				<th>工號</th>
				<th>名字</th>
				<th>部門代碼</th>
				
				<th>直間接人員</th>
				
				<th>加班日期</th>
				<th>班別</th>
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
				while($row = $over_rows->fetch_assoc()){
					
					// echo $row['ids'];
					// echo $row[3];
					if($row['overtimeType']==1){
						$overType="延時加班";
					}elseif($row['overtimeType']==2){
						$overType="例假日加班";
					}else if($row['overtimeType']==3){
						$overType="節假日加班";
					}
					if($row['notesStates']==0){
						$checkState="待產生加班單";
					}elseif($row['notesStates']==1){
						$checkState="已產生加班單";
					}else if($row['notesStates']==2){
						$checkState="不符規定";
					}
					
					
					
					echo"<tr id =\"".$j."\">"
					  . "<input type=\"hidden\" id=\"depname\" value=\"".$row[3]."\"/>"
					  . "<input type=\"hidden\" name=\"stime\" value=\"".$row[11]."\" />"
					  . "<input type=\"hidden\" name=\"etime\" value=\"".$row[12]."\" />"
					  . "<td>".$j."</td>"
					  . "<td><input type=\"hidden\" name=\"testid\" value=\"".$row['id']."\" />".$row['id']."</td>"
					  . "<td><input type=\"hidden\" name=\"name\" value=\"".$row['name']."\" />".$row['name']."</td>"
					  . "<td><input type=\"hidden\" name=\"depid\" value=\"".$row['depid']."\" />".$row['depid']."</td>"
					  . "<td><input type=\"hidden\" name=\"direct\" value=\"".$row['direct']."\" />".$row['direct']."</td>"
					  . "<td><input type=\"hidden\" name=\"overtimeDate\" value=\"".$row['overtimeDate']."\" />".$row['overtimeDate']."</td>"
					  . "<td><input type=\"hidden\" name=\"shift\" value=\"".$row['shift']."\" />".$row['shift']."</td>"
					  . "<td><input type=\"hidden\" name=\"overtimeInterval\" value=\"".$row['overtimeInterval']."\" />".$row['overtimeInterval']."</td>"
					  . "<td><input type=\"hidden\" name=\"overtimeHours\" value=\"".$row['overtimeHours']."\" />".$row['overtimeHours']."</td>"
					  . "<td>".$overType."</td>"
					  . "<td>".$checkState."</td>"
					  
					  ."</tr>";
					 $j++;
				}
				// echo $cch;
			?>
		</table>
	</div>
	<div>
		<input type="hidden" id="LineNo" value="<?php echo $lineno?>"/>
		<input type="hidden" id="RC_NO" value="<?php echo $RC_NO?>" />
		<input type="hidden" id="Item_No" value="<?php echo $item_no?>" />
		<input type="hidden" id="Shift" value="<?php echo $Shift?>" />
	</div>
	<!-- 
	<input name="" type="button" onclick="location.href = 'index_test.jsp'"		value="返回" />
	 -->
</body>
</html>