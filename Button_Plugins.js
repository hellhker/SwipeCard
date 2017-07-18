function showTableByIdentified() {
	var lineno = $("#test").val();
	var SDate = $("#dpick1").val();
	var EDate = $("#dpick2").val();
	$.ajax({
		type : 'post',
		url : 'overtime_order_identified_Show.php',
		data : {
			'lineno' : lineno,
			'SDate' : SDate,
			'EDate' : EDate
		},
		success : function(msg) {
			// alert(msg);
			$("#id").html(msg);
		}
	});
}

function showTable() {
	var lineno = $("#test").val();
	var SDate = $("#dpick1").val();
	var EDate = $("#dpick2").val();
	$.ajax({
		type : 'post',
		url : 'Select.php',
		data : {
			'lineno' : lineno,
			'SDate' : SDate,
			'EDate' : EDate
		},
		success : function(msg) {
			// alert(msg);
			$("#id").html(msg);
		}
	});
}

function showTableB() {
	var lineno = $("#test").val();
	var SDate = $("#dpick1").val();
	var EDate = $("#dpick2").val();
	$.ajax({
		type : 'post',
		url : 'overtime_order_identified_Show.php',
		data : {
			'lineno' : lineno,
			'SDate' : SDate,
			'EDate' : EDate
		},
		success : function(msg) {
			// alert(msg);
			$("#id").html(msg);
		}
	});
}

function getDValue() {
	var SDate = $("#dpick1").val();
	var EDate = $("#dpick2").val();
	alert("Start Date: " + SDate + "End Date:" + EDate);
}

/**
 * �寞��交�蝺蝑�隞嗆閰Ｖ誑�內�株��箔蜓���
 * @returns
 */
function showRCInforByDate() {
	var lineno = $("#lineno").val();
	var checkState = $("#checkState").val();
	var SDate = $("#dpick1").val();
	var EDate = $("#dpick2").val();
	
	
	var urlA = "";
	var urla1 = "overtime_order_pending_Show.php";
	var urla2 = "overtime_order_identified_Show.php";
	var urla3 = "overtime_order_check_Show.php";
	var urlB = "";
	var urlb1 = "show_overtime_rcno_ing_test.php";//TODO 待修改
	var urlb2 = "show_overtime_rcno_ed.php";
	var urlb3 = "show_overtime_rcno_all.php";
	if (checkState.indexOf("0") >= 0 || checkState.indexOf("9") >= 0) {
		urlA = urla1;
		urlB = urlb1;
	} else if (checkState == 1) {

		urlA = urla2;
		urlB = urlb2;
	} else {
		urlA = urla3;
		urlB = urlb3;
	}
	// alert("urlA: "+ urlA);
	$.ajax({
		type : 'post',
		url : urlB,
		data : {
			'lineno' : lineno,
			'checkState' : checkState,
			'SDate' : SDate,
			'EDate' : EDate,
			'urlA' : urlA
		},
		success : function(msg) {
			// alert(msg);
			$("#showRcNoTable").html(msg);
		}
	});
}


function showRCInforByDateVer1() {
	var lineno = $("#lineno").val();
	var checkState = $("#checkState").val();
	var SDate = $("#dpick1").val();
	var EDate = $("#dpick2").val();
	
	
	var urlA = "";
	var urla1 = "overtime_order_pending_Show.php";
	var urla2 = "overtime_order_identified_Show.php";
	var urla3 = "overtime_order_check_Show.php";
	var urlB = "";
	var urlb1 = "show_overtime_rcno_ing.php";
	var urlb2 = "show_overtime_rcno_ed.php";
	var urlb3 = "show_overtime_rcno_all.php";
	if (checkState.indexOf("0") >= 0 || checkState.indexOf("9") >= 0) {
		urlA = urla1;
		urlB = urlb1;
	} else if (checkState == 1) {

		urlA = urla2;
		urlB = urlb2;
	} else {
		urlA = urla3;
		urlB = urlb3;
	}
	// alert("urlA: "+ urlA);
	$.ajax({
		type : 'post',
		url : urlB,
		data : {
			'lineno' : lineno,
			'checkState' : checkState,
			'SDate' : SDate,
			'EDate' : EDate,
			'urlA' : urlA
		},
		success : function(msg) {
			// alert(msg);
			$("#showRcNoTable").html(msg);
		}
	});
}

function showRCInforByDatetest() {
	var lineno = $("#lineno").val();
	var checkState = $("#checkState").val();
	var SDate = $("#dpick1").val();
	var EDate = $("#dpick2").val();
	
	
	var urlA = "";
	var urla1 = "overtime_order_pending_Show_ver1.php";
	var urla2 = "overtime_order_identified_Show_test.php";
	var urla3 = "overtime_order_check_Show_test.php";
	var urlB = "";
	var urlb1 = "show_overtime_rcno_ing.php";
	var urlb2 = "show_overtime_rcno_ed.php";
	var urlb3 = "show_overtime_rcno_all.php";
	console.log(checkState);
	if (checkState.indexOf("0") >= 0 || checkState.indexOf("9") >= 0) {
		urlA = urla1;
		urlB = urlb1;
	} else if (checkState == 1) {

		urlA = urla2;
		urlB = urlb2;
	} else {
		urlA = urla3;
		urlB = urlb3;
	}
	// alert("urlA: "+ urlA);
	$.ajax({
		type : 'post',
		url : urlB,
		data : {
			'lineno' : lineno,
			'checkState' : checkState,
			'SDate' : SDate,
			'EDate' : EDate,
			'urlA' : urlA
		},
		success : function(msg) {
			// alert(msg);
			$("#showRcNoTable").html(msg);
		}
	});
}

function openTableWindow() {
	LGameWindow = window.open('', 'gameWindow', 'width=900,height=600,scrollbars=yes ');
	LGameWindow.focus();
	return true;
}


function showEmployeeByLineNo() {
	var lineno = $("#lineno").val();
	var SDate = $("#dpick1").val();
	var EDate = $("#dpick2").val();
	
	var urlA = "swipecardtime_byBigline_show.php";
	$.ajax({
		type : 'post',
		url : urlA,
		data : {
			'lineno' : lineno,
			'SDate' : SDate,
			'EDate' : EDate,
		},
		success : function(msg) {
			$("#showEmployeeByLineNo").html(msg);
		}
	});
}

function showEmployeeInfor() {
	var id = $("#lineno").val();
	var SDate = $("#dpick1").val();
	var EDate = $("#dpick2").val();
	
	var urlA = "EmployeeInfor_show.php";
	$.ajax({
		type : 'post',
		url : urlA,
		data : {
			'id' : id,
			'SDate' : SDate,
			'EDate' : EDate,
		},
		success : function(msg) {
			$("#showEmployeeInfor").html(msg);
		}
	});
}