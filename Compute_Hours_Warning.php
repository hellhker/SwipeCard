<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>工時預警</title>
<link href="css/bootstrap.css" rel="stylesheet">
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
	include("config.php");
	$date = date('2017/07/22');
	// $date1 = strtotime('-30 days',strtotime($date));
	$date = strtotime('-1 days',strtotime($date));
	// $date1 = strtotime('-30 days',strtotime($date));
	$date = date("Y/m/d",$date);
	// $date1 = date("Y/m/d",$date1);
	// echo $date1;
	// $sql = "select a.prod_line_code,b.id,b.name,b.depname,a.swipecardtime,a.swipecardtime2 from testswipecardtime a,testemployee b WHERE a.cardid=b.cardid and swipecardtime > date_sub(curdate(),interval 30 day) ";
	
	// $employee_sql = "select b.cardid,b.id,b.depname,b.depid from (select cardid from testswipecardtime group by cardid) a,testemployee b where a.cardid = b.cardid and b.cardid = '0087670656'";
	
	$employee_sql = "select b.cardid,b.id,b.depname,b.depid from (select cardid from testswipecardtime group by cardid) a,testemployee b where a.cardid = b.cardid and depid='XR-54' order by b.cardid";
	
	// $time_sql = "select prod_line_code,cardid,name,swipecardtime,swipecardtime2,shift,workshopno from testswipecardtime where swipecardtime > date_sub(curdate(),interval 30 day) and swipecardtime2 is not null order by cardid,swipecardtime desc";
	
	$time_sql = "select prod_line_code,cardid,name,swipecardtime,swipecardtime2,shift,WorkshopNo from testswipecardtime where swipecardtime >= date_sub('$date',interval 30 day) and date_format(swipecardtime,'%Y/%m/%d') <= '$date' and swipecardtime2 is not null order by cardid,swipecardtime desc";
	// echo $time_sql."<br>";
	
	
	
	// $time_sql = "select prod_line_code,cardid,name,swipecardtime,swipecardtime2,shift from testswipecardtime where swipecardtime > date_sub(curdate(),interval 30 day) and swipecardtime2 is not null  order by cardid,swipecardtime desc";
	
	// $time_inteval_setting = "select * from interval_setting where WorkshopNo='第四車間' and weekend = 0 and Shift = 'D'";
	// $interval_sql = "select * from interval_setting where WorkshopNo='$WorkshopNo' and weekend = '$weekend' and Shift = '$Shift'";
	$time_inteval_setting = "select * from interval_setting";
	// exit;
	// $base_rows = $mysqli->query($sql); 
	// $i=0;
	
	$base_rows = $mysqli->query($employee_sql);
	while($row= $base_rows->fetch_assoc()){
		$temp['cardid'][] = $row['cardid'];
		$temp['id'][] = $row['id'];
		$temp['depid'][] = $row['depid'];
		$temp['depname'][] = $row['depname'];
		
	}
	
	
	$time_rows = $mysqli->query($time_sql);
	while($row= $time_rows->fetch_assoc()){
		$temp1['cardid'][] = $row['cardid'];
		$temp1['name'][] = $row['name'];
		$temp1['swipecardtime'][] = $row['swipecardtime'];
		$temp1['swipecardtime2'][] = $row['swipecardtime2'];
		$temp1['shift'][] = $row['shift'];
		$temp1['WorkshopNo'][] = $row['WorkshopNo'];
	}
	
	
	$setting_rows = $mysqli->query($time_inteval_setting);
	while($row1= $setting_rows->fetch_assoc()){
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval1'];
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval2'];
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval3'];
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval4'];
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval5'];
		
	}
	// $tempInterval = split("-",$setting[0]);
	// var_dump($setting);
	// exit;
	
	// var_dump($setting);
		// exit;
	// foreach($temp1 as )\
	$con = count($temp1['cardid']);
	$conb = count($temp['cardid']);
	$temp2 =array();
	// echo $con;
	$k = 0;
	for($i = 0;$i<$con; $i++){
		for($j=0;$j<$conb;$j++){
			$k++;
			if($temp1['cardid'][$i]==$temp['cardid'][$j]){
				// $temp2[0][$i] = $temp[2][$j];
				// $temp2[1][$i] = $temp[3][$j];
				// $temp2[2][$i] = $temp[1][$j];
				// $temp2[3][$i] = $temp1[1][$i];
				
				// $temp2[4][$i] = $temp1[2][$i];
				// $temp2[5][$i] = $temp1[3][$i];
				// $temp2[6][$i] = $temp1[4][$i];
				
				$temp2[$temp['id'][$j]][0][] = $temp['depid'][$j];
				$temp2[$temp['id'][$j]][1][] = $temp['depname'][$j];
				$temp2[$temp['id'][$j]][2][] = $temp['id'][$j];
				
				$temp2[$temp['id'][$j]][3][] = $temp1['name'][$i];
				$temp2[$temp['id'][$j]][4][] = $temp1['swipecardtime'][$i];
				$temp2[$temp['id'][$j]][5][] = $temp1['swipecardtime2'][$i];
				$temp2[$temp['id'][$j]][6][] = $temp1['shift'][$i];
				$temp2[$temp['id'][$j]][7][] = date('Y/m/d',strtotime($temp1['swipecardtime'][$i]));
				$temp2[$temp['id'][$j]][8][] = $temp1['WorkshopNo'][$i];
				// $temp2[$temp[1][$j]][8][] = date('Y-m-d',strtotime($temp1[3][$i]));
				// echo $i."<br>";
				// echo "k: ".$k."<br>";
				break 1;
			}
		}
		// echo "i: ".$i."<bR>";
	}
	// echo $k;
	// date('Y-m-d',strtotime($temp[4][$j]));
	
	var_dump($temp2);
	exit;
	// $i = count($temp2[]);
	// for($j=0;$j<$i;$j++){//TODO
		// $temp2[7][$j] = date('Y-m-d',strtotime($temp2[4][$j]));
		// $temp2[8][$j] = date('Y-m-d',strtotime($temp2[5][$j]));
	// }
	// $date = date_create(date('Y-m-d'));
	
	// date('Y-m-d');
	// date_sub($date,date_interval_create_from_date_string("1 days"));
	// $date = date('2017/07/22');
	// $date = strtotime('-1 days',strtotime($date));
	// $date = date("Y/m/d",$date);
	// echo $date;
	// exit;

	
	foreach($temp2 as $key => $value){
		// echo $key."<br>";
		$i=0;
		$flag=0;
		$temp4[$key]['cont_date']=0;
		// $temp4[$key]['con_time']=0;
		// echo "key".$key."<Br>";
		foreach($value as $key1 => $value1){
			$sub = (strtotime($value[7][$i])-strtotime($value[7][$i+1]))/86400;
			// echo $value[7][0];
			if($value[7][0]==$date){
				if($flag==0){
					$temp4[$key]['cont_date']++;
					// echo $value[4][$i]."<br>";
					// echo $value[5][$i]."<br>";
					// echo $value[6][$i]."<br>";
					// echo $value[7][$i]."<br>";
					// $temp2[$temp[1][$j]][6][]
					// $tempCal = getTime($value[4][$i],$value[5][$i],$value[7][$i]);
					// echo "tempCal: ".$tempCal;
					// $value[4][$i]=date_create($value[4][$i]);
					// $value[5][$i]=date_create($value[5][$i]);
					$weekend = getWeekend($value[7][$i]);
					$interval_setting = $setting[$value[8][$i]][$weekend][$value[6][$i]];
					$tempCal = getTime($value[4][$i],$value[5][$i],$value[7][$i],$interval_setting,$value[6][$i],$value[3][$i]);
					$temp4[$key]['con_time'] += $tempCal;
					
					$flag=1;
					// echo "123";
				}
			}else{
				$temp4[$key]['cont_date']=0;
				$temp4[$key]['con_time']= 0;
				// echo "123";
				break 1;
			}
			
			if($sub==1){
				$temp4[$key]['cont_date']++;
				$tempCal = getTime($value[4][$i],$value[5][$i],$value[7][$i],$interval_setting,$value[6][$i],$value[3][$i]);
				$temp4[$key]['con_time'] += $tempCal;
			}else{
				break 1;
			}
			// echo $value[4][$i]." ".$value[5][$i]." ".$tempCal."<br>";
			// echo $i."<br>";
			$i++;
			
			// var_dump($value[4][$i]);
		}
		$temp3[$key]['depid'] = $value[0][0];
		$temp3[$key]['depname'] = $value[1][0];
		$temp3[$key]['id'] = $value[2][0];
		$temp3[$key]['name'] = $value[3][0];
		$temp3[$key]['cont_date'] = $temp4[$key]['cont_date'];
		$temp3[$key]['cont_time'] = $temp4[$key]['con_time'];
		$temp3[$key]['date_interval'] = $value[7][$i]." - ".$value[7][0];
		// $value[7][0]
		// echo $value[7][$i];
		// $temp3[$key][0][] = $temp[2][$j];
		// $temp3[$key][1][] = $temp[3][$j];
		// $temp3[$key][2][] = $temp[1][$j];
		
		// $temp3[$key][3][] = $temp1[1][$i];
		// $temp3[$key][4][] = $temp1[2][$i];
		// $temp3[$key][5][] = $temp1[3][$i];
		// $temp3[$key][6][] = $temp1[4][$i];
		// $temp3[$key][7][] = date('Y-m-d',strtotime($temp1[2][$i]));
		
		// $i=0;
		// $tempa =  count($value[0]);
		// for($i=0;$i<$tempa;$i++){
			// $temp2[$key][]
		// }
		
	}	
	 var_dump($temp3);
	exit;
	
	
	
	// exit;
	while($row = $base_rows->fetch_row()){
		$temp[0][$i] = $row[0];
		$temp[1][$i] = $row[1];
		$temp[2][$i] = $row[2];
		$temp[3][$i] = $row[3];
		$temp[4][$i] = $row[4];
		$temp[5][$i] = $row[5];
		$i++;
	}
	for($j=0;$j<$i;$j++){
		if(strlen($temp[5][$j])==0){
			$temp[5][$j]="NULL";
			$temp[6][$j]=0;
		} else{
			$Hi = strtotime(date("H:i",strtotime($temp[5][$j])));
			$tempHi = strtotime("17:30");
			$calHi = $Hi-$tempHi;//假如刷卡
			
			if($calHi>0){
				$temp[6][$j] = strtotime($temp[5][$j]) - strtotime($temp[4][$j])-7200;
			}else{
				$temp[6][$j] = strtotime($temp[5][$j]) - strtotime($temp[4][$j]);
			}
		}
		
		// echo $j." ".$temp[4][$j]." ".$temp[5][$j]." ".$temp[6][$j]." <br>";
		// echo $j." ".$temp[6][$j]." <br>";
	}
	// echo date("H:i",strtotime($temp[5][20]))."<br>";
	// echo strtotime($temp[5][20]) - strtotime($temp[4][20]);
	for($j=0;$j<$i;$j++){//TODO
		$temp[7][$j] = date('Y-m-d',strtotime($temp[4][$j]));
		$temp[8][$j] = date('Y-m-d',strtotime($temp[5][$j]));
	}
	// echo strtotime(0)."<br>";
	for($j=0;$j<$i;$j++){
		if(strlen($temp[5][$j])>0){
			// $temp[6][$j]
		}
		// echo strtotime(0)."<br>";
		// echo $j." ".$temp[4][$j]." ".$temp[5][$j]." <br>";
		// echo $j." ".$temp[7][$j]." ".$temp[8][$j]."<br>";
	}
	$contiDay = 0;
	$sumHour = 0;
	$flag = 0;
	// $Date={1,2,3,4};
	// for($j=0;$j<4;$j++){
			// if(($j+1)==$i){
				// $arr[$flag][0] = $temp[1][$j];
				// $arr[$flag][1] = $temp[2][$j];
				// $arr[$flag][2] = $temp[3][$j];
				// $arr[$flag][3] = $contiDay;	
				// $Hour = $sumHour + $temp[6][$j];	
				// $front = floor($Hour/3600);
				// $surplus = $Hour/3600 - $front;
				// if ($surplus < 0.25) {
					// $surplus = 0;
				// } else if ($surplus > 0.25 && $surplus < 0.5) {
					// $surplus = 0.25;
				// } else if ($surplus>=0.5 && $surplus < 0.75) {
					// $surplus = 0.5;
				// }else if($surplus >=0.75 && $surplus < 1 ){
					// $surplus = 0.75;
				// }
				// $arr[$flag][4] = $front+ $surplus;
				// return;
			// }else if($temp[1][$j]==$temp[1][$j+1]){//假如第一行與第二行相等
				// if($temp[6][$j]!=0){
					// $contiDay++;
					// $sumHour = $sumHour + $temp[6][$j];
				// }else{
					// $contiDay =0;
					// $sumHour = 0;
				// }
				// echo $sumHour."<br>";
			// }else if($temp[1][$j]!=$temp[1][$j+1]){
				// $arr[$flag][0] = $temp[1][$j];
				// $arr[$flag][1] = $temp[2][$j];
				// $arr[$flag][2] = $temp[3][$j];
				// $arr[$flag][3] = $contiDay;	
				// $front = floor($sumHour/3600);
				// $surplus = $sumHour/3600 - $front;
				// if ($surplus < 0.25) {
					// $surplus = 0;
				// } else if ($surplus > 0.25 && $surplus < 0.5) {
					// $surplus = 0.25;
				// } else if ($surplus>=0.5 && $surplus < 0.75) {
					// $surplus = 0.5;
				// }else if($surplus >=0.75 && $surplus < 1 ){
					// $surplus = 0.75;
				// }
				// $arr[$flag][4] = $front+ $surplus;
				// $sumHour = 0;
				// $contiDay = 0;
				// $flag++;
			// }
	// }
	
	
	for($j=0;$j<4;$j++){
		if($temp[6][$j]!=0){
			
			if($temp[1][$j]==$temp[1][$j+1]){
				
			}else if($temp[1][$j]!=$temp[1][$j+1]){
				
			}
		}else{
			$contiDay =0;
			$sumHour = 0;
		}
		
		
	}
	var_dump($temp);
	
	
	
	// for($k=0;$k<$x;$k++){
		// for($j=0;$j<5;$j++){
			// if($j==4)
				// echo $arr[$j][$k]."<br>";
			// else
				// echo $arr[$j][$k]."\t";
		// }
	// }
	
?>
	
</body>
</html>
