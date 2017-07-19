<?php 
	session_start();
	$access = $_SESSION["permission"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
<script src="assets/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
	
</script>
</head>
<body>
	<?php 
	
		// $MYSQL_LOGIN = "root";
		// $MYSQL_PASSWORD = "foxlink";
		// $MYSQL_HOST = "192.168.65.230";

		// $mysqli = new mysqli($MYSQL_HOST,$MYSQL_LOGIN,$MYSQL_PASSWORD,"swipecard");
		// $mysqli->query("SET NAMES 'utf8'");	 
		// $mysqli->query('SET CHARACTER_SET_CLIENT=utf8');
		// $mysqli->query('SET CHARACTER_SET_RESULTS=utf8'); 
		include("mysql_config.php");
		$timeCal = $_POST['timeCal'];
		$timeType = $_POST['timeType'];
		$lineNo = $_POST['lineNo'];
		$rC_NO = $_POST['rC_NO'];
		$item_No = $_POST['item_No'];
		
		if($_POST['workcontent']){
			$WorkContent = $_POST['workcontent'];
		}else{
			$WorkContent = $item_No."_".$rC_NO;
		}
		// echo "workContent: ".$WorkContent;
		$checkValue = $_POST['dropId'];
		$ids = $_POST['ids'];
		$names = $_POST['names'];
		$depids = $_POST['depids'];
		$depname = $_POST['depname'];
		$costids = $_POST['costids'];
		$directs = $_POST['directs'];
		$shift = $_POST['shift'];
		$calInterval = $_POST['calInterval'];
		$calHour = $_POST['calHour'];
		$yds = $_POST['yds'];
		
		$a = array();
		// var_dump($checkValue);
		// echo count($checkValue);
		for ($i = 0; $i < count($checkValue); $i++) {
			$a[$i][0]=$checkValue[$i];
			$a[$i][1]=$ids[$i];
			$a[$i][2]=$names[$i];
			$a[$i][3]=$depids[$i];
			$a[$i][4]=$depname[$i];
			$a[$i][5]=$calInterval[$i];
			$a[$i][6]=$calHour[$i];
			$a[$i][7]=$costids[$i];
			$a[$i][8]=$directs[$i];
			// echo ("a[1][" + $i + "]: " + $a[$i][1]);
		}
	
	for($i=0;$i<count($checkValue);$i++){
		$update_sql = "update testswipecardtime set CheckState = '1',overtimeCal='".$timeCal."',overtimeType='".$timeType."' where RecordId = '".$a[$i][0]."'";
		$cch = "insert into notes_overtime_state (id,name,depid,depname,overtimeInterval,overtimeHours,costID,Direct,overtimeDate,shift,overtimeType,LineNo,RC_NO,PRIMARY_ITEM_NO,WorkContent) value (";
		for($j=1;$j<=8;$j++){
			$cch .= "'".$a[$i][$j]."',";
		}
		$cch .= "'".$yds."',";
		$cch .= "'".$shift."',";
		$cch .= "'".$timeType."',";
		$cch .= "'".$lineNo."',";
		$cch .= "'".$rC_NO."',";
		$cch .= "'".$item_No."',";
		$cch .= "'".$WorkContent."')";
		$insert_sql = $cch;
		// $cch = '';
		// echo $insert_sql."<br>";
		
		$update_rows = $mysqli->query($update_sql);
		$insert_rows =$mysqli->query($insert_sql);
	}
	
	$mysqli->close();
?>

</body>
</html>

