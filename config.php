<?php 
include("mysql_config.php");
$time_inteval_setting = "select * from interval_setting where WorkshopNo='第四車間' and weekend = 0 and Shift = 'D'";
	$setting_rows = $mysqli->query($time_inteval_setting);
	while($row1= $setting_rows->fetch_assoc()){
		$setting[] = $row1['d_interval1'];
		$setting[] = $row1['d_interval2'];
		$setting[] = $row1['d_interval3'];
		$setting[] = $row1['d_interval4'];
		$setting[] = $row1['d_interval5'];
		
	}
	function getTime($stime,$edtime,$date,$setting){
		$minus = 0;
		$length =count($setting);
		$stime = date_format($stime,"Y/m/d H:i:s");
		$edtime = date_format($edtime,"Y/m/d H:i:s");
		for($j=0;$j<count($setting);$j++){
		// for($j=0;$j<1;$j++){
			// $date = date_create($date);
			if($j<$length-1){
				$tempInterval = split("-",$setting[$j]);
				$tempHour1 = split(":",$tempInterval[0]);
				$tempHour2 = split(":",$tempInterval[1]);
			}else{
				tempHour1 = t_s[j].split(":");
				tempHour2 = edate1[i];
				$tempHour1 = split(":",$tempInterval[0]);
				$tempHour2 = ;
			}
			$tempInterval = split("-",$setting[$j]);
			$tempHour1 = split(":",$tempInterval[0]);
			$tempHour2 = split(":",$tempInterval[1]);
			$tempTime1 = $date;
			$tempTime2 = $date;
			
			$tempTime1 = date_time_set($tempTime1,$tempHour1[0],$tempHour1[1],0);
			$tempTime1 = date_format($tempTime1,"Y/m/d H:i:s");
			echo $tempTime1;
			$tempTime2 = date_time_set($tempTime2,$tempHour2[0],$tempHour2[1],0);
			$tempTime2 = date_format($tempTime2,"Y/m/d H:i:s");
			echo $tempTime2;
			// echo $date;
			$calStart = strtotime($stime)-strtotime($tempTime1);
			$calEnd   = strtotime($edtime)-strtotime($tempTime2);
			// echo $calEnd."<br>";
			// exit;
			// echo $tempTime1."<br>";
			if($calEnd>0){
				$tempEnd = $tempTime2;//09:30
				if($calStart>0){
					$tempStart = $stime;//08:30
				}else{
					$tempStart = $tempTime1;//07:40
				}
			}else{
				$tempStart = $tempTime1;//09:40
				$tempEnd = $edtime;//10:30
				$minus += strtotime($tempEnd) - strtotime($tempStart);
				break;
			}
			// echo $tempEnd."<br>";
			// echo $tempStart."<br>";
			$minus += strtotime($tempEnd) - strtotime($tempStart);
			// echo $minus;
		}
		return $minus;
	}
	// $stime =date_create_from_format("Y-m-d H:i:s","2017-07-21 07:31:59");
	// $edtime =	date_create_from_format("Y-m-d H:i:s","2017-07-21 19:31:59");
	// $date = 	date_create_from_format("Y/m/d","2017/07/21");
	$stime = date_create("2017-07-21 07:31:59");
	$edtime = date_create("2017-07-21 19:31:59");
	$date = date_create("2017/07/21");
	// $stime = date_format($stime,"Y/m/d H:i:s");
	// $edtime = date_format($edtime,"Y/m/d H:i:s");
	// $date = date_format($date,"Y/m/d H:i:s");
	$ee = getTime($stime,$edtime,$date,$setting);
	// var_dump($date[]);
	// echo $date;
	echo $ee;

	