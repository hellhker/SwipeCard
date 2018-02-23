<html>
<!-- Bootstrap stylesheets (included template modifications) -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>test</title>
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
	// $line_sql = "select lineno from lineno";
	// $line_rows = $mysqli->query($line_sql);
	// while($row = $line_rows->fetch_row()){
		// $lineno[] = $row[0];
	// }
	include("mysql_config.php");
	$lineno = $_POST['lineno'];
	$SDate = $_POST['SDate'];
	$EDate = $_POST['EDate'];
	$url = $_POST['urlA'];
	
	$checkState = $_POST['checkState'];
	$type = $_POST['typeE'];
	// echo $type;
	// exit;
	$temp_cost = explode("*",$costid);
	$cch = "";
	foreach($temp_cost as $key => $val){
		if(end($temp_cost)==$val){
			$cch .= "'$val'";
		}else{
			$cch .= "'$val'".",";
		}
		// echo $cch."<br>";
	}
	// echo $type;
	//工號，姓名，性別，年齡，直間接，
	// 部門編號，部門名稱，歸屬代碼，入職日期，離職日期
	// 廠區，籍貫，事業群，事業處，職稱，
	// 招募來源
	$sql = "SELECT `Work_ID`, `Name`, `Sex`, `Age`, `Type`
				, `Dep_Code`, `Dep_Name`, `Ori_Dep_Code`, `Arrive_Date`, `Leave_Date`
				, `Org`, `Native_Place`, `BG`, `BU`, `POSITION`
				, `Recruit_Unit`
			FROM employee order by leave_date desc";
			// FROM employee order by leave_date desc limit 1000,74";
	//年	月/週	月/週數值	BU	在職/離職	D/I	年資TYPE	人數
	//2018	M	10	xxx	1	I	0-5	5
	$detail_rows= $mysqli->query($sql);
	
	$count = mysqli_num_rows($detail_rows);
	
	while($row = $detail_rows->fetch_assoc()){
		$arr['Work_ID'][] = $row['Work_ID'];
		$arr['Sex'][] = $row['Sex'];
		$arr['Type'][] = $row['Type'];
		$arr['Dep_Code'][] = $row['Dep_Code'];
		$arr['Ori_Dep_Code'][] = $row['Ori_Dep_Code'];
		$arr['Arrive_Date'][] = $row['Arrive_Date'];
		$arr['Leave_Date'][] = $row['Leave_Date'];
		$arr['BG'][] = $row['BG'];
		$arr['BU'][] = $row['BU'];
		$arr['POSITION'][] = $row['POSITION'];
		$arr['Recruit_Unit'][] = $row['Recruit_Unit'];
	}
	// echo $count;
	
	// var_dump($arr['Arrive_Date']);
	// var_dump($arr['Leave_Date']);
	// var_dump($arr);
	// echo "<PRE>";
	// print_r($arr['Leave_Date']);
	// echo "</PRE>";
	// exit;
	// for($i=0;$i<$count;$i++){
		// if($arr['Leave_Date'][$i] == NULL){
			// $list['']
		// }
	// }
	// echo date('Y-m-o-W',strtotime('2017-01-01'));
	// echo date('Y-m-o-W',strtotime('2017-12-31'));
	/**
	* 去周的資料
	*/
	//年	月/週	月/週數值	BU	在職/離職	D/I	年資TYPE	人數
	//2018	M	10	xxx	1	I	0-5	5
	
	
	function getWeekDate($year,$weeknum){
		$firstdayofyear=mktime(0,0,0,1,1,$year);  
		$firstweekday=date('N',$firstdayofyear);  
		$firstweenum=date('W',$firstdayofyear);  
		if($firstweenum==1){  
			$day=(1-($firstweekday-1))+7*($weeknum-1);  
			$startdate=date('Y-m-d',mktime(0,0,0,1,$day,$year));  
			$enddate=date('Y-m-d',mktime(0,0,0,1,$day+6,$year));  
		}else{  
			$day=(9-$firstweekday)+7*($weeknum-1);  
			$startdate=date('Y-m-d',mktime(0,0,0,1,$day,$year));  
			$enddate=date('Y-m-d',mktime(0,0,0,1,$day+6,$year));  
		}  
		return array($startdate,$enddate);      
	}
	
	$i=1;
	// for($i=1;$i<=52;$i++){
		// $temp[$i] = getWeekDate(2017,$i);
		echo $count;
		for($j=0;$j<$count;$j++){
			// echo $arr['Leave_Date'][$count]."<BR>";
			if($arr['Leave_Date'][$j]==NULL){
				$list[2017][$i]['D']+=1;
				// echo $arr['Work_ID'][$j];
			}else{
				// echo $arr['Leave_Date'][$i]."<BR>";
				$list[2017][$i]['L']+=1;
				// echo $arr['Work_ID'][$j]."<BR>";
			}
		}
	// }
	
	// if($arr['Leave_Date'][1057]==NULL){
		// echo "123";
	// }
	// print_r($arr['Leave_Date'][1057]);
	// echo "---";
	// print_r($arr['Leave_Date'][1056]);
	
	echo "<PRE>";
	// print_r($arr['Leave_Date']);
	print_r($list);
	echo "</PRE>";

// ?>
</body>
</html>
