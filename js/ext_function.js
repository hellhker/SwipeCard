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
		var spellCont = "cont_";
		var sp = [];//小時數小計
		var spT = [];//時間段
		var spCont = [];//時間段
		var record = [];
		//加班時間
		var minus = 0;
		var str=null;
		// console.log(t_set);
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
			spCont[i] = spellCont + record[i];
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
					console.log(sdate1[i]);
					sdate1[i].setHours(05, 00, 0);
				}
				
				minus = (edate1[i] - sdate1[i]);
				//console.log("sdate1[0]"+typeof(sdate1[0]));
				//console.log("時長minus: "+ minus);
				//根據選擇的加班時間類型得出不同時段
				//console.log(getHour(sdate1[i]));
				// minus = minus / 3600000;	
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
						$calOn = sdate1[i]-tempTime2;
						
						if($calOn<0){
							if($calEnd>0){
								$tempEnd = tempTime2;//20.00
								if($calStart>0){
									$tempStart = sdate1[i];//08:30
									$tempStart.setSeconds(0);
								}else{
									$tempStart = tempTime1;//07:40
								}
							}else{
								if(edate1[i]-tempTime1<0){
									break;
								}
								if($calStart>0){
									$tempStart = sdate1[i];//16.30
									$tempEnd = edate1[i];
									$tempStart.setSeconds(0);
								}else{
									$tempStart = tempTime1;//16.30
									$tempEnd = edate1[i];
									$tempEnd.setSeconds(0);
								}
								// var tempStart = new Date($tempStart);
								// var tempEnd = new Date($tempEnd);
								// console.log("$tempStart:"+ tempStart);
								// console.log("$tempEnd:"+ tempEnd);
								minus += $tempEnd - $tempStart;
								break;
							}
							// var tempStart = new Date($tempStart);
							// var tempEnd = new Date($tempEnd);
							// console.log("$tempStart:"+ tempStart);
							// console.log("$tempEnd:"+ tempEnd);
							minus += $tempEnd - $tempStart;
						}
					}
					// minus = minus / 3600000;	
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
							// console.log("321");
						}
						
						// tempTime1 = new Date(tempTime1);
						// console.log("T1 :" + edate1[i]);
						// tempTime2 = new Date(tempTime2);
						// console.log("T2 :" + tempTime2);
						
						$calStart = sdate1[i]-tempTime1;
						$calEnd   = edate1[i]-tempTime2;
						
						
						$calOn = sdate1[i]-tempTime2;
						
						if($calOn<0){
							if($calEnd>0){
								$tempEnd = tempTime2;//20.00
								if($calStart>0){
									$tempStart = sdate1[i];//08:30
									$tempStart.setSeconds(0);
								}else{
									$tempStart = tempTime1;//07:40
								}
							}else{
								if($calStart>0){
									$tempStart = sdate1[i];//16.30
									$tempEnd = edate1[i];
									$tempStart.setSeconds(0);
								}else{
									$tempStart = tempTime1;//16.30
									$tempEnd = edate1[i];
									$tempEnd.setSeconds(0);
								}
								var tempStart = new Date($tempStart);
								var tempEnd = new Date($tempEnd);
								console.log("$tempStart:"+ tempStart);
								console.log("$tempEnd:"+ tempEnd);
								minus += $tempEnd - $tempStart;
								break;
							}
							var tempStart = new Date($tempStart);
							var tempEnd = new Date($tempEnd);
							console.log("$tempStart:"+ tempStart);
							console.log("$tempEnd:"+ tempEnd);
							minus += $tempEnd - $tempStart;
						}
					}
				}
				// minus = minus / 3600000;	
				// console.log(minus);	
				//根據選擇的加班時間類型得出不同時段
			}else if(cal=='3'){//TODO
				if(shift=="D"){
					var continus = 0;
					var calTemp;
					sdate1[i].setHours(15, 40, 0);
					
					// console.log(minus);
					// console.log(continus);
				}else{
					// var tempDay  = getNextDay.time.getPreDate(1,sdate1[i]);
					var tempDay = sdate1[i].getDate();
					sdate1[i].setDate(tempDay+1);
					sdate1[i].setHours(03, 40, 0);
				}
				calTemp = (edate1[i] - sdate1[i])/3600000;
				if( calTemp >0&&calTemp <=2){
					minus = calTemp;
				}else if(calTemp >2&&calTemp <=4){
					minus = 2;
					continus = calTemp - 2;
				}else if(calTemp >4){
					minus = calTemp -2;
					continus = 2;
				}
				// minus = (edate1[i] - sdate1[i]);
			}else if(cal=='4'){
				for(var j = 0;j<t_s.length;j++){
					// for(var j = 0;j<1;j++){
					if(j<t_s.length-1){
						tempInterval = t_s[j].split("-");
						tempHour1 = tempInterval[0].split(":");
						// console.log(tempHour1);
						tempHour2 = tempInterval[1].split(":");
						// console.log("A");
					}else{
						tempHour1 = t_s[j].split(":");
						tempHour2 = edate1[i];
						// console.log("B");
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
					$calStart = sdate1[i]-tempTime1;//18.30 18.00 1
					$calEnd   = edate1[i]-tempTime2;//19.30-20.00 0
					$calOn = sdate1[i]-tempTime2;
						
					if($calOn<0){
						
						if($calEnd>0){
							$tempEnd = tempTime2;//20.00
							if($calStart>0){
								$tempStart = sdate1[i];//08:30
								$tempStart.setSeconds(0);
							}else{
								$tempStart = tempTime1;//07:40
							}
						}else{
							if($calStart>0){
								$tempStart = sdate1[i];//16.30
								$tempEnd = edate1[i];
								$tempStart.setSeconds(0);
							}else{
								$tempStart = tempTime1;//16.30
								$tempEnd = edate1[i];
								$tempEnd.setSeconds(0);
							}
							// var tempStart = new Date($tempStart);
							// var tempEnd = new Date($tempEnd);
							// console.log("$tempStart:"+ tempStart);
							// console.log("$tempEnd:"+ tempEnd);
							minus += $tempEnd - $tempStart;
							break;
						}
						// var tempStart = new Date($tempStart);
						// var tempEnd = new Date($tempEnd);
						// console.log("$tempStart:"+ tempStart);
						// console.log("$tempEnd:"+ tempEnd);
						minus += $tempEnd - $tempStart;
					}
						
						
					// }
					
				}
				 // minus = minus/3600000;
			}
			
			
			minus = minus/3600000;
			// console.log(continus);
			console.log(minus);	
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
			$("#"+spCont[i]).find(".textBoxCont").attr("value",continus);
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

	
	//判斷小時數
	function getNum(Num) {
		var front = 0;
		var surplus = 0;
		front = Math.floor(Num);
		surplus = Num - front;
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
	
	/**
	
	function firm() {
		getValue();
		if (confirm("你确定提交吗？")) {

			alert("点击了确定");
		} else {
			alert("点击了取消");
		}
	}
*/
		
	//判斷是否將選取人員update
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
			
			/**
				dataType: 'json',
				sfc\tab_prod\show_capacity.php
			*/
			
			$.ajax({
				type : 'post',
				traditional : true,
				url : 'overtime_order_pending_Update123.php',
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
					// alert("提交成功,窗口即將關閉！");
					// $("#ttt").html(msg);
					console.log(msg);
					
					// window.close();
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
		}
	}
