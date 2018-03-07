<html>
<!-- Bootstrap stylesheets (included template modifications) -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>test</title>
</head>
<body>
<?php 
	echo date("Y-m-d H:i:s");
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
	//TODO
	echo date('Y-m-o-W',strtotime('2017-01-01'))."<BR>";
	// exit;
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
	
	/**
	* 這個函數用來
	* 確定是離職還是在職
	*/
	function containsWeek($nowWeek,$inTime,$outTime){
		// echo $outTime."<BR>";
		// $BeginEndTime = getWeekDate(2017,$nowWeek);
		// $startTime  =  $BeginEndTime[0];
		// $endTime = $BeginEndTime[1];
		//2017-01-01  2017-01-2016-52 得到 年月年周
		//入職日期，離職日期，當前周數
		$inWeek  = date('o-W',strtotime($inTime));
		if($outTime==NULL||$outTime==""||$outTime==0){
			$outWeek==0;
		}else{
			$outWeek  = date('o-W',strtotime($outTime));
		}
		
		// $nowWeek = date('o-W',strtotime($startTime));
		$nowWeek = "2017-".$nowWeek;
		if($outWeek==0){
			if($nowWeek>=$inWeek){
				$status="True";
			}else{
				$status="NaN";
			}
		}else{
			if($nowWeek==$outWeek){
				$status = "False";
			}else if($nowWeek>$outWeek){
				$status = "NaN";
			}else if($nowWeek>=$inWeek){
				$status="True";
			}
		}
		return $status;
	}
	
	// $i=1;
	// $temp = getWeekDate(2017,1);
	// var_dump($temp);
	
	// exit;
	
	
	for($i=50;$i<=50;$i++){
		// $temp[$i] = getWeekDate(2017,$i);
		// echo $count;
		for($j=0;$j<$count;$j++){
			// if($arr['Leave_Date'][$j]==NULL){//在職人員
				// $list[2017][$i]['D']+=1;
			// }else if($arr['Leave_Date'][$j]>$temp[$i][0]){
				// $list[2017][$i]['D']+=1;
			// }
			// else{//離職人員
				// $list[2017][$i]['L']+=1;
			// }
			// 1、在職情況
			// a、在某一周內入職的，若不離職，那麼這周以後的所有周都在職
			// b、入職、離職日期都有，若不在同一周 ，那麼除了離職日期的那一周，均都在職
			// 2、離職情況
			// a、入職、離職日期都在同一周的
			// b、入職、離職日期都有，若不在同一周，那麼離職日期那一周，計入離職
			// 3、補充
			// a、若是入職日期在某周之後，不計入在職；
			// b、若是離職以後，只有時間段之間的周，才計入在職或是離職，後續不再統計。
			//年	月/週	月/週數值	BU	在職/離職	D/I	年資TYPE	人數

			$status = containsWeek($i,$arr['Arrive_Date'][$j],$arr['Leave_Date'][$j]);
			if($status=="True"){
				
				$list[2017][$i]['D'][$arr['Type'][$j]][$arr['BU'][$j]]+=1;
				// $list[2017][$i]['D']+=1;
			}else if($status=="False"){
				$list[2017][$i]['L'][$arr['Type'][$j]][$arr['BU'][$j]]+=1;
				// $list[2017][$i]['L']+=1;
			}else if($status=="NaN"){
				$list[2017][$i]['N']+=1;
				$empty[] = $arr['Work_ID'][$j];
				$empty1[] = $arr['Leave_Date'][$j];
			}
		}
	}
	
	
	
	// $nowWeek = date('o-W',strtotime('2017-01-01'));
	$nowWeek1 = date('o-W',strtotime('2017-04-01'));
	// echo date('Y-m-o-W',strtotime('2017-12-31'));
	// if($nowWeek>=$nowWeek1){
		// echo "Big";
	// }else{
		// echo "Small";
	// }
	// echo $nowWeek;
	echo $nowWeek1."<BR>";
	// $st = containsWeek($nowWeek,$inTime,$outTime)
	// $st = containsWeek(14,"2017-01-02","2017-04-01");
	// echo $st;
	
	// $arr1 = getWeekDate(2017,13);
	// var_dump($arr1);
	// if($arr['Leave_Date'][1057]==NULL){;
		// echo "123";
	// }
	// print_r($arr['Leave_Date'][1057]);
	// echo "---";
	// print_r($arr['Leave_Date'][1056]);
	
	
	echo "<PRE>";
	var_dump($c);
	// var_dump($empty1);
	echo "</PRE>";
	echo date("Y-m-d H:i:s");
	for($i=0;$i<52;$i++){
		
	}
	
	
	
	$sql = "INSERT INTO `di_statistic`(`Num_Year`, `Month_Or_Week`, `Num_Value`, `BU`, `Current_Status`, `Position_Type`, `Seniority_Type`, `Total_People`) VALUES ('".$Year."'".$M_W.""."')"
	
// ?>
</body>
</html>
